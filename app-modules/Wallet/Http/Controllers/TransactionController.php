<?php

namespace AppModules\Wallet\Http\Controllers;

use App\Http\Controllers\Controller;
use AppModules\Wallet\Services\TransferService;
use AppModules\Wallet\Http\Requests\TransferRequest;
use AppModules\Wallet\Exceptions\UnauthorizedTransferException;
use AppModules\Wallet\Exceptions\InsufficientFundsException;

class TransactionController extends Controller
{
    public function __construct(
        protected TransferService $transferService,
    ) {}

    public function transfer(TransferRequest $request)
    {
        try {
            $this->transferService->execute(
                $request->sender_id,
                $request->receiver_id,
                $request->amount
            );
            return response()->json(['message' => 'Transferência realizada com sucesso.'], 200);
        } catch (InsufficientFundsException $e) {
            return response()->json(['message' => 'Saldo insuficiente.'], 400);
        } catch (UnauthorizedTransferException $e) {
            return response()->json(['message' => 'Transferência não autorizada.'], 403);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Ocorreu um erro ao processar a transferência.'], 500);
        }
    }
}
