<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

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

}
