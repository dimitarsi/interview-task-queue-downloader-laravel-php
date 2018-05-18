<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all Web Resource entries';

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
        $jobs = \App\Models\WebResource::all(["status", "url", "download_name"])->toArray();
        $this->table(["Status", "URL", "File Name"], $jobs);
    }
}
