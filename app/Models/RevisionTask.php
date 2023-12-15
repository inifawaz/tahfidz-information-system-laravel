<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionTask extends Model
{
    use HasFactory;

    protected $fillable = ['revision_part_id', 'type', 'due_date', 'completed'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function quarters()
    {
        return $this->belongsToMany(Quarter::class);
    }

    public function submissions()
    {
        return $this->hasMany(RevisionSubmission::class);
    }
}
