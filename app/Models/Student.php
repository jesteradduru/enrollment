<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'gender',
        'date_of_birth',
        'address',
        'type',
        'last_school_attended',
        'last_school_address',
        'created_by'
    ];

     protected $appends = ['full_name', 'age'];

    public function getFullNameAttribute(): string
    {
        $middleInitial = $this->middle_name ? strtoupper($this->middle_name[0]) . '.' : '';
        $extension = $this->extension_name ? ' ' . $this->extension_name : '';
        return ucfirst("{$this->first_name} {$middleInitial} {$this->last_name}{$extension}");
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Classroom::class, 'enrollments');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
