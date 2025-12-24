<?php

namespace App\Http\Controllers;

use App\Http\Requests\applyJobRequest;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class JobVacancyController extends Controller
{
    public function show($id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        return view('job-vacancies.show', compact('jobVacancy'));
    }

    public function apply($id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $resumes = auth()->user()->resume;
        return view('job-vacancies.apply', compact('jobVacancy', 'resumes'));
    }

    public function processApplication(applyJobRequest $request, $id)
    {
        // Handle existing resume selection
        $resumeId = null;
        $extractedInfo = null;

        if ($request->input('resume_option') === 'new_resume') {
            $file = $request->file('resume_file');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = $file->getClientOriginalName();
            $fileName = 'resume_' . time() . '.' . $extension;

            // Store the file in the 'cloud' disk (configured for S3)
            $filePath = $file->storeAs('resumes', $fileName, 'cloud');

            // $fileUrl = config('filesystems.disks.cloud.endpoint') . '/' . config('filesystems.disks.cloud.bucket') . '/' . $filePath;

            // ToDo: Extract information using OpenAI API (omitted for brevity)

            $extractedInfo = [
                'summary' => '',
                'education' => '',
                'skills' => '',
                'experience' => '',
            ];


            $resume = Resume::create([
                'filename' => $originalFileName,
                'fileUri' => $filePath,
                'userId' => auth()->id(),
                'contactDetails' => json_encode([
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ]),
                'summary' => $extractedInfo['summary'],
                'education' => $extractedInfo['education'],
                'skills' => $extractedInfo['skills'],
                'experience' => $extractedInfo['experience'],
            ]);

            $resumeId = $resume->id;
        } else {
            // Handle existing resume selection
            $resumeId = $request->input('resume_option');
            $resume = Resume::findOrFail($resumeId);

            $extractedInfo = [
                'summary' => $resume->summary,
                'education' => $resume->education,
                'skills' => $resume->skills,
                'experience' => $resume->experience,
            ];
        }
        // Todo: evaluate job fit using OpenAI API (omitted for brevity)

        // Here you can create a JobApplication record linking the existing resume to the job vacancy
        JobApplication::create([
            "status" => "pending",
            "userId" => auth()->id(),
            "resumeId" => $resumeId,
            'jobVacancyId' => $id,
            "aiGeneratedScore" => 0,
            "aiGeneratedFeedback" => '',
        ]);
        return redirect()->route('dashboard')->with('success', 'Application submitted successfully using existing resume.');
    }
}
