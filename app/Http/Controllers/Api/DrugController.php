<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function index() {
        return Drug::all();
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        return Drug::create($request->all());
    }

    public function show(Drug $drug) {
        return $drug;
    }

    public function update(Request $request, Drug $drug) {
        $drug->update($request->all());
        return $drug;
    }

    public function destroy(Drug $drug) {
        $drug->delete();
        return response()->noContent();
    }
}
