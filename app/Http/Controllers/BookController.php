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

        //bookonloansテーブルの中身とbooksテーブルの中身を配列として繋げる
        $books_onloans = [];  //結合した内容を格納するための空配列を用意 
        foreach ($books as $book){
            //一つの配列としてmerge(booksテーブルの中身(eloquantコレクション)をtoArrayで配列化、bookonloansテーブルも同様に配列化) コレクションの形のままarray_mergeは使えないので一旦toArrayで配列化 注意:同じ項目は上書きされる。(created_atは上書きされる)
           $book_onloan = array_merge($book->toArray(),$book->bookonloan->toArray());
           //pushで上の空配列に格納する→これで同じ本の情報が一つのObjectになって、配列の中にはいる。[{},{},{}]の形になってフロントエンドに渡せるのでフロントエンド側でデータを扱いやすくなる。
           array_push($books_onloans,$book_onloan);
        }        
        // eloquantをそのままreturnすると、jsonに変換してくれる。
        return $books_onloans;
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
