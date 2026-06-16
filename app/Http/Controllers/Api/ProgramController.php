<?php

namespace App\Http\Controllers\Api;

use App\Enums\DegreeLevel;
use App\Enums\ProgramLanguage;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProgramController extends Controller
{
    /**
     * List active programs with filtering, search and sorting (public catalog).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $programs = Program::query()
            ->active()
            ->with('faculty')
            ->withCount('universities')
            ->when($request->filled('degree_level'), function ($q) use ($request) {
                $level = $request->string('degree_level')->toString();
                if (in_array($level, DegreeLevel::values(), true)) {
                    $q->where('degree_level', $level);
                }
            })
            ->when($request->filled('language'), function ($q) use ($request) {
                $language = $request->string('language')->toString();
                if (in_array($language, ProgramLanguage::values(), true)) {
                    $q->where('language', $language);
                }
            })
            ->when($request->filled('faculty'), function ($q) use ($request) {
                $faculty = $request->string('faculty')->toString();
                $q->whereHas('faculty', function ($fq) use ($faculty) {
                    $fq->where('slug', $faculty)->orWhere('id', $faculty);
                });
            })
            ->when($request->filled('university'), function ($q) use ($request) {
                $university = $request->string('university')->toString();
                $q->whereHas('universities', function ($uq) use ($university) {
                    $uq->where('universities.slug', $university)
                        ->orWhere('universities.id', $university);
                });
            })
            ->when($request->filled('max_tuition'), fn ($q) => $q->where('tuition_min', '<=', $request->float('max_tuition')))
            ->when($request->filled('min_tuition'), fn ($q) => $q->where('tuition_max', '>=', $request->float('min_tuition')))
            ->when($request->boolean('featured'), fn ($q) => $q->where('is_featured', true))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(function ($q) use ($term) {
                    $q->where('title_en', 'like', $term)
                        ->orWhere('title_ar', 'like', $term)
                        ->orWhere('description_en', 'like', $term)
                        ->orWhere('description_ar', 'like', $term);
                });
            });

        $this->applySorting($programs, $request->string('sort')->toString());

        return ProgramResource::collection(
            $programs->paginate($request->integer('per_page', 15))->withQueryString()
        );
    }

    /**
     * Show a single active program with faculty and offering universities.
     */
    public function show(Program $program): ProgramResource
    {
        abort_if(! $program->is_active, 404);

        $program->load(['faculty', 'universities' => fn ($q) => $q->where('is_active', true)]);

        return new ProgramResource($program);
    }

    /**
     * @param  Builder<Program>  $query
     */
    protected function applySorting($query, string $sort): void
    {
        match ($sort) {
            'tuition_asc' => $query->orderBy('tuition_min'),
            'tuition_desc' => $query->orderByDesc('tuition_max'),
            'newest' => $query->latest(),
            'title' => $query->orderBy('title_en'),
            default => $query->orderByDesc('is_featured')->orderBy('title_en'),
        };
    }
}
