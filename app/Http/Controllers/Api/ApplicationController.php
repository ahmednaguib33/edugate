<?php

namespace App\Http\Controllers\Api;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApplicationController extends Controller
{
    /**
     * List the authenticated student's applications.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $applications = Application::query()
            ->where('user_id', auth('api')->id())
            ->with(['program.faculty', 'preferredUniversity'])
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return ApplicationResource::collection($applications);
    }

    /**
     * Submit a new application.
     */
    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $application = Application::create([
            ...$request->validated(),
            'user_id' => auth('api')->id(),
            'status' => ApplicationStatus::Pending,
            'source' => 'portal',
        ]);

        $application->load(['program.faculty', 'preferredUniversity']);

        return (new ApplicationResource($application))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Show one of the authenticated student's applications.
     */
    public function show(Application $application): ApplicationResource
    {
        abort_if($application->user_id !== auth('api')->id(), 403);

        $application->load(['program.faculty', 'program.universities', 'preferredUniversity', 'assignedAgent']);

        return new ApplicationResource($application);
    }
}
