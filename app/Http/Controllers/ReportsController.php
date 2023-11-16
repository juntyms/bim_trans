<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionReport;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\ReportsResource;

class ReportsController extends Controller
{
    public function monthly(ReportRequest $request)
    {
        $start_report = \Carbon\Carbon::parse($request->start_date)->format('Y-m');

        $end_report = \Carbon\Carbon::parse($request->end_date)->format('Y-m');


        $reports = TransactionReport::whereBetween('trans_ym',[$start_report,$end_report])
            ->get();

        return ReportsResource::collection(
            $reports
        );

    }
}
