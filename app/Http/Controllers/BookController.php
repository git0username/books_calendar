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
        
        //booksテーブルの中身を変数に定義(コレクションのインスタンスとして取得される)
        $books = Book::all();

        $minReturnday = Book::minReturnday();
        $books_onloans = [];  //結合した内容を格納するための空配列を用意
        foreach ($books as $book){
            //dd($book);

            //一つの配列としてmerge(booksテーブルの中身(eloquantコレクション)をtoArrayで配列化、bookonloansテーブルも同様に配列化) コレクションの形のままarray_mergeは使えないので一旦toArrayで配列化 注意:同じ項目は上書きされる。(created_atは上書きされる)
           $book_onloan = array_merge($book->toArray(),$book->bookonloan->toArray());
           //dd($book_onloan);
           //もし$returnDate_min_arrの中にbookIdが該当すれば、①貸出中の冊数を配列にkey(onloan_numbe)追加②貸出最短日を配列に追加(=keyにreturnDate_minがあれば全冊貸出中で最速の返却日がvalueに入る)
           $book_onloan_bookid = $book_onloan["bookId"];           
           if(array_key_exists($book_onloan_bookid,$minReturnday["returnDate_min_arr"])){
                $book_onloan["onloan_number"] = $minReturnday["onloan_number_arr"][$book_onloan_bookid]; //①
                $available_loan_date = date("Y-m-d",strtotime( " 1 day " . $minReturnday["returnDate_min_arr"][$book_onloan_bookid])); //②
                // dd($available_loan_date);
                $book_onloan["available_loan_date"] = $available_loan_date;
           }else{
                $book_onloan["available_loan_date"] = date("Y-m-d");
           }
            
           //pushで上の空配列に格納する→これで同じ本の情報が一つのObjectになって、配列の中にはいる。[{},{},{}]の形になってフロントエンドに渡せるのでフロントエンド側でデータを扱いやすくなる。        
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
        $books = Book::where('id',$id)->get();
        return $books;
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
