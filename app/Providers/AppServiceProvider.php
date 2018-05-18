<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\WebResource;
use App\Events\EnqueueDownloading;
use Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        WebResource::created(function($model) {
            event(new EnqueueDownloading($model));
        });

        WebResource::deleting(function($model) {
            if(Storage::disk("downloads")->exists($model->file_name)) {
                Storage::disk("downloads")->delete($model->file_name);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
