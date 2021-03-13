<?php

namespace App\Repositories;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiRepository
{
    /**
     * Create Bank.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createBank(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:banks'
            ]);

            $validatedData['created_at'] = Carbon::now();
            $validatedData['updated_at'] = Carbon::now();

            $id = DB::table('banks')
                ->insertGetId($validatedData);

            return response()->json([
                'data' => [
                    'bank_id' => $id
                ],
                'message' => 'Your bank has been successfully created'
            ], 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    /**
     * Create Bank Branch.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createBankBranch(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:bank_branches',
                'bank_id' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            $validatedData['created_at'] = Carbon::now();
            $validatedData['updated_at'] = Carbon::now();

            DB::table('bank_branches')
                ->insert($validatedData);

            return response()->json([
                'message' => 'Your bank branch has been successfully created'
            ], 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    /**
     * Create Customer.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCustomer(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:customers',
                'bank_branch_id' => 'required',
            ]);

            $validatedData['balance'] = Customer::INITIAL_BALANCE;
            $validatedData['created_at'] = Carbon::now();
            $validatedData['updated_at'] = Carbon::now();

            DB::table('customers')
                ->insert($validatedData);

            return response()->json([
                'message' => 'Your customer has been successfully created'
            ], 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    /**
     * Money Transfer.
     *
     * @param Request $request
     */
    public function moneyTransfer(Request $request)
    {
        $senderCustomerId = $request->get('sender_customer_id');
        $receiverCustomerId = $request->get('receiver_customer_id');
        $amount = $request->get('amount');

        try {
            $balanceResult = $this->checkBalanceControl($senderCustomerId, $amount);

            if ($balanceResult) {
                $this->sendMoney($senderCustomerId, $amount);

                $this->getMoney($receiverCustomerId, $amount);

                return response()->json(['message' => sprintf("Customer id: %s sent %s to customer id: %s.", $senderCustomerId, $amount, $receiverCustomerId)]);
            }
        } catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    /**
     * Send Money.
     *
     * @param $senderCustomerId
     * @param $amount
     */
    public function sendMoney($senderCustomerId, $amount)
    {
        return DB::table('customers')
            ->where('id', $senderCustomerId)
            ->decrement('balance', $amount);
    }

    /**
     * Get Money.
     *
     * @param $receiverCustomerId
     * @param $amount
     */
    private function getMoney($receiverCustomerId, $amount)
    {
        return DB::table('customers')
            ->where('id', $receiverCustomerId)
            ->increment('balance', $amount);
    }

    /**
     * Check Balance Control.
     *
     * @param $senderCustomerId
     * @param $amount
     * @return bool|\Illuminate\Http\JsonResponse
     */
    private function checkBalanceControl($senderCustomerId, $amount)
    {
        if ($amount <= 0) {
            throw new \Exception('Amount greater than 0');
        }

        $sender = DB::table('customers')
            ->where('id', $senderCustomerId)
            ->first();

        if ($sender->balance < $amount) {
            throw new \Exception('Not enough credits');
        }

        return true;
    }
}
