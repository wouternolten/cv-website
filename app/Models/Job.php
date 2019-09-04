<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
