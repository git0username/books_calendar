<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookOnloan extends Model
{
    protected $fillable = [
        'bookId',
        'checkout',
        'return',
    ];
}
