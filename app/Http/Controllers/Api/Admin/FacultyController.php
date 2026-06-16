<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFacultyRequest;
use App\Http\Requests\Admin\UpdateFacultyRequest;
use App\Http\Resources\FacultyResource;
use App\Models\Faculty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class FacultyController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $faculties = Faculty::query()
            ->withCount('programs')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('name_en', 'like', $term)->orWhere('name_ar', 'like', $term));
            })
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->orderBy('name_en')
            ->paginate($request->integer('per_page', 20));

        return FacultyResource::collection($faculties);
    }

    public function store(StoreFacultyRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['slug'] ??= $this->uniqueSlug($data['name_en']);

        $faculty = Faculty::create($data);

        return (new FacultyResource($faculty))->response()->setStatusCode(201);
    }

    public function show(Faculty $faculty): FacultyResource
    {
        $faculty->loadCount('programs')->load('programs');

        return new FacultyResource($faculty);
    }

    public function update(UpdateFacultyRequest $request, Faculty $faculty): FacultyResource
    {
        $faculty->update($request->validated());

        return new FacultyResource($faculty->fresh());
    }

    public function destroy(Faculty $faculty): JsonResponse
    {
        $faculty->delete();

        return response()->json(['message' => 'Faculty deleted.']);
    }

    protected function uniqueSlug(string $base): string
    {
        $slug = Str::slug($base);
        $original = $slug !== '' ? $slug : 'faculty';
        $slug = $original;
        $i = 2;

        while (Faculty::where('slug', $slug)->exists()) {
            $slug = $original.'-'.$i++;
        }

        return $slug;
    }
}
