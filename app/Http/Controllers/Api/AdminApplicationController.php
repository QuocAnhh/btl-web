<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Notifications\ApplicationStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class AdminApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['nullable', 'string', Rule::in(['pending', 'processing', 'approved', 'rejected'])],
        ]);

        $applications = Application::with('user:id,name,email')
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest('submitted_at')
            ->paginate(15);

        return response()->json($applications);
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application): JsonResponse
    {
        return response()->json($application->load(['user', 'aspirations.major', 'documents']));
    }

    /**
     * Update the status of the specified resource in storage.
     */
    public function updateStatus(Request $request, Application $application): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['processing', 'approved', 'rejected'])],
        ]);

        $application->status = $validated['status'];
        $application->save();

        // Send notification to the user
        $application->user->notify(new ApplicationStatusUpdated($application));

        return response()->json($application);
    }
}
