<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'level_id',
        'number',
        'user_id'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'memberships')->withTimestamps();
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
