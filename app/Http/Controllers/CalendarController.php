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
     * @param  \Illuminate\Http\Request  $request_arr
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request_arr = $request->toArray();
        // var_export($request_arr);
        // dd($request_arr);
        //BookOnloadsテーブルにpost
    //    $BookOnloan = new BookOnloan;
       foreach($request_arr as $request_ch){
        //    dd($request_arr,$request_ch);
        $BookOnloan = new BookOnloan;
            $BookOnloan->booktypeId = $request_ch['booktypeId'];
            $BookOnloan->studentNo = $request_ch['studentNo'];
            $BookOnloan->start = $request_ch['start'];
            $BookOnloan->end = $request_ch['end'];
            $BookOnloan->save();
       }
    }

    public function NumberPerDay($booktypeId)
    {
        $number = BookOnloan::where('booktypeId',$booktypeId)->get(['start','end'])->toArray();
        //[3冊借りられてる日を配列にいれる]
        return $number;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($booktypeId)
    public function show($booktypeId, $studentNo)
    {
        $books = BookOnloan::where('booktypeId',$booktypeId)->get()->toArray();

        //bookIdを管理しない仕様にしたので不要になった下
        // $bookId_arr = Book::where('booktypeId',$booktypeId)->pluck('id')->toArray(); //id だけの配列ができる              
        // $books = BookOnloan::whereIn('bookId',$bookId_arr)->get()->toArray(); //BookOnloanテーブル、bookIdカラム内から上のid（配列）に一致するものを取得
       
        if (!Empty($books)){ 
            foreach ($books as $book){
                $title = "貸出Id".$book['id']."/"."studentNo".$book['studentNo']; //."/"."bookId"."/".$book['bookId'];
                // dd($title);        
                unset($book['id']); //fullcalendarのeventsのidと認識されてしまうので消去
                $book["title"] = $title; 
                $book['end'] =  date('Y-m-d', strtotime($book['end']. '+1 day' )); //fullcalendarで1日ずれるので調整
                if($book['studentNo'] == $studentNo){ //自分の貸出履歴のみ編集可にするflagを付ける
                    $book['edit'] = "yes";
                }else{
                    $book['edit'] = "no";
                }; 
                
                $book_onloan[] = $book; //array_merge($book,["edit"=>"no"]);
            };
        }else{
            $book_onloan[]= ["title"=> "", "start"=> "", "end"=> ""];
        };

        //祝日を入れる
        $url = 'https://holidays-jp.github.io/api/v1/date.json';
        $json = mb_convert_encoding(file_get_contents($url), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'); //urlからjsonを取得してエンコード処理
        $holidaysData_arr = json_decode($json,true); //JSONを連想配列にする [日付=>祝日の名前, ..., ..]
        foreach($holidaysData_arr as $key => $value){
            $holiday =[
                "title"=> $value,
                "start"=>  $key,
                "classNames"=> "holiday",
                "holiday"=>  $key,
            ];
            // dd($holiday);
            $book_onloan[] = $holiday;
            // dd($book_onloan);
        }

        return $book_onloan;
        }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request_arr
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request_arr, $id)
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
