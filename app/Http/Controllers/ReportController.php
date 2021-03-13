<?php

namespace App\Http\Controllers;

use App\Repositories\ReportRepository;

class ReportController extends Controller
{
    /**
     * @var ReportRepository
     */
    private $reportRepository;

    /**
     * ReportController constructor.
     *
     * @param ReportRepository $reportRepository
     */
    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /**
     * Find By Branch Higher Balance.
     *
     * @return \Illuminate\Support\Collection
     */
    public function findByBranchHigherBalance()
    {
        return $this->reportRepository->findByBranchHigherBalance();
    }

    public function atLeastTwoCustomerWith50k()
    {
        return $this->reportRepository->atLeastTwoCustomerWith50k();
    }
}
