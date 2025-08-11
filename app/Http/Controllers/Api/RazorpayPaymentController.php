<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class RazorpayPaymentController extends Controller
{


    // Payment verify karne ke liye
  public function verifyPayment(Request $request)
{
    $request->validate([
        'razorpay_order_id' => 'required|string',
        'razorpay_payment_id' => 'required|string',
        'razorpay_signature' => 'required|string',
    ]);

    $userId = 1; // hardcoded user id for now

    $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

    $attributes = [
        'razorpay_order_id' => $request->razorpay_order_id,
        'razorpay_payment_id' => $request->razorpay_payment_id,
        'razorpay_signature' => $request->razorpay_signature,
    ];

    try {
        $api->utility->verifyPaymentSignature($attributes);

        $order = Order::where('razorpay_order_id', $request->razorpay_order_id)
            ->where('user_id', $userId)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
            ], 404);
        }

        $order->status = 'completed';
        $order->save();

        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        Cart::where('user_id', $userId)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Payment verified and order completed successfully',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Payment verification failed',
            'error' => $e->getMessage(),
        ], 400);
    }
}

}
