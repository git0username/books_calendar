<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookOnloan;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // //
        // //booksテーブルの中身を変数に定義(コレクションのインスタンスとして取得される)
        // $books = Book::all(); 
        // $fromtoday = Bookonloan::where('returnDate','>=',date("Y-m-d"))->orderBy('bookId','asc')->orderBy('returnDate','asc')->get(); 
        
        // $bookid_arr= $fromtoday->pluck('bookId')->toArray();
        // // dd(gettype($fromtoday));
        // //bookId毎にカウント(得られる配列["bookid"=>"count数","bookid"=>"count数",...])
        // $onloan_number_arr = array_count_values($bookid_arr);
        // //カウントしたものと全冊数を比べる
        //  //$onloan_number_arrのkey(bookid)を取得
        // $onloan_bookid_arr = array_keys($onloan_number_arr);
        
        // // $book_number_arr = []; //内容を格納するための空配列を用意

        // $returnDate_min_arr = [];        

        // //onloanテーブル(貸出中)の本について、booksテーブルからその本の冊数を取得
        // foreach($onloan_bookid_arr as $onloan_bookid){
        //     $book_number = Book::where('id',$onloan_bookid)->value('number');
        //     //貸出中の冊数と全冊数を比較
        //     foreach($onloan_number_arr as $onloan_number){ //貸出中の冊数を取得
        //         //もし全部借りられてたら、
        //         if($book_number <= $onloan_number){
        //             $fromtoday_nodup = $fromtoday->unique('bookId'); //filterして取り出す
        //             $fromtoday_nodup_search_arr = array_search($onloan_bookid, array_column($fromtoday_nodup, 'bookId')); //多次元配列内をbookIdで検索して、該当の内側配列を取り出す
        //             $returnDate_min = ["returnDate_min" => $fromtoday_nodup_search_arr['returnDate']];
        //             $returnDate_min_arr[$onloan_bookid] = $returnDate_min;                                      
        //         }else{                   
        //               //何もしない                  
        //         }
        //     }            
        // }
        // //

        $books_onloans = [];  //結合した内容を格納するための空配列を用意
        foreach ($books as $book){
            //一つの配列としてmerge(booksテーブルの中身(eloquantコレクション)をtoArrayで配列化、bookonloansテーブルも同様に配列化) コレクションの形のままarray_mergeは使えないので一旦toArrayで配列化 注意:同じ項目は上書きされる。(created_atは上書きされる)
           $book_onloan = array_merge($book->toArray(),$book->bookonloan->toArray(),$book->$returnDate_min);
        //    $book_onloan1 = array_push($book_onloan,$book->minReturnday());
           
            //←ここに貸出最短日を追加
           //pushで上の空配列に格納する→これで同じ本の情報が一つのObjectになって、配列の中にはいる。[{},{},{}]の形になってフロントエンドに渡せるのでフロントエンド側でデータを扱いやすくなる。
           dd($book_onloan);
           array_push($books_onloans,$book_onloan);
        }        
        // eloquantをそのままreturnすると、jsonに変換してくれる。
        return $books_onloans;

        // $book->minReturnday->toArray()        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
