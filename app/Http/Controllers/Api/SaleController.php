<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index() {
        return Sale::with(['user', 'drug'])->get();
    }

    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'drug_id' => 'required|exists:drugs,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric',
        ]);

        return Sale::create($request->all());
    }

    public function show(Sale $sale) {
        return $sale->load(['user', 'drug']);
    }

    public function update(Request $request, Sale $sale) {
        $sale->update($request->all());
        return $sale;
    }

    public function destroy(Sale $sale) {
        $sale->delete();
        return response()->noContent();
    }
}
