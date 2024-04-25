<?php

namespace App\Console\Commands;

use App\Models\ProductModel;
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
    public function handle()
    {
        $products = ProductModel::inRandomOrder()->limit(10)->get();

        foreach ($products as $product) {
            $product->discount = 10;
            $product->save();
        }

        $this->info('Discounts updated successfully.');
    }
}
