<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(){
       $order = Order::with('items.product')->paginate(10);
        return response()->json([
            'status' => true,
            'orders' => $order,
        ]);
    }

    public function show(Order $order){
        $order->load('items.product');
        return response()->json([
             'status' => true,
            'order' => $order,
        ]);
    }
        public function updateStatus(Request $request, Order $order)
    {
       $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);
        $order->status = $request->status;
       $order->save();
       return response()->json([
         'status' => true,
         'message'=> 'Order status update succefully',
            'orders' => $order,
       ]);
    }

}
