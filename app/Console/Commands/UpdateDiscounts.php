<?php

namespace App\Console\Commands;

use App\Models\ProductModel;
use App\Models\Products;
use Illuminate\Console\Command;


class UpdateDiscounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updateDiscounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): void
    {
        $products = Products::inRandomOrder()->limit(10)->get();

        foreach ($products as $product) {
            $discountAmount = $product->price * (10 / 100);

            $discountedPrice = $product->price - $discountAmount;

            $discountedPrice = round($discountedPrice, 2);

            $product->discount_price = $discountedPrice;

            $product->save();
        }

        $this->info('Discounts updated successfully.');
    }
}
