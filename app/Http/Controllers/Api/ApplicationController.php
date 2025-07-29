<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
// use App\Models\User; // No longer needed for test user
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\ApplicationStatusUpdated;


class ApplicationController extends Controller
{
    public function updateStatus(Request $request, Application $application): JsonResponse
{
    // Kiểm tra quyền người dùng (chỉ Admin hoặc quản trị viên được cập nhật)
    // if (!auth()->user()->isAdmin()) return response()->json(['message' => 'Unauthorized'], 403);

    $request->validate([
        'status' => 'required|in:approved,rejected,processing',
    ]);

    $application->status = $request->input('status');
    $application->save();

    // Gửi thông báo (qua email + database) tới học sinh
    $application->user->notify(new ApplicationStatusUpdated($application));

    return response()->json([
        'message' => 'Trạng thái hồ sơ đã được cập nhật và thông báo đã được gửi.',
        'data' => $application->load('user')
    ]);
}
    /**
     * Display a listing of the resource for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $applications = $request->user()->applications()->with('aspirations.major')->latest()->get();

        return response()->json(['data' => $applications]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        try {
            $application = DB::transaction(function () use ($validated, $user) {
                // 1. Create the application
                $application = $user->applications()->create([
                    'submitted_at' => now()
                ]);

                // 2. Create aspirations
                foreach ($validated['aspirations'] as $aspirationData) {
                    $application->aspirations()->create($aspirationData);
                }

                // 3. Handle file uploads
                foreach ($validated['documents'] as $type => $file) {
                    $originalName = $file->getClientOriginalName();
                    // Path: applications/{user_id}/{application_id}/{file_type}_{timestamp}.ext
                    $path = $file->store("applications/{$user->id}/{$application->id}", 'public');

                    $application->documents()->create([
                        'document_type' => $type,
                        'file_path' => $path,
                        'original_filename' => $originalName,
                    ]);
                }

                return $application;
            });

            return response()->json($application->load(['aspirations.major', 'documents']), 201);

        } catch (\Exception $e) {
            Log::error('Application submission failed: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred during submission.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application): JsonResponse
    {
        // Policy: Ensure the user can only view their own application
        if (request()->user()->id !== $application->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($application->load(['aspirations.major', 'documents']));
    }
}
