<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookOnloan extends Model
{
    protected $fillable = [
        'booktypeId',
        'start',
        'end',
    ];

    //BOOKテーブル（主）と連携する
    public function book()
    {
        return $this->belongsTo(Book::class,'booktypeId', 'booktypeId');
    }
}
