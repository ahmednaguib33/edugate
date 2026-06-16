<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApplicationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $applications = Application::query()
            ->with(['student', 'program.faculty', 'preferredUniversity', 'assignedAgent'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('program_id'), fn ($q) => $q->where('program_id', $request->integer('program_id')))
            ->when($request->filled('assigned_agent_id'), fn ($q) => $q->where('assigned_agent_id', $request->integer('assigned_agent_id')))
            ->when($request->boolean('unassigned'), fn ($q) => $q->whereNull('assigned_agent_id'))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(function ($q) use ($term) {
                    $q->where('full_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('application_number', 'like', $term);
                });
            })
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return ApplicationResource::collection($applications);
    }

    public function show(Application $application): ApplicationResource
    {
        $application->load(['student', 'program.faculty', 'program.universities', 'preferredUniversity', 'assignedAgent']);

        return new ApplicationResource($application);
    }

    public function update(UpdateApplicationRequest $request, Application $application): ApplicationResource
    {
        $application->update($request->validated());

        $application->load(['student', 'program.faculty', 'preferredUniversity', 'assignedAgent']);

        return new ApplicationResource($application);
    }

    public function destroy(Application $application): JsonResponse
    {
        $application->delete();

        return response()->json(['message' => 'Application deleted.']);
    }
}
