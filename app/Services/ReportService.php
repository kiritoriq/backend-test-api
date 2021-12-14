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
        $data = $this->repo->getDataMerchant($req);

        $current_page = LengthAwarePaginator::resolveCurrentPage();
        $per_page = $req['per_page'];
        $current_items = array_slice($data, $per_page * ($current_page - 1), $per_page);
        $data = (new LengthAwarePaginator($current_items, count($data), $per_page, $current_page))->setPath(route('report.merchant'));

        $data->each(function ($item, $key) use ($data) {
            $data[$key] = (Object)$item;
        });
        return $data;
    }

    public function getReportByOutlet($req)
    {
        $data = $this->repo->getDataOutlet($req);

        $current_page = LengthAwarePaginator::resolveCurrentPage();
        $per_page = $req['per_page'];
        $current_items = array_slice($data, $per_page * ($current_page - 1), $per_page);
        $data = (new LengthAwarePaginator($current_items, count($data), $per_page, $current_page))->setPath(route('report.outlet'));

        $data->each(function ($item, $key) use ($data) {
            $data[$key] = (Object)$item;
        });
        return $data;
    }
}
