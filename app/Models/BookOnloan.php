<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookOnloan extends Model
{
    protected $fillable = [
        'bookId',
        'checkoutDate',
        'returnDate',
    ];

    //BOOKテーブル（主）と連携する
    public function book()
    {
        return $this->belongsTo(Book::class,'bookId');
    }

    public function calendarEvent($bookId){
        $books = BookOnloan::where('bookId',$bookId)->get();
        return $books;
}

    
}
