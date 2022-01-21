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
        $bookId = Book::where('title',$request->book_title)->value('id'); 
        
        //②入力されたタイトルがあるかないか(idが空かどうか)
        if(empty($bookId)){
            //空だったら(idが検索できなかったら)returnする
            return response("no-title",404);
            // return redirect('/books',303); →redirectはphpなのでブラウザ側で分かってくれない。（＝遷移しない）
            
        }else{
            //③本の在庫があるか確認する
            



        // $this->validate($request, BookOnloan::$rules); need?

        //BookOnloadsテーブルにpost
        $BookOnloan = new BookOnloan;
        $BookOnloan->bookId = $bookId;
        $BookOnloan->userId = $request->userId;
        $BookOnloan->checkoutDate = $request->checkoutDate;
        $BookOnloan->returnDate = $request->returnDate;
        $BookOnloan->save();

        //中間テーブルにpost
        $Booklist = new Booklist;
        $Booklist->userId = $request->userId;
        $Booklist->bookId = $bookId;
        $Booklist->onloanId = BookOnloan::latest()->value('id'); //book_onloansテーブルから今入れたやつのprimarykey(id)を持ってくる
        $Booklist->save();
        
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
