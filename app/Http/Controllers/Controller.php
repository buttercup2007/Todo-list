<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function save(Request $request)
    {
        // Voor nu loggen we alles
        \Log::info($request->all());

        // Geef een JSON terug
        return response()->json([
            'status' => 'success',
            'data' => $request->all()
        ]);
    }
}