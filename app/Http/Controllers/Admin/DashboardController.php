<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Season;
use App\Models\Submission;
use App\Models\SubmissionVersion;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $kpis = [
            'total_clubs' => Club::query()->count(),
            'submissions_received' => SubmissionVersion::query()->count(),
            'pending_payments' => Submission::query()->where('payment_status', 'pending')->count(),
            'in_review_payments' => Submission::query()->where('payment_status', 'in_review')->count(),
            'paid_payments' => Submission::query()->where('payment_status', 'paid')->count(),
            'pending_corrections' => SubmissionVersion::query()
                ->whereIn('status', ['received', 'under_review'])
                ->where('version_number', '>', 1)
                ->count(),
            'rejected_versions' => SubmissionVersion::query()->where('status', 'rejected')->count(),
            'accepted_versions' => SubmissionVersion::query()->where('status', 'accepted')->count(),
        ];

        return view('admin.dashboard', [
            'kpis' => $kpis,
            'seasons' => Season::query()->orderByDesc('year')->get(),
            'recentSubmissions' => Submission::query()
                ->with(['season', 'division', 'club', 'versions' => fn ($query) => $query->latest('version_number')->limit(1)])
                ->withCount('versions')
                ->latest('updated_at')
                ->limit(10)
                ->get(),
        ]);
    }
}
