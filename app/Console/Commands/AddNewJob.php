<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WebResource;
use Cerbero\CommandValidator\ValidatesInput;
use Illuminate\Support\Collection;

class AddNewJob extends Command
{
    use ValidatesInput;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:add
        {url : Resource URL}
        {fname : Resource Download Name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new Web Resource Entry';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function rules() {
        return [
            "url" => "URL"
        ];
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        $url = $this->argument("url");
        $download_name = $this->argument("fname");

        $res = WebResource::create([
            "url" => $url,
            "download_name" => $download_name
        ]);

        $this->table(["Status", "URL", "File Name"], Collection::make([$res])->map(function($model) {
            return [
                "status" => $model->status,
                "url" => $model->url,
                "file" => $model->download_name
            ];
        })->toArray());
    }
}
