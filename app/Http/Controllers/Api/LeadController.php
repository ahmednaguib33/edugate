<?php

namespace App\Http\Controllers\Api;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Application;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    /**
     * Capture a public lead — a prospective student submitting interest in a
     * program without needing an account. Stored as an application that the
     * back-office (agents/admins) can then pick up and manage.
     */
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $application = Application::create([
            ...$request->validated(),
            'user_id' => null,
            'status' => ApplicationStatus::Pending,
            'source' => 'website',
        ]);

        return response()->json([
            'message' => 'Thank you! Your request has been received. Our team will contact you shortly.',
            'data' => [
                'application_number' => $application->application_number,
                'status' => $application->status->value,
            ],
        ], 201);
    }
}
