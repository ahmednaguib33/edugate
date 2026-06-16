<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUniversityRequest;
use App\Http\Requests\Admin\UpdateUniversityRequest;
use App\Http\Resources\UniversityResource;
use App\Models\University;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class UniversityController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $universities = University::query()
            ->withCount('programs')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('name_en', 'like', $term)->orWhere('name_ar', 'like', $term));
            })
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->orderBy('name_en')
            ->paginate($request->integer('per_page', 20));

        return UniversityResource::collection($universities);
    }

    public function store(StoreUniversityRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['slug'] ??= $this->uniqueSlug($data['name_en']);

        $university = University::create($data);

        return (new UniversityResource($university))->response()->setStatusCode(201);
    }

    public function show(University $university): UniversityResource
    {
        $university->loadCount('programs')->load('programs.faculty');

        return new UniversityResource($university);
    }

    public function update(UpdateUniversityRequest $request, University $university): UniversityResource
    {
        $university->update($request->validated());

        return new UniversityResource($university->fresh());
    }

    public function destroy(University $university): JsonResponse
    {
        $university->delete();

        return response()->json(['message' => 'University deleted.']);
    }

    protected function uniqueSlug(string $base): string
    {
        $slug = Str::slug($base);
        $original = $slug !== '' ? $slug : 'university';
        $slug = $original;
        $i = 2;

        while (University::where('slug', $slug)->exists()) {
            $slug = $original.'-'.$i++;
        }

        return $slug;
    }
}
