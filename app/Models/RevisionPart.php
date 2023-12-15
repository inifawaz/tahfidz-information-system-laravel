<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionPart extends Model
{
    use HasFactory;


    protected $fillable = ['part_id', 'student_id', 'period_id', 'level_id', 'completed'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function tasks()
    {
        return $this->hasMany(RevisionTask::class);
    }
}
