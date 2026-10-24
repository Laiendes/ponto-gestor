<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTimeEntryRequest;
use App\Services\TimeEntryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimeEntryController extends Controller
{
    public function __construct(
        private readonly TimeEntryService $timeEntryService
    ) {}

    public function index(Request $request): View
    {
        $data = $this->timeEntryService->getEmployeeTimeEntries(
            $request->user()
        );

        return view('employee.time-entries.index', $data);
    }

    public function store(StoreTimeEntryRequest $request): RedirectResponse
    {
        $this->timeEntryService->register(
            $request->user()
        );

        return redirect()
            ->route('employee.time-entries.index')
            ->with('success', 'Ponto registrado com sucesso.');
    }
}
