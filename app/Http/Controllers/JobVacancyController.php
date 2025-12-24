<?php

namespace App\Http\Controllers;

use App\Http\Requests\applyJobRequest;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Services\ResumeAnalysisService;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class JobVacancyController extends Controller
{

    protected $resumeAnalysisService;
    public function __construct(ResumeAnalysisService $resumeAnalysisService)
    {
        $this->resumeAnalysisService = $resumeAnalysisService;
    }

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

        $jobVacancy = JobVacancy::findOrFail($id);

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

            $fileUrl = config('filesystems.disks.cloud.endpoint') . '/' . config('filesystems.disks.cloud.bucket') . '/' . $filePath;

            // Extract information using OpenAI API (omitted for brevity)

            $extractedInfo = $this->resumeAnalysisService->extractResumeInformation($fileUrl);

            $resume = Resume::create([
                'filename' => $originalFileName,
                'fileUri' => $filePath,
                'userId' => auth()->id(),
                'contactDetails' => json_encode([
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ]),
                'summary' => $extractedInfo['summary'],
                // ensure arrays are stored as JSON strings
                'education' => is_string($extractedInfo['education']) ? $extractedInfo['education'] : json_encode($extractedInfo['education']),
                'skills' => is_string($extractedInfo['skills']) ? $extractedInfo['skills'] : json_encode($extractedInfo['skills']),
                'experience' => is_string($extractedInfo['experience']) ? $extractedInfo['experience'] : json_encode($extractedInfo['experience']),
            ]);

            $resumeId = $resume->id;
        } else {
            // Handle existing resume selection
            $resumeId = $request->input('resume_option');
            $resume = Resume::findOrFail($resumeId);

            $extractedInfo = [
                'summary' => $resume->summary,
                'education' => is_string($resume->education) ? json_decode($resume->education, true) : $resume->education,
                'skills' => is_string($resume->skills) ? json_decode($resume->skills, true) : $resume->skills,
                'experience' => is_string($resume->experience) ? json_decode($resume->experience, true) : $resume->experience,
            ];
        }
        // evaluate job fit using OpenAI API (omitted for brevity)

        $evaluation = $this->resumeAnalysisService->analyzeResume($jobVacancy, $extractedInfo);

        // Here you can create a JobApplication record linking the existing resume to the job vacancy
        JobApplication::create([
            "status" => "pending",
            "userId" => auth()->id(),
            "resumeId" => $resumeId,
            'jobVacancyId' => $id,
            "aiGeneratedScore" => $evaluation['aiGeneratedScore'],
            "aiGeneratedFeedback" => $evaluation['aiGeneratedFeedback'],
        ]);
        return redirect()->route('jobApplications.index')->with('success', 'Application submitted successfully.');
    }
}
