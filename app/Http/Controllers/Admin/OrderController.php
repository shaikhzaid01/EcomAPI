<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(){
        $this->middleware("auth");
    }
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $orders = Order::with('user')->latest()->paginate(12);
    return view('admin.orders.index', compact('orders'));
}


    /**
     * Show the form for creating a new resource.
     */

    public function show(Order $order)
    {
        $order->load(['user','items.product']);
             return view("admin.orders.show",compact('order'));
    }


    public function update(Request $request, Order $order)
    {
       $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);
        $order->update(['status' => $request->status]);
        return redirect()->route('admin.orders.index')->with('success','Order status updated..');
    }


}
