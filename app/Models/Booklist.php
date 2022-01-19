<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booklist extends Model
{
    protected $fillable = [
        'userId',
        'bookId',
        'onloanId',
    ];
    
}
