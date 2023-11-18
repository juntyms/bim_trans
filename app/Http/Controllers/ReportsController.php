<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\TransactionReport;
use App\Http\Requests\ReportRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReportsResource;

class ReportsController extends Controller
{
    use HttpResponses;
    /**
     * @OA\POST(
     *  tags={"Report"},
     *  path="/api/v1/report",
     *  summary="Report",
     *  security={ {"bearerToken": {}} },
     *  @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"start_date","end_date"},
     *              @OA\Property(
     *                  property="start_date",
     *                  type="string",
     *                  format="date",
     *                  example="2023-09-30"
     *              ),
     *              @OA\Property(
     *                  property="end_date",
     *                  type="string",
     *                  format="date",
     *                  example="2023-10-30"
     *              ),
     *          ),
     *      ),
     *  ),
     *  @OA\Response(response=200, description="OK"),
     *  @OA\Response(response=401, description="Unauthorized")
     * )
     *
     */
    public function monthly(ReportRequest $request)
    {
        // if (1 != Auth::user()->is_admin) {
        //     return $this->error('','Only Admin can create a transaction',401);
        // }
        if ($this->adminAuthorization()) {
            return $this->adminAuthorization();
        }

        $start_report = \Carbon\Carbon::parse($request->start_date)->format('Y-m');

        $end_report = \Carbon\Carbon::parse($request->end_date)->format('Y-m');


        $reports = TransactionReport::whereBetween('trans_ym',[$start_report,$end_report])
            ->get();

        return ReportsResource::collection(
            $reports
        );

    }
}
