<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookOnloan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request_arr
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)//予約の変更、削除、登録をする
    {   
       //DBに登録
       if(!empty($request->add)){
            foreach($request->add as $request_ch){
            $BookOnloan = new BookOnloan;
                $BookOnloan->booktypeId = $request_ch['booktypeId'];
                $BookOnloan->studentNo = $request_ch['studentNo'];
                $BookOnloan->start = $request_ch['start'];
                $BookOnloan->end = $request_ch['end'];
                $BookOnloan->save();
            };
        };

       //DBから削除
       if(!empty($request->delete)){
        BookOnloan::destroy($request->delete);
       };
       
       //DBを更新
       if(!empty($request->edit)){
       foreach($request->edit as $value){
        //    dd($value);
            $BookOnloan = BookOnloan::find($value['貸出Id']);
            unset($value['貸出Id']);
            $BookOnloan->fill($value)->save();
         }       
       }; 
    }
    
    public function NumberPerDay(Request $request, $booktypeId)//全冊借りられている日、自分が予約した日の配列を返す
    {
        //本の種類(booktypeId)が一致するものの'start'と'end'カラムだけを取得
        $start_end_arr = BookOnloan::where('booktypeId',$booktypeId)->get(['id','studentNo','start','end'])->toArray(); //[0=>["start"=>"2022-02-22","end"=>"2022-02-24"],1=>["start"...],...] 
        $bookedday_own =[];
        
        if(empty($start_end_arr)){ //whereでヒットせず、配列が空になった場合
            return $start_end_arr;
        }else{
            foreach($start_end_arr as $start_end){ //配列の要素を一つずつ取得                
                if($start_end['studentNo']==$request->studentNo){//自分が借りてる日付の配列を作る 重複レンタルの防止
                    $bookedday_own[] = ['貸出Id' => $start_end['id'], 'start' => $start_end['start'], 'end' => $start_end['end']];
                }
                $start = $start_end['start'];
                $end = $start_end['end'];
                $periods = CarbonPeriod::create($start, $end); //第一引数と第二引数の期間を日単位に取得 (2022.02.22, 2022.02.23, 2022.02.24)                             
                foreach ($periods as $period) {  // $periodを日付だけの配列にする [2022.02.22, 2022.02.23, 2022.02.24]
                    $date = $period->format('Y-m-d');                            
                    $date_arr[] = $date;
                };                         
            };                              
        }; 
        $countDate_arr = array_count_values($date_arr); //各日が何個あるかcountして配列を取得 ["日付"=>"count数","日付"=>"count数",]    
        $number = $request->number; //clientから来たpram(本の全数)を格納
        $fullBooked = array_keys($countDate_arr, $number); //count数が全数と同じ日付を取得し配列にする なかったら空が返る       
            
        return ['fullBooked' => $fullBooked, 'bookedday_own' => $bookedday_own]; //全数借りられている日の配列を返す
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

        /*
        bookIdを管理しない仕様にしたので不要になった↓
        $bookId_arr = Book::where('booktypeId',$booktypeId)->pluck('id')->toArray(); //id だけの配列ができる              
        $books = BookOnloan::whereIn('bookId',$bookId_arr)->get()->toArray(); //BookOnloanテーブル、bookIdカラム内から上のid（配列）に一致するものを取得
        */
       
        if (!Empty($books)){ 
            foreach ($books as $book){
                $book['貸出Id'] = $book['id'];                
                $title = "貸出Id".$book['id']."/"."studentNo".$book['studentNo'];
                unset($book['id']); //fullcalendarのeventsのidと認識されてしまうので消去
                $book["title"] = $title;                 
                $end = new Carbon($book['end']); //Carbonインスタンス生成
                $book['end'] = $end->addDays(1)->format('Y-m-d'); //fullcalendarで1日ずれるので調整
                
                //今日以降の自分の貸出予約を編集可にするflagを付ける
                if($studentNo == 100){//管理者だったら全て編集可にする 
                    $book['edit'] = "yes";
                    $book['editable'] = true;
                }elseif($book['studentNo'] == $studentNo && $book['start'] >= new Carbon('today')){
                    $book['edit'] = "yes";
                    $book['editable'] = true;
                }else{
                    $book['edit'] = "no";
                    $book['editable'] = false;
                }; 
                
                $book_onloan[] = $book;
            };
        }else{
            $book_onloan[]= ["title"=> "", "start"=> "", "end"=> ""];
        };

        /*
        祝日を入れる カレンダーが見にくくなるので要検討-----------------------------
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
            $book_onloan[] = $holiday;            
        }
        */
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
