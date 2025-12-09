<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = JobVacancy::query();

        // Search functionality SQL fuzzy search
        if ($request->has('search') && $request->type == null) {
            $search = $request->input('search');
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('location', 'like', '%' . $search . '%')
                ->orWhereHas('company', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%');
                });
        }

        if ($request->has('type') && $request->search == null) {
            $type = $request->input('type');
            $query->where('type', $type);
        }

        if ($request->has('type') && $request->has('search')) {
            $type = $request->input('type');
            $search = $request->input('search');
            $query->where('type', $type)
                ->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('location', 'like', '%' . $search . '%')
                        ->orWhereHas('company', function ($q2) use ($search) {
                            $q2->where('name', 'like', '%' . $search . '%')
                                ->orWhere('address', 'like', '%' . $search . '%');
                        });
                });
        }

        $jobs = $query->latest()->paginate(10)->onEachSide(1);

        return view('dashboard', compact('jobs'));
    }
}
