<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserPocketController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('auth/profile', [AuthController::class, 'profile']);
    Route::get('pockets', [UserPocketController::class, 'index']);
    Route::post('pockets', [UserPocketController::class, 'store']);
    Route::get('pockets/total-balance', [UserPocketController::class, 'totalBalance']);
    Route::post('incomes', [IncomeController::class, 'store']);
    Route::post('expenses', [ExpenseController::class, 'store']);
    Route::post('pockets/{id}/create-report', [ReportController::class, 'create']);
});