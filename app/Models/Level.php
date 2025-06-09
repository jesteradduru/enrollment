<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['level', 'code', 'grade', 'type'];

    public function classes()
    {
        return $this->hasMany(Classroom::class);
    }

    public function getDisplayNameAttribute()
    {
        return $this->name;
    }
}
