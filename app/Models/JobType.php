<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{

    protected $fillable = ['name'];

    public function jobs()
    {
        $this->hasMany(Job::class);
    }
}
