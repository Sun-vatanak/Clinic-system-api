<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() {
        return Notification::with('user')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required',
            'message' => 'required',
        ]);

        return Notification::create($request->all());
    }

    public function show(Notification $notification) {
        return $notification->load('user');
    }

    public function update(Request $request, Notification $notification) {
        $notification->update($request->all());
        return $notification;
    }

    public function destroy(Notification $notification) {
        $notification->delete();
        return response()->noContent();
    }
}
