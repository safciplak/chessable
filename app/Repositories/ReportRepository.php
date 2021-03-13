<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ReportRepository
{
    /**
     * Find By Branch Higher Balance.
     *
     * @return \Illuminate\Support\Collection
     */
    public function findByBranchHigherBalance()
    {
        return DB::table('customers')
            ->rightJoin('bank_branches', 'bank_branches.id', '=', 'customers.bank_branch_id')
            ->groupBy('customers.bank_branch_id')
            ->orderBy(DB::raw("SUM(customers.balance)"), 'DESC')
            ->get(['bank_branches.name', DB::raw("COALESCE(SUM(customers.balance), 0) as balance")]);
    }

    /**
     * At Least Two Customer With Balance 50.000 for each Branch.
     *
     * @return \Illuminate\Support\Collection
     */
    public function atLeastTwoCustomerWith50k()
    {
        return DB::table('customers')
            ->rightJoin('bank_branches', 'bank_branches.id', '=', 'customers.bank_branch_id')
            ->groupBy('customers.bank_branch_id')
            ->having(DB::raw('COUNT(customers.bank_branch_id)'), '>=', 2)
            ->having(DB::raw('SUM(customers.balance)'), '>', 50000)
            ->orderBy(DB::raw("SUM(customers.balance)"), 'DESC')
            ->get(['bank_branches.name', DB::raw("COALESCE(SUM(customers.balance), 0) as balance")]);
    }
}
