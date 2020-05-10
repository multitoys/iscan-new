<?php

namespace App\Http\Controllers;

use App\Models\EntryLog;
use Illuminate\Http\Request;

class EntryLogController extends Controller
{
    public function __invoke()
    {
        $logs = EntryLog::latest()->paginate(50);

        return view('entry_log.index', [
            'logs' => $logs,
        ]);
    }
}
