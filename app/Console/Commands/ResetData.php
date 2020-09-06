<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
class ResetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:data';

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
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('migrate:fresh', []);
        Artisan::call('passport:install', []);
        Artisan::call('db:seed', []);
        $this->info('Done');
    }
}