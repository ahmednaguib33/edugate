<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Aggregated statistics for the admin dashboard.
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'data' => [
                'universities' => [
                    'total' => University::count(),
                    'active' => University::where('is_active', true)->count(),
                ],
                'faculties' => [
                    'total' => Faculty::count(),
                    'active' => Faculty::where('is_active', true)->count(),
                ],
                'programs' => [
                    'total' => Program::count(),
                    'active' => Program::where('is_active', true)->count(),
                    'by_degree_level' => Program::query()
                        ->selectRaw('degree_level, COUNT(*) as count')
                        ->groupBy('degree_level')
                        ->pluck('count', 'degree_level'),
                ],
                'students' => User::where('role', UserRole::Student)->count(),
                'agents' => User::where('role', UserRole::Agent)->count(),
                'applications' => [
                    'total' => Application::count(),
                    'unassigned' => Application::whereNull('assigned_agent_id')->count(),
                    'by_status' => Application::query()
                        ->selectRaw('status, COUNT(*) as count')
                        ->groupBy('status')
                        ->pluck('count', 'status'),
                ],
                'recent_applications' => ApplicationResource::collection(
                    Application::with(['student', 'program.faculty'])->latest()->limit(5)->get()
                ),
            ],
        ]);
    }
}
