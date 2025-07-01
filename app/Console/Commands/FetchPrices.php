<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PriceAggregator;

class FetchPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prices:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store prices';

    /**
     * Execute the console command.
     */
    public function handle(PriceAggregator $aggregator) {
        $aggregator->fetchAndStore();
        $this->info("Prices fetched and stored successfully.");
    }
}
