<?php

namespace Database\Factories;

use App\Models\Cashier;
use App\Models\Customer;
use App\Models\SellingInvoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SellingInvoice>
 */
class SellingInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $produk_id = SellingInvoice::orderBy('invoice_code', 'desc')->pluck('invoice_code')->first();
        $number = intval(str_replace("INV-", "", $produk_id)) + 1;

        $customers = User::where('role', 'user')->pluck('username')->all();
        $customer = fake()->randomElement($customers);

        return [
            'selling_invoice_id' => fake()->uuid,
            'invoice_code' => 'INV-'. str_pad($number, 6, '0', STR_PAD_LEFT),
            'cashier_name' => Cashier::first()->user->username,
            'customer_name' => $customer,
            'customer_phone' => '08'.strval(fake()->numberBetween(1000000000, 9999999999)),
            'customer_file'=> fake()->word().'.jpg',
            'customer_request' => fake()->words(10, true),
            'customer_bank' => fake()->word(),
            'customer_payment'=> fake()->word().'.jpg',
            'order_date' => fake()->dateTime(),
            'order_complete' => fake()->dateTime(),
            'refund_file' => fake()->word().'.jpg',
            'reject_comment' => fake()->words(15, true),
            'order_status' => fake()->randomElement(['Berhasil', 'Gagal', 'Menunggu Pengembalian', 'Menunggu Konfirmasi', 'Menunggu Pengambilan', 'Offline', 'Refund'])
        ];
    }
}