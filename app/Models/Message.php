<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'message',
        'uuid'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($message) {
            $message->uuid = Uuid::uuid4();
        });
    }
}
