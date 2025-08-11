<?php

namespace App\Services;

use Razorpay\Api\Api;

class RazorpayService
{
    protected $api;

    public function __construct()
    {
        $this->api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    /**
     * Create a Razorpay Order
     *
     * @param float $amount Amount in rupees
     * @param string $currency Currency code (default: INR)
     * @param string|null $receipt Optional receipt ID
     * @return \Razorpay\Api\Entity
     */
    public function createOrder($amount, $currency = 'INR', $receipt = null)
    {
        $orderData = [
            'amount' => $amount * 100, // Convert to paise
            'currency' => $currency,
            'receipt' => $receipt ?? uniqid('order_'),
            'payment_capture' => 1 // Auto-capture payment
        ];

        return $this->api->order->create($orderData);
    }
}
