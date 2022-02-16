<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookOnloan;
use App\Models\Booklist;
use App\Models\Book;
use SebastianBergmann\Environment\Console;

class BookOnloanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = BookOnloan::all();  
        $books_onloans = [];  
        foreach ($books as $book){
           $book_onloan = array_merge($book->toArray(),$book->book->toArray());
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
        //①本のタイトルから本Idの値を取ってくる 
        $booktypeId = Book::where('title',$request->book_title)->value('booktypeId');
        // first()->toArray();
        // var_dump($booktypeId); 
        
        //②入力されたタイトルがあるかないか(idが空かどうか)
        if($booktypeId == null){
            //空だったら(idが検索できなかったら)returnする
            return response("no-title",404);
            return redirect('/books',303); //→redirectはphpなのでブラウザ側で分かってくれない。（＝遷移しない）
            
        }else{
            //③本の在庫があるか確認する
            



        // $this->validate($request, BookOnloan::$rules); need?

        //BookOnloadsテーブルにpost
        $BookOnloan = new BookOnloan;
        $BookOnloan->booktypeId = $booktypeId;
        $BookOnloan->userId = $request->userId;
        $BookOnloan->start = $request->checkoutDate;
        $BookOnloan->end = $request->returnDate;
        $BookOnloan->save();
        // var_dump($request->return);

        // //中間テーブルにpost
        // $Booklist = new Booklist;
        // $Booklist->userId = $request->userId;
        // $Booklist->bookId = $request->bookId;
        // $Booklist->onloanId = BookOnloan::latest()->value('id'); //book_onloansテーブルから今入れたやつのprimarykey(id)を持ってくる
        // $Booklist->save();
        
        return response("success"); //responseメソッドでresponse内容に"succsess"というメッセージを追記してくれる。 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bookonloan_arr = BookOnloan::where('studentNo', $id)->get();
        // dd($bookonloan_arr);
        $books = [];
        foreach($bookonloan_arr as $bookonloan){
            // dd($bookonloan->book);
            // var_export($bookonloan->toArray());
            // var_export($bookonloan->book->toArray());
            $book_arr = $bookonloan->toArray();

            // $book_onloan_book= $bookonloan->book->toArray();

            $book = array_merge($bookonloan->book->toArray(), $book_arr);
            // dd($book);
            $books[] = $book;
        };
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
