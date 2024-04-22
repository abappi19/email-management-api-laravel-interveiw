<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject',
        'body',
        'sender_email',
        'recipient_email',
        'format',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
