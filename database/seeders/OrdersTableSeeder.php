<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->count() == 0 || $products->count() == 0) {
            $this->command->warn('⚠ Please seed users and products before running OrdersTableSeeder.');
            return;
        }

        foreach (range(1, 10) as $i) {
            $user = $users->random();
            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0,
                'status' => collect(['pending', 'processing', 'completed'])->random(),
            ]);

            $total = 0;
            $orderProducts = $products->random(rand(1, 3));

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 5);
                $price = $product->price;
                $total += $price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }

            $order->update(['total' => $total]);
        }

        $this->command->info('✅ Dummy orders with items created successfully.');
    }
}
