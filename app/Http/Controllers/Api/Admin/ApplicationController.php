<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['nullable', 'string', Rule::in(['pending', 'processing', 'approved', 'rejected'])],
        ]);

        $applications = Application::with('user:id,name,email') // Chỉ lấy thông tin cần thiết của user
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

        return response()->json($application);
    }
}
