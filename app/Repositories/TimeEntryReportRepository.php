<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TimeEntryReportRepository
{
    public function paginateByManager(
        int $managerId,
        ?string $startDate = null,
        ?string $endDate = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        $page = LengthAwarePaginator::resolveCurrentPage();

        $offset = ($page - 1) * $perPage;

        $bindings = [
            'manager_id' => $managerId,
        ];

        $conditions = [
            'employee.manager_id = :manager_id',
        ];

        if ($startDate !== null) {
            $conditions[] = 'te.recorded_at >= :start_date';
            $bindings['start_date'] = $startDate.' 00:00:00';
        }

        if ($endDate !== null) {
            $conditions[] = 'te.recorded_at < DATE_ADD(:end_date, INTERVAL 1 DAY)';
            $bindings['end_date'] = $endDate;
        }

        $where = implode(' AND ', $conditions);

        $from = <<<'SQL'
            FROM time_entries AS te
            INNER JOIN users AS employee ON employee.id = te.user_id
            INNER JOIN users AS manager ON manager.id = employee.manager_id
        SQL;

        $total = DB::selectOne(
            "SELECT COUNT(*) AS total {$from} WHERE {$where}",
            $bindings
        )->total;

        $records = DB::select(
            <<<SQL
                SELECT
                    te.id,
                    employee.name AS employee_name,
                    employee.position,
                    TIMESTAMPDIFF(YEAR, employee.birth_date, CURDATE()) AS age,
                    manager.name AS manager_name,
                    te.type,
                    te.recorded_at
                {$from}
                WHERE {$where}
                ORDER BY te.recorded_at DESC, te.id DESC
                LIMIT :limit OFFSET :offset
            SQL,
            [
                ...$bindings,
                'limit' => $perPage,
                'offset' => $offset,
            ]
        );

        return new LengthAwarePaginator(
            $records,
            (int) $total,
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => request()->query(),
            ]
        );
    }
}
