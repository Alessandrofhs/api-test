<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\UserPocket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pocket_id' => 'required|exists:user_pockets,id',
            'amount' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {

            // get pocket
            $pocket = UserPocket::where('id', $request->pocket_id)
                ->where('user_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();

            // Cek saldo cukup atau kagak
            if ($pocket->balance < $request->amount) {
                return response()->json([
                    'status' => 400,
                    'error' => true,
                    'message' => 'Saldo pocket tidak mencukupi.',
                    'data' => []
                ], 400);
            }

            // Simpan expense
            $expense = Expense::create([
                'user_id' => Auth::id(),
                'pocket_id' => $pocket->id,
                'amount' => $request->amount,
                'notes' => $request->notes,
            ]);

            // Kurangi saldo
            $pocket->balance -= $request->amount;
            $pocket->save();

            return response()->json([
                'status' => 200,
                'error' => false,
                'message' => 'Berhasil menambahkan expense.',
                'data' => [
                    'id' => $expense->id,
                    'pocket_id' => $pocket->id,
                    'current_balance' => $pocket->balance,
                ]
            ]);
        });
    }
}
