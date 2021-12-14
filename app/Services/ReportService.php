<?php

namespace App\Services;

use App\Repositories\ReportRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportService
{
    protected $repo;

    public function __construct(ReportRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getReportByMerchant($req)
    {
        // call method getDataMerchant from reportRepository
        $data = $this->repo->getDataMerchant($req);

        // Create pagination manually
        $current_page = LengthAwarePaginator::resolveCurrentPage(); // Get current page
        $per_page = $req['per_page']; // Per page from query params
        $current_items = array_slice($data, $per_page * ($current_page - 1), $per_page); // Create an array slice for paginating
        $data = (new LengthAwarePaginator($current_items, count($data), $per_page, $current_page))->setPath(route('report.merchant')); // generate route manually

        $data->each(function ($item, $key) use ($data) {
            $data[$key] = (Object)$item;
        }); // convert array to object item
        return $data;
    }

    public function getReportByOutlet($req)
    {
        // call method getDataOutlet from reportRepository
        $data = $this->repo->getDataOutlet($req);

        // Create pagination manually
        $current_page = LengthAwarePaginator::resolveCurrentPage(); // Get current page
        $per_page = $req['per_page']; // Per page from query params
        $current_items = array_slice($data, $per_page * ($current_page - 1), $per_page); // Create an array slice for paginating
        $data = (new LengthAwarePaginator($current_items, count($data), $per_page, $current_page))->setPath(route('report.outlet')); // generate route manually

        $data->each(function ($item, $key) use ($data) {
            $data[$key] = (Object)$item;
        }); // convert array to object item
        return $data;
    }
}
