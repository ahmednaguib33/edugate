<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProgramRequest;
use App\Http\Requests\Admin\UpdateProgramRequest;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $programs = Program::query()
            ->with('faculty')
            ->withCount('universities')
            ->when($request->filled('degree_level'), fn ($q) => $q->where('degree_level', $request->string('degree_level')))
            ->when($request->filled('faculty_id'), fn ($q) => $q->where('faculty_id', $request->integer('faculty_id')))
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('title_en', 'like', $term)->orWhere('title_ar', 'like', $term));
            })
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return ProgramResource::collection($programs);
    }

    public function store(StoreProgramRequest $request): JsonResponse
    {
        $data = $request->validated();
        $universityIds = $data['university_ids'] ?? [];
        unset($data['university_ids']);

        $data['slug'] ??= $this->uniqueSlug($data['title_en']);

        $program = Program::create($data);

        if (! empty($universityIds)) {
            $program->universities()->sync($universityIds);
        }

        $program->load('faculty', 'universities');

        return (new ProgramResource($program))->response()->setStatusCode(201);
    }

    public function show(Program $program): ProgramResource
    {
        $program->load('faculty', 'universities');

        return new ProgramResource($program);
    }

    public function update(UpdateProgramRequest $request, Program $program): ProgramResource
    {
        $data = $request->validated();

        if (array_key_exists('university_ids', $data)) {
            $program->universities()->sync($data['university_ids'] ?? []);
            unset($data['university_ids']);
        }

        $program->update($data);

        return new ProgramResource($program->fresh()->load('faculty', 'universities'));
    }

    public function destroy(Program $program): JsonResponse
    {
        $program->delete();

        return response()->json(['message' => 'Program deleted.']);
    }

    protected function uniqueSlug(string $base): string
    {
        $slug = Str::slug($base);
        $original = $slug !== '' ? $slug : 'program';
        $slug = $original;
        $i = 2;

        while (Program::where('slug', $slug)->exists()) {
            $slug = $original.'-'.$i++;
        }

        return $slug;
    }
}
