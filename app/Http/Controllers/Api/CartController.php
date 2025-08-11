<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Services\RazorpayService;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;




class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = 1;
        $cartItem = Cart::where('user_id', $userId)->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            // Update Quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Create new Cart Item
            $cartItem = Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully',
            'data' => $cartItem
        ]);
    }


    public function listCartItems()
    {
        $userId = 1; // hardcoded
        try {
            $cartItems = Cart::with('product.images')->where('user_id', $userId)->get();

            $total = 0;
            foreach ($cartItems as $item) {
                $item->subtotal = $item->product->price * $item->quantity;
                $total += $item->subtotal;
            }

            return response()->json([
                'status' => true,
                'message' => 'Cart items fetched successfully',
                'items' => $cartItems,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching cart items',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateCartItem(Request $request, Cart $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',

        ]);

        // make sure cart item belongs to user 1 (Hardcoded)

        if ($cartItem->user_id !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'status' => true,
            'message' => 'Cart Item updated succefully',
            'data' => $cartItem,
        ]);
    }
 public function deleteCartItem(Cart $cartItem)
{
    try {
        // Check authorization
        if ($cartItem->user_id !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $cartItem->delete();

        return response()->json([
            'status' => true,
            'message' => 'Cart item deleted successfully',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to delete cart item',
            'error' => $e->getMessage(),
        ], 500);
    }
}



public function checkout(RazorpayService $razorpay)
{
    DB::beginTransaction();

    try {
        $userId = 1; // Replace with Auth::id() in real app
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty',
            ], 400);
        }

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Create Razorpay order through service
        $razorpayOrder = $razorpay->createOrder($totalAmount);

        // Save order in DB
        $order = Order::create([
            'user_id' => $userId,
            'razorpay_order_id' => $razorpayOrder['id'],
            'total' => $totalAmount,
            'status' => 'pending', // or 'pending'
        ]);

        // Save order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Optionally clear cart after order creation
        Cart::where('user_id', $userId)->delete();

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Order created successfully',
            'data' => [
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => $razorpayOrder['amount'],
                'currency' => $razorpayOrder['currency'],
                'key' => config('services.razorpay.key'),
                'order' => $order,
            ]
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Checkout failed',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function verifyPayment(Request $request)
{
    $request->validate([
        'razorpay_order_id' => 'required|string',
        'razorpay_payment_id' => 'required|string',
        'razorpay_signature' => 'required|string',
    ]);

    $userId = 1; // hardcoded user id for now

    $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

    $attributes = [
        'razorpay_order_id' => $request->razorpay_order_id,
        'razorpay_payment_id' => $request->razorpay_payment_id,
        'razorpay_signature' => $request->razorpay_signature,
    ];

    try {
        $api->utility->verifyPaymentSignature($attributes);

        // Payment verified — now update order in DB
        $order = Order::where('razorpay_order_id', $request->razorpay_order_id)
            ->where('user_id', $userId)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
            ], 404);
        }

        // Update order status
        $order->status = 'completed';
        $order->save();

        // Create order items from cart
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Clear cart
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
