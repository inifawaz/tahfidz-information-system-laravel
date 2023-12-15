<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionMistake extends Model
{
    use HasFactory;

    protected $fillable = [
        'revision_submission_id',
        'mistake_type_id',
        'verse_id',
        'from_index',
        'to_index'
    ];

    public function mistakeType()
    {
        return $this->belongsTo(MistakeType::class);
    }
}
