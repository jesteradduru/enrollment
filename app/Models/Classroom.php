<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'level_id',
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'classroom_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments');
    }



    public function users()
    {
        return $this->belongsToMany(User::class, 'faculty_classroom', 'classroom_id', 'faculty_id');
    }

    public function faculty()
    {
        return $this->belongsToMany(User::class, 'faculty_classroom', 'classroom_id', 'faculty_id');
    }
}
