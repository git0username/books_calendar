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

    //laravel p283
    // public function minReturnday()
    // {
    //     $fromtoday = Bookonloan::where('returnDate','>=',date("Y-m-d"))->orderBy('bookId','asc')->orderBy('returnDate','asc')->get();
    //     // dd($fromtoday);
    //     $bookid_arr= $fromtoday->pluck('bookId')->toArray();
    //     // dd(gettype($fromtoday));
    //     //bookId毎にカウント(得られる配列["bookid"=>"count数","bookid"=>"count数",...])
    //     $onloan_number_arr = array_count_values($bookid_arr);
    //     //カウントしたものと全冊数を比べる
    //      //$onloan_number_arrのkey(bookid)を取得
    //     $onloan_bookid_arr = array_keys($onloan_number_arr);
        
    //     // $book_number_arr = []; //内容を格納するための空配列を用意

    //     //onloanテーブル(貸出中)の本について、booksテーブルからその本の冊数を取得
    //     foreach($onloan_bookid_arr as $onloan_bookid){
    //         $book_number = Book::where('id',$onloan_bookid)->value('number');
    //         //貸出中の冊数と全冊数を比較
    //         foreach($onloan_number_arr as $onloan_number){ //貸出中の冊数を取得
    //             //もし全部借りられてたら、
    //             if($book_number <= $onloan_number){
    //                 $returnDate_min = ["returnDate_min"=>$fromtoday->unique('bookId')];
    //                 return $returnDate_min;                    
    //             }else{
    //                 return "貸出可";
    //                 // dd($returnDate_min) ;
    //             }
    //         }
    //         // array_push($book_number_arr,$book_number);
    //     }

    // }
    
}
