<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\TimeEntryReportRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class TimeEntryReportService
{
    public function __construct(
        private readonly TimeEntryReportRepository $timeEntryReportRepository
    ) {}

    public function getReport(
        User $manager,
        ?string $startDate = null,
        ?string $endDate = null
    ): LengthAwarePaginator {
        return $this->timeEntryReportRepository->paginateByManager(
            managerId: $manager->id,
            startDate: $startDate,
            endDate: $endDate
        );
    }
}
