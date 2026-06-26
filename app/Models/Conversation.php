<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'generated_post_id',
        'title',
         'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generatedPost()
    {
        return $this->belongsTo(GeneratedPost::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}