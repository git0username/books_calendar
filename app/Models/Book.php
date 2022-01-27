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
        //bookIdだけ取り出す(得られる配列[0=>1(bookId), 1=>1(bookId), 2=>2(bookId), ...])
        $bookid_arr= $fromtoday->pluck('bookId')->toArray();
        // dd($bookid_arr); 
        // dd(gettype($fromtoday));
        //bookId毎にカウント(得られる配列["bookid"=>"count数","bookid"=>"count数",...]) =何冊借りられているか分かる
        $onloan_number_arr = array_count_values($bookid_arr);
        // dd($onloan_number_arr);
        //カウントしたものと全冊数を比べる
         //$onloan_number_arrのkey(bookid)を取得(得られる配列[0=>1(bookId), 1=>2(bookId), ...])=貸出中の本のbookIdを配列化
        $onloan_bookid_arr = array_keys($onloan_number_arr);
        // dd($onloan_bookid_arr);
        
        // $book_number_arr = []; //内容を格納するための空配列を用意 よういしなくても動いてる、、、検証すること

        // $book_number_arr = [];
        $returnDate_min_arr = [];         

        //onloanテーブル(貸出中)の本について、booksテーブルからその本の全冊数を取得配列化([0=>全冊数, 1=>全冊数, ])
        foreach($onloan_bookid_arr as $onloan_bookid){
            $book_number = Book::where('id',$onloan_bookid)->value('number');
            $book_number_arr[] = $book_number; //$book_number_arr[$onloan_bookid]にしないのは、作成してきた配列すべてbookId順にしているから。
            // var_dump($book_number_arr);
        }                                                      

            //dd($onloan_bookid);

            //貸出中の冊数と全冊数を比較
                      
            $i = 0;
            // dd($i);
            foreach($onloan_number_arr as $onloan_number){ //貸出中の冊数を取得                
                $book_number_arr[$i]; //貸出中の本の全冊数
                $onloan_bookid_arr[$i]; //貸出中の本のbookId
                // var_dump($book_number_arr[$i]);
                // var_dump($onloan_number);
                // var_dump($onloan_bookid_arr[$i]);

                //もし全部借りられてたら、
                // if($onloan_number <= $book_number_arr[$i]){
                if($onloan_number >= $book_number_arr[$i]){
                    // var_dump($i);
                    // var_dump($book_number_arr[$i]);
                    // var_dump($onloan_number);
                    // var_dump($onloan_number >= $book_number_arr[$i]);

                    $fromtoday_nodup = array_values($fromtoday->unique('bookId')->toArray()); //重複しているbookIdを一つにして配列化 [0=>[...], 1=>[...], 2=>...]] (重複削除後は最短のreturnDateが残る)
                    // var_dump($fromtoday->toArray());
                    // var_dump($fromtoday_nodup);
                    $fromtoday_nodup_search_index = array_search($onloan_bookid_arr[$i], array_column($fromtoday_nodup, 'bookId')); //多次元配列内をbookIdで検索して、該当のindexを取り出す
                    // var_dump(array_column($fromtoday_nodup, 'bookId'));
                    // var_dump($onloan_bookid_arr[$i]);
                    // var_dump($fromtoday_nodup_search_index) ;                   
                    // var_dump($fromtoday_nodup[$fromtoday_nodup_search_index]['returnDate']);
                    //dd($onloan_bookid);
                    $returnDate_min_arr[$onloan_bookid_arr[$i]] = $fromtoday_nodup[$fromtoday_nodup_search_index]['returnDate'];
                    // var_dump($returnDate_min_arr);                                                      
                }else{                   
                      //何もしない                  
                }
                ++$i;
            }
            // var_dump($returnDate_min_arr);            
        
        return ["returnDate_min_arr" => $returnDate_min_arr , "onloan_number_arr" => $onloan_number_arr];    
    }
}
