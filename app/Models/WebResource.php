<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebResource extends Model
{
    public $fillable = ["url", "status", "completedAt"];

    public $attributes = [
        "status" => "pending"
    ];

    public $timestamps = false;
}
