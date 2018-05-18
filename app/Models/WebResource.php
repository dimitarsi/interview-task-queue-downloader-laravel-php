<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebResource extends Model
{
    public $fillable = ["url", "status", "completedAt", "download_name", "file_name"];

    public $attributes = [
        "status" => "pending"
    ];

    public $timestamps = false;

    public function isComplete() {
        return $this->status == "complete";
    }
}
