<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // use HasFactory;

    protected $fillable = [
        'title',
        'price'
    ];

    //bookonloansテーブル（従）と連携する
    public function bookonloan()
    {
        return $this->hasOne(BookOnloan::class,'bookId');
    }

    //p283
    
}
