<?php

namespace App\Http\Controllers;

use App\Enums\MoneyTransactionStatus;
use App\Enums\MoneyTransactionType;
use App\Http\Requests\StoreMoneyTransactionRequest;
use App\Models\MoneyTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class MoneyTransactionController extends Controller
{
    public function index() {
        $received_transactions = collect(auth()->user()->received_money_transactions()->get());

        $sent_transactions = collect(auth()->user()->sent_money_transactions()->get());

        return $received_transactions->merge($sent_transactions);
    }

    public function store(StoreMoneyTransactionRequest $request) {
        $validated = $request->validated();

        $transaction = new MoneyTransaction();
        $transaction->type = MoneyTransactionType::Common;
        $transaction->status = MoneyTransactionStatus::Transferred;
        $transaction->amount = $validated['amount'];
        if ($request->exists('description')) $transaction->description = $validated['description'];
        $transaction->receiver_id = $validated['receiver_id'];

        auth()->user()->sent_money_transactions()->save($transaction);

        $receiver = User::find($validated['receiver_id']);
        $receiver->bank_balance = $receiver->bank_balance + $validated['amount'];
        $receiver->save();

        $sender = User::find(auth()->user()->id);
        $sender->bank_balance = $sender->bank_balance - $validated['amount'];
        $sender->save();

        return $transaction;
    }
}
