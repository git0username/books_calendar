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

    // bookonloansテーブルから今日を含めた貸出中の本を抽出
    //その結果から最短のreturnDateを取り出して、連想配列に格納する。["(bookIdの数字がkey)" => ["returnDate_min" => "(最短の返却日が入る)"]]
    public static function minReturnday()
    {       
       
        $fromtoday = BookOnloan::where('returnDate','>=',date("Y-m-d"))->orderBy('bookId','asc')->orderBy('returnDate','asc')->get();
        //dd($fromtoday); 
        
        $bookid_arr= $fromtoday->pluck('bookId')->toArray();
        // dd(gettype($fromtoday));
        //bookId毎にカウント(得られる配列["bookid"=>"count数","bookid"=>"count数",...])
        $onloan_number_arr = array_count_values($bookid_arr);
        //カウントしたものと全冊数を比べる
         //$onloan_number_arrのkey(bookid)を取得
        $onloan_bookid_arr = array_keys($onloan_number_arr);
        
        // $book_number_arr = []; //内容を格納するための空配列を用意

        $returnDate_min_arr = [];        

        //onloanテーブル(貸出中)の本について、booksテーブルからその本の冊数を取得
        foreach($onloan_bookid_arr as $onloan_bookid){
            $book_number = Book::where('id',$onloan_bookid)->value('number');
            //dd($onloan_bookid);
            //貸出中の冊数と全冊数を比較
            ////ここでばらけさす
            foreach($onloan_number_arr as $onloan_number){ //貸出中の冊数を取得
                //もし全部借りられてたら、
                if($book_number <= $onloan_number){
                    $fromtoday_nodup = $fromtoday->unique('bookId')->toArray(); //重複を消して配列化 [0=>[...], 1=>[...], 2=>...]]
                    //dd($fromtoday_nodup);
                    $fromtoday_nodup_search_index = array_search($onloan_bookid, array_column($fromtoday_nodup, 'bookId')); //多次元配列内をbookIdで検索して、該当のindexを取り出す
                    //dd($fromtoday_nodup[$fromtoday_nodup_search_index]['returnDate']);
                    //dd($onloan_bookid);
                    $returnDate_min_arr[$onloan_bookid] = $fromtoday_nodup[$fromtoday_nodup_search_index]['returnDate'];
                    var_dump($returnDate_min_arr);                                                      
                }else{                   
                      //何もしない                  
                }
            }            
        }
        return $returnDate_min_arr;    
    }
}
