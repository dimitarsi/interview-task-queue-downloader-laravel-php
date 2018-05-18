<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\EnqueueDownloading;

class WebResource extends Model
{
    public $fillable = ["url", "status", "completedAt"];

    public $attributes = [
        "status" => "pending"
    ];

    public $timestamps = false;

    public $dispatchesEvents = [
        "created" =>  EnqueueDownloading::class
    ];
}
