<?php

use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post("/leads", [LeadController::class, "store"]);
});
