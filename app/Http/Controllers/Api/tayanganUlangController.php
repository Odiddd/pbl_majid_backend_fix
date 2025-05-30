<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\tayanganUlangModel;
use Illuminate\Http\Request;

class tayanganUlangController extends Controller
{
    public function index()
    {
        $tayanganUlang = tayanganUlangModel::all();
        return response()->json($tayanganUlang);
    }
}
