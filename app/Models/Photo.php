<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'session_id',
        'filter',
        'frame_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
