<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookOnloan;
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
        echo $books;
        // eloquantをそのままreturnすると、jsonに変換してくれる。
        return $books;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $bookId = Book::where('title',$request->book_title)->get()->pluck('id');
        // dd($bookId[0]);

        $bookId = Book::where('title',$request->book_title)->get();
        // dd($bookId[0]['id']);


        
        if($bookId[0]['id'] === null){
            // return view('/check');
        }else{
        // $this->validate($request, BookOnloan::$rules); need?

        $BookOnloan = new BookOnloan;

        // $BookOnloan->bookId = $request->bookId;

        $BookOnloan->bookId = $bookId[0]['id'];
        $BookOnloan->checkoutDate = $request->checkoutDate;
        $BookOnloan->returnDate = $request->returnDate;
        $BookOnloan->save();
        // return redirect('/check');
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
