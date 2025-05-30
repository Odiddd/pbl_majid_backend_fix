<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\donasiModel;
use Illuminate\Http\Request;

class donasiController extends Controller
{
    public function index()
    {
        $donasi = donasiModel::all();
        return response()->json($donasi);
    }
}
