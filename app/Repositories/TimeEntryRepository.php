<?php

namespace App\Repositories;

use App\Enums\TimeEntryType;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TimeEntryRepository
{
    public function paginateByEmployee(User $employee, int $perPage = 10): LengthAwarePaginator
    {
        return $employee->timeEntries()
            ->orderByDesc('recorded_at')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function findLastByEmployee(User $employee): ?TimeEntry
    {
        return $employee->timeEntries()
            ->orderByDesc('recorded_at')
            ->orderByDesc('id')
            ->first();
    }

    public function lockEmployee(User $employee): void
    {
        User::query()
            ->whereKey($employee->id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    public function create(User $employee, TimeEntryType $type): TimeEntry
    {
        $timeEntry = new TimeEntry;

        $timeEntry->user()->associate($employee);
        $timeEntry->type = $type;
        $timeEntry->recorded_at = now();

        $timeEntry->save();

        return $timeEntry;
    }
}
