<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookOnloan;

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
        $books = BookOnloan::where('bookId',$request->bookId)->get();
        return $books;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $books = BookOnloan::where('booktypeId',$id)->get()->toArray();        
        foreach ($books as $book){
            $title = "貸出Id".$book['id']."/"."userId".$book['userId'];
            dd($title);
        
            unset($book['id']);
            $book['end'] =  date('Y-m-d', strtotime($book['end']. '+1 day' ));           
            
            $book_onloan[] = array_merge($book,["edit"=>"no"]);            
                
            };
           
        //  dd( $book_onloan);
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
