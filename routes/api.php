<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\userController;
use App\Http\Controllers\Api\roleController;
use App\Http\Controllers\Api\informasiController;
use App\Http\Controllers\Api\kegiatanController;
use App\Http\Controllers\Api\transaksiController;
use App\Http\Controllers\Api\jenisTransaksiController;
use App\Http\Controllers\Api\reservasiController;
use App\Http\Controllers\Api\tempatReservasiController;


Route::get('/test', function () {
    return response()->json(['message' => 'CORS Test Successful dari laravel']);
});

// route untuk menampilkan data informasi di website
Route::get('/informasi', [informasiController::class, 'index']);
Route::get('/informasi/{id}', [informasiController::class, 'show']);

// route untuk menampilkan data kegiatan di website
Route::get('/kegiatan', [kegiatanController::class, 'index']);
Route::get('/kegiatan/{id}', [kegiatanController::class, 'show']);

// route untuk menampilkan data tempatreservasi di website
Route::get('/tempatReservasi', [tempatReservasiController::class, 'index']);
Route::get('/tempatReservasi/{id}', [tempatReservasiController::class, 'show']);

Route::get('/jenis_transaksi', [jenisTransaksiController::class, 'index']);
Route::get('/jenis_transaksi/{id}', [jenisTransaksiController::class, 'show']);

Route::post('/login', [AuthController::class, 'login']);
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->group(function () {
    // route logout
    Route::post('/logout', [AuthController::class, 'logout']);

    /////////// user controller
    Route::get('/user', [userController::class, 'index']);
    Route::post('/user', [userController::class, 'store']);
    Route::get('/user/{id}', [userController::class, 'show']);
    Route::put('/user/{id}', [userController::class, 'update']);
    Route::patch('/user/{id}', [userController::class, 'update']);
    Route::delete('/user/{id}', [userController::class, 'destroy']);

    ////////// Role Controller
    Route::get('/role', [roleController::class, 'index']);
    Route::post('/role', [roleController::class, 'store']);
    Route::get('/role/{id}', [roleController::class, 'show']);
    Route::put('/role/{id}', [roleController::class, 'update']);
    Route::patch('/role/{id}', [roleController::class, 'update']);
    Route::delete('/role/{id}', [roleController::class, 'destroy']);

    ////////// Informasi Controller
    Route::post('/informasi', [informasiController::class, 'store']); // untuk upload
    Route::put('/informasi/{id}', [informasiController::class, 'update']); // disesuaikan dengan refine
    Route::patch('/informasi/{id}', [informasiController::class, 'update']);
    Route::delete('/informasi/{id}', [informasiController::class, 'destroy']);

    ////////// Kegiatan Controller
    Route::post('/kegiatan', [kegiatanController::class, 'store']);
    Route::post('/kegiatan/{id}', [kegiatanController::class, 'update']); // PATCH via _method
    Route::put('/kegiatan/{id}', [kegiatanController::class, 'update']);
    Route::patch('/kegiatan/{id}', [kegiatanController::class, 'update']);
    Route::delete('/kegiatan/{id}', [kegiatanController::class, 'destroy']);

    ////////// Transaksi Controller
    Route::get('/transaksi', [transaksiController::class, 'index']);
    Route::get('/transaksi/summary', [transaksiController::class, 'summary']);
    Route::post('/transaksi', [transaksiController::class, 'store']);
    Route::get('/transaksi/{id}', [transaksiController::class, 'show']);
    // Route::post('/transaksi/{id}/validasi', [transaksiController::class, 'validasi']);
    // Route::put('/transaksi/{id}/validasi', [transaksiController::class, 'validasi']);
    Route::post('/transaksi/{id}', [transaksiController::class, 'update']); // PATCH via _method
    Route::put('/transaksi/{id}', [transaksiController::class, 'update']);
    Route::patch('/transaksi/{id}', [transaksiController::class, 'update']);
    Route::delete('/transaksi/{id}', [transaksiController::class, 'destroy']);

    ////////// Jenis Transaksi Controller
    
    Route::post('/jenisTransaksi', [jenisTransaksiController::class, 'store']);
    
    Route::post('/jenisTransaksi/{id}', [jenisTransaksiController::class, 'update']); // PATCH via _method
    Route::put('/jenisTransaksi/{id}', [jenisTransaksiController::class, 'update']);
    Route::patch('/jenisTransaksi/{id}', [jenisTransaksiController::class, 'update']);
    Route::delete('/jenisTransaksi/{id}', [jenisTransaksiController::class, 'destroy']);


    ////////// reservasi Controller
    Route::prefix('reservasi')->group(function () {
        Route::get('/', [reservasiController::class, 'index']);          
        Route::post('/', [reservasiController::class, 'store']);         
        Route::get('/{id}', [reservasiController::class, 'show']);     
        Route::put('/{id}', [reservasiController::class, 'update']);      
        Route::delete('/{id}', [reservasiController::class, 'destroy']);  
    });

    ////////// tempat reservasi Controller
    Route::prefix('tempatReservasi')->group(function () {   
        Route::post('/', [tempatReservasiController::class, 'store']);          
        Route::put('/{id}', [tempatReservasiController::class, 'update']);      
        Route::patch('/{id}', [tempatReservasiController::class, 'update']);      
        Route::post('/{id}', [tempatReservasiController::class, 'update']);      
        Route::delete('/{id}', [tempatReservasiController::class, 'destroy']);  
    });

});

