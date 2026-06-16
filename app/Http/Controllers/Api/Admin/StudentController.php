<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StudentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $students = User::query()
            ->where('role', UserRole::Student)
            ->withCount('applications')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return UserResource::collection($students);
    }

    public function show(User $user): UserResource
    {
        abort_if(! $user->isStudent(), 404);

        $user->load(['applications.program.faculty', 'applications.preferredUniversity']);

        return (new UserResource($user))->additional([
            'applications' => ApplicationResource::collection($user->applications),
        ]);
    }
}
