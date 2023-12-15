<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'registrations')->withTimestamps();
    }
}
