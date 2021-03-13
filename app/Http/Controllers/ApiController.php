<?php

namespace App\Http\Controllers;

use App\Repositories\ApiRepository;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * @var ApiRepository
     */
    private $apiRepository;

    /**
     * ApiController constructor.
     * @param ApiRepository $apiRepository
     */
    public function __construct(ApiRepository $apiRepository)
    {
        $this->apiRepository = $apiRepository;
    }

    /**
     * Create Bank.
     * @param Request $request
     */
    public function createBank(Request $request)
    {
        return $this->apiRepository->createBank($request);
    }

    /**
     * Create Bank Branch.
     *
     * @param Request $request
     */
    public function createBankBranch(Request $request)
    {
        return $this->apiRepository->createBankBranch($request);
    }

    /**
     * Create Customer.
     *
     * @param Request $request
     */
    public function createCustomer(Request $request)
    {
        return $this->apiRepository->createCustomer($request);
    }

    /**
     * Money Transfer.
     *
     * @param Request $request
     */
    public function moneyTransfer(Request $request)
    {
        return $this->apiRepository->moneyTransfer($request);
    }
}
