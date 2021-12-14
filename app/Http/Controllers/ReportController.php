<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;

class ReportController extends Controller
{
    protected $service;

    /**
     * Create a new ReportController instance.
     *
     * @return void
     */
    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    /**
     * Get Report data by merchant
     * queryParams : {
     *  per_page, e.g per_page = 3
     *  page, e.g page = 1
     *  transaction_date | optional, e.g transaction_date = 2021-11-05
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReportByMerchant(Request $request)
    {
        $data = $this->service->getReportByMerchant($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil didapatkan',
            'data' => $data
        ], 200);
    }

    /**
     * Get Report data by outlet
     * queryParams : {
     *  per_page, e.g per_page = 3
     *  page, e.g page = 1
     *  transaction_date | optional, e.g transaction_date = 2021-11-05
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReportByOutlet(Request $request)
    {
        $data = $this->service->getReportByOutlet($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil didapatkan',
            'data' => $data
        ], 200);
    }
}
