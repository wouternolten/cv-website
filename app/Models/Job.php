<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
