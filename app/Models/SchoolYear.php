<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = ['start_year', 'end_year'];

    protected $appends = ['display_name'];

    public function getDisplayNameAttribute(): string
    {
        return 'SY ' . $this->start_year . '-' . $this->end_year;
    }


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

}