<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
        'active',
        'group_capacity',
        'revision_task_type',
        'revision_quarter_portion',
        'connection_block_portion',
        'memorization_block_portion',
        'max_promotion_recitation_mistake',
        'max_promotion_question_mistake',
        'max_revision_recitation_mistake',
        'max_revision_question_mistake',
        'max_memorization_mistake'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected function revisionQuarterPortion(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => intval($value),
            set: fn (int $value) => strval($value),
        );
    }
}
