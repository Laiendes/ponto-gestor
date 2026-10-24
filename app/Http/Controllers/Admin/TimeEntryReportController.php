<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TimeEntryReportRequest;
use App\Services\TimeEntryReportService;
use Illuminate\View\View;

class TimeEntryReportController extends Controller
{
    public function __construct(
        private readonly TimeEntryReportService $timeEntryReportService
    ) {}

    public function index(TimeEntryReportRequest $request): View
    {
        $filters = $request->validated();

        $timeEntries = $this->timeEntryReportService->getReport(
            manager: $request->user(),
            startDate: $filters['start_date'] ?? null,
            endDate: $filters['end_date'] ?? null
        );

        return view('admin.time-entries.index', [
            'timeEntries' => $timeEntries,
            'filters' => $filters,
        ]);
    }
}
