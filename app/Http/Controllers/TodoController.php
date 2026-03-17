<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TodoController extends Controller
{
    public function save(Request $request)
    {
        Storage::disk('local')->put('todos.json', json_encode($request->all()));
        return response()->json(['status' => 'ok']);
    }

    public function getTodos()
    {
        if (Storage::disk('local')->exists('todos.json')) {
            $data = json_decode(Storage::disk('local')->get('todos.json'), true);
            return response()->json($data);
        }

        return response()->json([
            'todos' => [],
            'maps' => []
        ]);
    }
}