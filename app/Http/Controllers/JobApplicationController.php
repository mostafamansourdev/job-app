<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index()
    {

        $jobApplications = JobApplication::where('userId', auth()->id())->latest()->paginate(10);
        // show archived
        if (request()->has('archive') && request()->input('archive') === 'true') {
            $jobApplications = JobApplication::onlyTrashed()->where('userId', auth()->id())->latest()->paginate(10);
        }

        return view('job-applications.index', compact('jobApplications'));
    }
}
