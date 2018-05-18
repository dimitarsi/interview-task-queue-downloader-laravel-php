<?php

namespace App\Listeners;

use App\Events\EnqueueDownloading;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client;
use Log;

class EnqueueListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EnqueueDownloading  $event
     * @return void
     */
    public function handle(EnqueueDownloading $event)
    {
        Log::info("Start downloading {$event->webresource->url}");
        $client = new Client;
        $resource_path = storage_path("app/downloads/{$event->webresource->file_name}");
        Log::info("Start downloading {$event->webresource->url}; pipe to $resource_path");
        $event->webresource->status = "downloading";
        try {
            $client->request("GET", $event->webresource->url, ['sink' => $resource_path]);
            $event->webresource->status = "complete";
            Log::info("Downloaded {$event->webresource->url}");
        } catch(Exception $exp)
        {
            Log::info("Error downloading {$event->webresource->url}");
            Log::error($exp->getMessage());
            $event->webresource->status = "error";
        } finally {
            Log::info("Complete downloading {$event->webresource->url}");
            $event->webresource->save();
        }
    }
}
