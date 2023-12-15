<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionTask extends Model
{
    use HasFactory;

    protected $fillable = ['promotion_part_id', 'type', 'due_date', 'completed'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function promotionPart()
    {
        return $this->belongsTo(PromotionPart::class);
    }

    public function submissions()
    {
        return $this->hasMany(PromotionSubmission::class);
    }
}
