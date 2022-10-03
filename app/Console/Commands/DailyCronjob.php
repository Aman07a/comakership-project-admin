<?php

namespace App\Console\Commands;

use App\Http\Controllers\CollectionsController;
use Carbon\Carbon;
use App\Models\Broker;
use App\Models\Property;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\XMLController;

class DailyCronjob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:cronjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Cronjob';

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
     *? Testing: Cronjob
     */
    public static function handle()
    {
        $broker = DB::table('brokers')->latest('id')->first(); // get last result from brokers
        // $broker = DB::table('brokers')->first(); // get first result from brokers
        $collectionsController = new CollectionsController();
        $collectionsController->index($broker->api_key);
    }
}
