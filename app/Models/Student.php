<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'date_of_birth',
        'gender',
        'phone_number',
        'email',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
