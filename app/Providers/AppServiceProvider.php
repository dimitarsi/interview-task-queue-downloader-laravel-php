<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\WebResource;
use App\Events\EnqueueDownloading;
use Ramsey\Uuid\Uuid;
use Storage;
use DB;

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

        WebResource::creating(function($model) {
            $model->file_name = Uuid::uuid4()->toString();
        });

        WebResource::created(function($model) {
            event(new EnqueueDownloading($model));
        });

        WebResource::deleting(function($model) {
            if(Storage::disk("downloads")->exists($model->file_name)) {
                Storage::disk("downloads")->delete($model->file_name);
            }
        });

        WebResource::updating(function($model) {
            if($model->isComplete()) {
                $model->completed_at = DB::raw("CURRENT_TIME");
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
