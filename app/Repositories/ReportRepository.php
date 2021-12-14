<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ReportRepository
{
    public function getDataMerchant($req)
    {
        $where = '';
        if (isset($req['transaction_date'])) {
            $where = "where Date = '".$req['transaction_date']."'";
        }
        return DB::select("
                WITH _tanggal AS (
                    select FROM_UNIXTIME(UNIX_TIMESTAMP(CONCAT('2021-11-',n)),'%Y-%m-%d') as Date from (
                        select (((b4.0 << 1 | b3.0) << 1 | b2.0) << 1 | b1.0) << 1 | b0.0 as n
                                from  (select 0 union all select 1) as b0,
                                      (select 0 union all select 1) as b1,
                                      (select 0 union all select 1) as b2,
                                      (select 0 union all select 1) as b3,
                                      (select 0 union all select 1) as b4 ) t
                        where n > 0 and n <= day(last_day('2021-11-01'))
                )
                , _dasar AS (
                    SELECT t.*, m.user_id, m.merchant_name, o.outlet_name
                    FROM transactions t
                    JOIN merchants m ON t.merchant_id = m.id
                    JOIN outlets o ON t.outlet_id = o.id
                    ORDER BY t.created_at ASC
                )
                , _gabung AS (
                    SELECT tgl.Date,
                        if(tgl.Date = DATE(d.created_at), d.merchant_name, 'Tidak ada transaksi') as merchant_name,
                        if(tgl.Date = DATE(d.created_at), SUM(d.bill_total), 0) as omzet
                    FROM _tanggal tgl
                    LEFT JOIN _dasar d ON tgl.Date = DATE(d.created_at) AND d.user_id = ".auth('api')->user()->id."
                    GROUP BY d.merchant_id, tgl.Date
                    ORDER BY tgl.Date ASC
                )
                SELECT * FROM _gabung $where;
        ");
    }

    public function getDataOutlet($req)
    {
        $where = '';
        if (isset($req['transaction_date'])) {
            $where = "where Date = '".$req['transaction_date']."'";
        }
        return DB::select("
                WITH _tanggal AS (
                    select FROM_UNIXTIME(UNIX_TIMESTAMP(CONCAT('2021-11-',n)),'%Y-%m-%d') as Date from (
                        select (((b4.0 << 1 | b3.0) << 1 | b2.0) << 1 | b1.0) << 1 | b0.0 as n
                                from  (select 0 union all select 1) as b0,
                                      (select 0 union all select 1) as b1,
                                      (select 0 union all select 1) as b2,
                                      (select 0 union all select 1) as b3,
                                      (select 0 union all select 1) as b4 ) t
                        where n > 0 and n <= day(last_day('2021-11-01'))
                )
                , _dasar AS (
                    SELECT t.*, m.user_id, m.merchant_name, o.outlet_name
                    FROM transactions t
                    JOIN merchants m ON t.merchant_id = m.id
                    JOIN outlets o ON t.outlet_id = o.id
                    ORDER BY t.created_at ASC
                )
                , _gabung AS (
                    SELECT tgl.Date,
                        if(tgl.Date = DATE(d.created_at), d.merchant_name, 'Tidak ada transaksi') as merchant_name,
                        if(tgl.Date = DATE(d.created_at), d.outlet_name, 'Tidak ada transaksi') as outlet_name,
                        if(tgl.Date = DATE(d.created_at), SUM(d.bill_total), 0) as omzet
                    FROM _tanggal tgl
                    LEFT JOIN _dasar d ON tgl.Date = DATE(d.created_at) AND d.user_id = ".auth('api')->user()->id."
                    GROUP BY d.merchant_id, d.outlet_id, tgl.Date
                    ORDER BY tgl.Date ASC
                )
                SELECT * FROM _gabung $where;
        ");
    }
}
