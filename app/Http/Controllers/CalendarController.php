<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookOnloan;
use PHPUnit\Framework\MockObject\Rule\Parameters;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = BookOnloan::all();
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
        $request = $request->toArray();
        // var_export($request);
        // dd($request);
        //BookOnloadsテーブルにpost
    //    $BookOnloan = new BookOnloan;
       foreach($request as $request1){
        //    dd($request,$request1);
        $BookOnloan = new BookOnloan;
            $BookOnloan->bookId = $request1['bookId'];
            $BookOnloan->userId = $request1['userId'];
            $BookOnloan->start = $request1['start'];
            $BookOnloan->end = $request1['end'];
            $BookOnloan->save();
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
        $bookId_arr = Book::where('booktypeId',$id)->pluck('id')->toArray();        
        $books = BookOnloan::whereIn('bookId',$bookId_arr)->get()->toArray();
       
        if (!Empty($books)){ 
            foreach ($books as $book){
                $title = "貸出Id".$book['id']."/"."userId".$book['userId']."/"."bookId"."/".$book['bookId'];
                // dd($title);        
                unset($book['id']);
                $book["title"] = $title;               
                // dd($book);
                $book['end'] =  date('Y-m-d', strtotime($book['end']. '+1 day' ));           
                
                $book_onloan[] = array_merge($book,["edit"=>"no"]);
            };
        }else{
            $book_onloan[]= ["title"=> "", "start"=> "", "end"=> ""];
        };
       
        return $book_onloan;
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
