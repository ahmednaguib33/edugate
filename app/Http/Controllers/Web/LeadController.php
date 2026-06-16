<?php

namespace App\Http\Controllers\Web;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;

class LeadController extends Controller
{
    /**
     * Handle the public "request information / apply" form on the website.
     */
    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $application = Application::create([
            ...$request->validated(),
            'user_id' => null,
            'status' => ApplicationStatus::Pending,
            'source' => 'website',
        ]);

        return back()->with('lead_success', [
            'message' => __('Thank you! Your request has been received. Our team will contact you shortly.'),
            'application_number' => $application->application_number,
        ]);
    }
}
