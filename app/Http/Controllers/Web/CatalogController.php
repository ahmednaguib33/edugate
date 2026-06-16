<?php

namespace App\Http\Controllers\Web;

use App\Enums\DegreeLevel;
use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\University;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home(): View
    {
        $featured = Program::query()
            ->active()
            ->with('faculty')
            ->withCount('universities')
            ->orderByDesc('is_featured')
            ->orderBy('title_en')
            ->limit(6)
            ->get();

        $faculties = Faculty::query()->active()->withCount(['programs' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('name_en')->limit(8)->get();

        $universities = University::query()->active()->orderBy('name_en')->get();

        return view('home', [
            'featured' => $featured,
            'faculties' => $faculties,
            'universities' => $universities,
            'stats' => [
                'programs' => Program::where('is_active', true)->count(),
                'faculties' => Faculty::where('is_active', true)->count(),
                'universities' => University::where('is_active', true)->count(),
            ],
        ]);
    }

    public function programs(Request $request): View
    {
        $programs = Program::query()
            ->active()
            ->with('faculty')
            ->withCount('universities')
            ->when($request->filled('degree_level'), fn ($q) => $q->where('degree_level', $request->string('degree_level')))
            ->when($request->filled('faculty'), fn ($q) => $q->whereHas('faculty', fn ($fq) => $fq->where('slug', $request->string('faculty'))))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('title_en', 'like', $term)->orWhere('title_ar', 'like', $term));
            })
            ->when($request->string('sort')->toString() === 'tuition_asc', fn ($q) => $q->orderBy('tuition_min'))
            ->when($request->string('sort')->toString() === 'tuition_desc', fn ($q) => $q->orderByDesc('tuition_max'))
            ->when(! $request->filled('sort'), fn ($q) => $q->orderByDesc('is_featured')->orderBy('title_en'))
            ->paginate(12)
            ->withQueryString();

        return view('programs.index', [
            'programs' => $programs,
            'faculties' => Faculty::active()->orderBy('name_en')->get(),
            'degreeLevels' => DegreeLevel::cases(),
            'filters' => $request->only(['degree_level', 'faculty', 'q', 'sort']),
        ]);
    }

    public function program(Program $program): View
    {
        abort_if(! $program->is_active, 404);

        $program->load(['faculty', 'universities' => fn ($q) => $q->where('is_active', true)]);

        $related = Program::query()
            ->active()
            ->where('faculty_id', $program->faculty_id)
            ->where('id', '!=', $program->id)
            ->with('faculty')
            ->limit(3)
            ->get();

        return view('programs.show', [
            'program' => $program,
            'related' => $related,
        ]);
    }

    public function faculties(): View
    {
        $faculties = Faculty::query()
            ->active()
            ->withCount(['programs' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('name_en')
            ->get();

        return view('faculties.index', ['faculties' => $faculties]);
    }

    public function universities(): View
    {
        $universities = University::query()
            ->active()
            ->withCount(['programs' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('name_en')
            ->get();

        return view('universities.index', ['universities' => $universities]);
    }
}
