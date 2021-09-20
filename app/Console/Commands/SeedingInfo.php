<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedingInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inform about seeded data into database';

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
     * @return void
     */
    public function handle()
    {
        //return 0;
        $this->info("Database is automatically seeded with data.
        There will be one admin user created with username: admin@email.com
        and password: password. Please use this account to login as an admin user
        into the application.");
    }
}
