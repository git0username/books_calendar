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
        $book_all = Book::all();       
        
        //booktypeId(得られる配列[0=>1(bookId), 1=>1(bookId), 2=>2(bookId), ...])
       $booktypeId_arr= $book_all->pluck('booktypeId')->toArray();
       //booktypeId毎にカウント(得られる配列["booktypeId"=>"count数","booktypeId"=>"count数",...]) =本ごとの合計冊数が分かる        
       $number_arr = array_count_values($booktypeId_arr);
       $books = $book_all->unique('booktypeId')->toArray();
       foreach($books as $book){           
           $books_arr[] = array_merge($book,["number"=> $number_arr[$book['booktypeId']]]);
       }
       return $books_arr;     
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
        // $books = Book::where('id',$id)->get();
        
        // return $books;
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
