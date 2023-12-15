<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionSubmission extends Model
{
    use HasFactory;

    protected $fillable = ['promotion_task_id', 'user_id', 'duration', 'success'];

    protected $casts = [
        'success' => 'boolean'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mistakes()
    {
        return $this->hasMany(PromotionMistake::class);
    }
}
