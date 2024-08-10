<?php

namespace AppModules\Wallet\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use AppModules\Wallet\Services\TransferService;
use AppModules\User\Database\Repositories\Eloquent\UserRepository;

class TransactionController extends Controller
{
    public function __construct(
        protected TransferService $transferService,
        protected UserRepository $userRepository
    ) {}

    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            $this->transferService->execute(
                $this->userRepository->findById($validated['sender_id']),
                $this->userRepository->findById($validated['receiver_id']),
                $validated['amount']
            );
            return response()->json(['message' => 'Transferência realizada com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
