<?php

// app/Http/Controllers/StockController.php
namespace App\Http\Controllers;

use App\Models\Stock;
use App\Events\StockUpdated;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        return response()->json(Stock::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0'
        ]);

        $stock = Stock::create($validated);
        broadcast(new StockUpdated($stock))->toOthers();
        return response()->json($stock, 201);
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0'
        ]);

        $stock->update($validated);
        broadcast(new StockUpdated($stock))->toOthers();
        return response()->json($stock);
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();
        broadcast(new StockUpdated($stock))->toOthers();
        return response()->json(null, 204);
    }
}