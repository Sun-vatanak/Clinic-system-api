<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index() {
        return Purchase::with(['supplier', 'drug'])->get();
    }

    public function store(Request $request) {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'drug_id' => 'required|exists:drugs,id',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric',
        ]);

        return Purchase::create($request->all());
    }

    public function show(Purchase $purchase) {
        return $purchase->load(['supplier', 'drug']);
    }

    public function update(Request $request, Purchase $purchase) {
        $purchase->update($request->all());
        return $purchase;
    }

    public function destroy(Purchase $purchase) {
        $purchase->delete();
        return response()->noContent();
    }
}
