<?php

namespace App\Services;

use App\Enums\TimeEntryType;
use App\Models\TimeEntry;
use App\Models\User;
use App\Repositories\TimeEntryRepository;
use Illuminate\Support\Facades\DB;

class TimeEntryService
{
    public function __construct(
        private readonly TimeEntryRepository $timeEntryRepository
    ) {}

    public function getEmployeeTimeEntries(User $employee): array
    {
        $timeEntries = $this->timeEntryRepository
            ->paginateByEmployee($employee);

        $lastEntry = $this->timeEntryRepository
            ->findLastByEmployee($employee);

        return [
            'timeEntries' => $timeEntries,
            'nextType' => $this->determineNextType($lastEntry),
        ];
    }

    public function register(User $employee): TimeEntry
    {
        return DB::transaction(function () use ($employee) {
            $this->timeEntryRepository->lockEmployee($employee);

            $lastEntry = $this->timeEntryRepository
                ->findLastByEmployee($employee);

            $nextType = $this->determineNextType($lastEntry);

            return $this->timeEntryRepository->create(
                $employee,
                $nextType
            );
        });
    }

    private function determineNextType(?TimeEntry $lastEntry): TimeEntryType
    {
        return $lastEntry?->type === TimeEntryType::Entry
            ? TimeEntryType::Exit
            : TimeEntryType::Entry;
    }
}
