<template>
<div>
<Header />
<div id="sosen"><div id="oya"><div class="ko"></div></div></div>
<div style="font-size: 30px">【{{data.title}}】 の貸出表</div>
<div>※{{data.title}}の蔵書数は、{{data.number}}冊です。</div><br>
<div class="fullcalendar">
  <FullCalendar :options="calendar.calendarOptions" />
<button class="btn- btn-info text-white mt-2 flag" v-on:click="doAction_確定()">確定</button>
<p>カレンダー上でドラッグすると日付を選べます。</p><br>
<!-- <p>変更したイベントオレンジ</p> 
<button class="btn- btn-info text-white mt-2 flag" v-on:click="doAction_リセット()">変更リセット</button> -->
 
</div>
</div>
</template>

<script>
import { reactive, onMounted, watch,} from "vue";
import "@fullcalendar/core/vdom"; // solves problem with Vite
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import { useRouter } from 'vue-router';
import axios from "axios";
import dayjs from 'dayjs';
import isBetween from 'dayjs/plugin/isBetween';
import { store } from "./store.js";
import Header from "./header.vue";



export default {
  name: "calendar",
  components: {
    FullCalendar, // make the <FullCalendar> tag available
    Header,
  },
  
  setup(){
    const router = useRouter();
    const store_calendar = store.state.calendar.calendar; 
    
    const data = reactive({    
      booktypeId: store_calendar.booktypeId,
      title: store_calendar.title,
      studentNo: store.state.studentInfo.studentInfo.studentNo,
      number: store_calendar.number, //本の全数
      new_reserve_arr: [], //DBに渡す用add_arr
      onloanDate_delete_arr: [], //DBに渡す用delete_arr
      onloanDate_edit_arr:[], //DBに渡す用edit_arr 
      fullBooked_arr:[], //全数借りられている日
      bookedday_own_arr:[], //自分が借りてる日      
    });
    
  //calendar情報--------------------------------------------------------------------------------------------  
    const calendar = reactive({      
      //calendar情報
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],         
        locale: 'ja',  
        initialView: "dayGridMonth",
        weekends: true,
        editable: false,
        navLinks: false,
        // contentHeight:'auto', //全量表示の設定         
        events:{
          url:  '/api/calendar/'+ data.booktypeId + '/' + data.studentNo,
          // color: 'yellow',   // an option!
          // textColor: 'black', // an option!
          // allDay: true,
          // allDayDefault:true,
         },
         
        eventSources:['https://holidays-jp.github.io/api/v1/datetime.json'],        
        
        // 日付をクリック、または範囲を選択したイベントの挙動▼▼▼▼▼▼▼▼▼▼
        selectable: true,
        select: function(select_item) {                       
          dayjs.extend(isBetween);
          const today =  dayjs().format('YYYY-MM-DD');          

          //DBに渡せる形にする
          const endStr = dayjs(select_item.endStr).add(-1, 'd').format("YYYY-MM-DD"); //fullcalendarのendは1日ずれるので調整
          const startStr = dayjs(select_item.start).format("YYYY-MM-DD")

          //今日以前は選択できない。
          if(select_item.startStr < today){ //選択した日が今日以前なら
            alert("今日以前は選択できません。\n必要な場合は管理者情報を入力してください");
            return;
          }      
          
          //選択した日にフルレンタルされてる日があれば、(ダブルブッキング防止)
          //または、すでに自分が借りていたらalertでお知らせする。（二重貸出を防止）                   
           let flag ="";
           let count = 0; //alert message 切り替え用
           let alert_mess ="";

           //新しく登録した予約のtart～end日の間の日を埋める。
           const new_reserve_arr_forCheck = [];
           data.new_reserve_arr.forEach((value)=>{
             for(let date = dayjs(value['start']); date<=dayjs(value['end']); date=date.add(1, 'day')){              
              let date_str = date.format("YYYY-MM-DD");              
              new_reserve_arr_forCheck.push(date_str);               
             }          
           });

           //過去に自分が予約済のstart～end日の間の日を埋める。
            const bookedday_own_arr_forCheck = [];            
            data.bookedday_own_arr.forEach((value)=>{
             for(let date = dayjs(value['start']); date<=dayjs(value['end']); date=date.add(1, 'day')){              
              let date_str = date.format("YYYY-MM-DD");              
              bookedday_own_arr_forCheck.push(date_str);               
             }          
           });
            
          const check = [data.fullBooked_arr, bookedday_own_arr_forCheck, new_reserve_arr_forCheck];          

          check.some((arr)=>{ //some()はforEach()をreturnしたいときに代わりとして使える                                
            arr.some((date)=>{                                          
               if(dayjs(date).isBetween(startStr, endStr, null, '[]')){
                 alert_mess += date + "\n";                        
              }
            })
            if(alert_mess){
              if(count==0){                
                alert( `${alert_mess} は空きがありません。`);                              
              }else if(count==1 || count==2){                
                alert( `${alert_mess} はすでに予約済みです。`);                
              }
              alert_mess = "";
              flag = "true";
            }
            count++;
            
            if(flag){ //check.someを抜ける          
              return true;
            }
          });

          if(flag){ //check.someでalertが出されたらselect処理を抜ける          
            return true;

          }

          //上のcheck処理をpassしたら
          //貸出の確認alert
          if(confirm("指定した日で貸出しますか？")){            
            this.addEvent({ //this = Calendar
              title: "studentNo" + data.studentNo,
              start: select_item.startStr,
              end: select_item.endStr,
              booktypeId: data.booktypeId, 
              edit:"yes",
              color:"red",
              editable:true,
              newEvent:"yes",
            });
               
           //自分が借りる日の配列を新しく作る( data.new_reserve_arr)
           data.new_reserve_arr.push({start: startStr, end: endStr});
          }          
        },
       // 日付をクリック、または範囲を選択したイベントの挙動▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲ 
         

       //イベントクリックした時の挙動▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
        eventClick: function(click_item) {
          //入力した貸出日を削除する
          const itemEve = click_item.event;
          const itemExt = click_item.event.extendedProps;         

          //別のstudentNoまたは今日以前endのeventは編集不可
          if(itemExt.edit == "yes"){
            const date_start = dayjs(itemEve.start).format('YYYY-MM-DD');
            if(itemEve.backgroundColor == "green"){//すでに削除処理済みの場合
              if(confirm("この削除済みの予約を元にもどしますか？")){
                    itemEve.setProp("color","");
              }
            }else{ //初めて削除処理する場合
              if(confirm(itemEve.title + "\n" + date_start + "～" + "を消しますか？" )){
                if(itemExt.newEvent == "yes"){//確定前のイベントの場合↓
                  //カレンダーから消す                
                  itemEve.remove(); 
                  //new_reserve_arrから削除               
                  let count = 0;
                  data.new_reserve_arr.forEach((date)=>{ 
                    if(date['start'] == date_start){                  
                      data.new_reserve_arr.splice(count,1);                  
                    }
                    count++;
                  })
                }else{//DBから読込んできたイベントの場合↓
                      //カレンダー上で緑色に変更する(removeでカレンダーから削除したら、ユーザーがどれ消したか見えなくなるから)
                      itemEve.setProp("color","green");
                      //bookedday_own_arrから削除
                      let count = 0;
                      data.bookedday_own_arr.forEach((value)=>{                
                        if(value['貸出Id'] == itemExt.貸出Id){
                          data.bookedday_own_arr.splice(count,1);
                        }
                        count++;
                      })
                      //DBに渡す用delete_arrにpush              
                      data.onloanDate_delete_arr.push(itemExt.貸出Id);              
                }
              }              
            }         
          }else{
            //別のstudentNoまたは今日以前endのeventは編集不可
            alert("このイベントは編集不可です。")
          }     
        },
        //イベントクリックした時の挙動▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲
        
        //イベントリサイズした時の挙動▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼▼
        eventResize: function(resize_item){                 
          //data.onloanDate_edit_arrに更新する内容をいれる。
          const 貸出Id = resize_item.event.extendedProps.貸出Id;
          //fullcalendarのendは1日ずれるので調整
          const endStr_resize = dayjs(resize_item.event.endStr).add(-1, 'd').format("YYYY-MM-DD");
          const oldendStr_resize = dayjs(resize_item.oldEvent.endStr).add(-1, 'd').format("YYYY-MM-DD");
          
          //新しく作成したイベント(赤色イベント)を移動した場合、data.new_reserve_arrのend日を入れ替える
          if("newEvent" in resize_item.event.extendedProps){
            let count = 0;
              data.new_reserve_arr.forEach((date)=>{
                if(date['end'] == oldendStr_resize){
                 data.new_reserve_arr[count]['end'] = endStr_resize;                  
                }
                count++;
              }) 
          }else{//DBから読込んできたイベントの場合、data.bookedday_own_arrに更新する内容をいれる。
          if(resize_item.event.extendedProps.edit == "yes"){//別のstudentNoまたは今日以前endのeventは編集不可
              let count = 0;
              let flag = true;
              //bookedday_own_arrに貸出Idが存在した場合は、end日を上書き(or 新規挿入)            
              data.bookedday_own_arr.forEach((date)=>{
                if(date['貸出Id'] == 貸出Id){
                data.bookedday_own_arr[count]['end'] = endStr_resize;
                flag = false;
                }
                count++;
              });
              if(flag){//bookedday_own_arrに貸出Idが存在しない場合は、新規で情報push ←これはありえんのでは イベントリサイズできるということはbookedday_own_arrにあるはず
                data.bookedday_own_arr.push({id: 貸出Id, end: endStr_resize });
              }
              //編集したイベントを黄色にする
              resize_item.event.setProp("color","#FFA500");
            }
          }
        },
        //イベントリサイズした時の挙動▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

        //イベントドロップした時の挙動▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲
        eventDrop: function(drop_item){
          console.log(drop_item.event.extendedProps);
          const startStr_drop = drop_item.event.startStr;
          //fullcalendarのendは1日ずれるので調整
          const endStr_drop = dayjs(drop_item.event.endStr).add(-1, 'd').format("YYYY-MM-DD");
                                         
          //新しく作成したイベント(赤色イベント)を移動した場合、data.new_reserve_arrのstart日とend日を入れ替える
          if("newEvent" in drop_item.event.extendedProps){
            let count = 0;
              data.new_reserve_arr.forEach((date)=>{
                if(date['start'] == drop_item.oldEvent.startStr){
                data.new_reserve_arr[count]['start'] = startStr_drop;
                data.new_reserve_arr[count]['end'] = endStr_drop;
                }
              count++;
              }) 
          }else{//DBから読込んできたイベントの場合、data.bookedday_own_arrに更新する内容をいれる。
            if(drop_item.event.extendedProps.edit == "yes"){//別のstudentNoまたは今日以前endのeventは編集不可        
              const 貸出Id = drop_item.event.extendedProps.貸出Id;
              //すでにdata.bookedday_own_arrに貸出Idが存在した場合は、start日とend日を上書き(or 新規挿入)
              let count = 0;
              let flag = true;
              data.bookedday_own_arr.forEach((date)=>{
                if(date['貸出Id'] == 貸出Id){
                data.bookedday_own_arr[count]['start'] = startStr_drop;
                data.bookedday_own_arr[count]['end'] = endStr_drop;
                flag =false;
                }
                count++;
              })
              //onloanDate_edit_arrに貸出Idが存在しない場合は、新規で情報push
              if(flag){
                data.bookedday_own_arr.push({id: 貸出Id, start: startStr_drop, end: endStr_drop }); 
              }
              //編集したイベントを黄色にする
              drop_item.event.setProp("color","#FFA500");
            }
          }
        },
        //イベントドロップした時の挙動▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲
      },
      //カレンダー情報ここまで▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲
    });

    
  //calendar情報ここまで▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲     

    //貸出日をpostする    
    const doAction_確定 = () => {     
       //new_reserve_arrを成形 
       if(data.new_reserve_arr){ //data.new_reserve_arr(新しい予約)の中身があったらif内の処理       
        // const value_arr =[];
        let count = 0;
        data.new_reserve_arr.forEach((value)=>
        {  
          data.new_reserve_arr[count]=
            {
              start: value['start'],
              end: value['end'],
              booktypeId: data.booktypeId,
              studentNo: data.studentNo
            };
          count++;        
        }); 
       }
       console.log(data.new_reserve_arr); //onloanDate_arr消せる？

       const param = {add: data.new_reserve_arr,
                      delete: data.onloanDate_delete_arr,
                      edit: data.bookedday_own_arr}
           
      if(!data.new_reserve_arr.length && !data.onloanDate_delete_arr.length && !data.bookedday_own_arr.length){ //各arrが空だった場合
        alert("貸出日を指定してください。")
      }else{//arrに中身がある場合
        if(confirm("赤色の日は予約、\nオレンジ色の日は変更、\n緑色の日は予約削除されます。")){      
          const url = "api/calendar/"; //このページがAPI入出力の窓口として機能している 
          axios.post(url, param)
            .then(response => {
              console.log(response);
              if (confirm("予約が完了しました。\n OK：書籍一覧ページ　キャンセル：貸出履歴ページ")) {
                router.push("/"); //「OK」押すと本一覧ページ(/index)に遷移
              } else { //「キャンセル」押すと貸出し履歴に遷移           
                router.push("/bookingList");
              }
            })
            .catch(error => {
              console.log(error);
              alert("error");
            });
        }
      }
     };

     //フルレンタルされてる日と自分が借りてる日の配列をそれぞれapiで取得
     const getfullBooked_own_date = async () => {
      const result = await axios.post("api/calendar/"+ data.booktypeId ,
      { studentNo: data.studentNo, number: data.number}); 
      data.fullBooked_arr = result.data.fullBooked.sort();
      data.bookedday_own_arr =  result.data.bookedday_own.sort();
    }; //catch処理必要
     
     onMounted(() => {
       getfullBooked_own_date();
     });

     //nextMonthならsessionstorageに編集中データを格納
    //  const nextMonth = document.getElementsByTagName("p").getAttribute(title);
     document.addEventListener("DOMContentLoaded", function(){
        var nextMonth = document.querySelector("[title='Next month']");
        if (!nextMonth){ return false;}
        nextMonth.addEventListener('click', function() {
              console.log("aaaa");
              //今のevent状況にする
              store.commit('setCalendarEdit_data', {delete: data.onloanDate_delete_arr, edit: data.bookedday_own_arr});
            });
      });

      //祝日の背景色を変えたかった 途中 完成したらonMountedに入れる----------------------------------------
      // const child =document.getElementsByClassName('ko')[0]; // 子要素を変数に代入
      // const sosen = child.parentElement; // 祖先要素を取得
      // const sosen2 = sosen.parentElement;
      // console.log("child");
      // console.log(sosen2); 
      // const child1 =document.getElementsByClassName('holiday');
      // const length = child1.length;
      // console.log("child");
      // console.log(child1);
      // console.log(child1[0]);//htmlcoleectionからなぜか値を取得できない
      // console.log(length);
      //--------------------------------------------------------------------------------------------------
    
    return{ data, calendar, onMounted, watch, doAction_確定 };
  }
  
};
 //getEvents()のデータの取り方参考
    // const a = this.getEvents();
    // console.log(a[0].extendedProps.edit);      
</script>

<!--<style src="../css/calendar.css" scoped>
/* @import "../css/calendar.css"; */
</style>-->

<style >
/* .fc-daygrid-day-frame {
  background-color: #eaf4ff;
} */
.fullcalendar{ /*カレンダーの高さ指定したいけどわからん*/
  max-width: auto;
  min-width: 750px;
  /* border: solid; */
  /* height: 700px; */
}
.fc-day-sun {  /* 日曜日 */
  background-color: rgb(243, 203, 203);
  color: red
  } 
.fc-day-sat {  /* 土曜日 */
  background-color: #c7d1fc;
  } 

/* 特定日背景色を変更する書き方 全ての祝日の色を付けたい 
td[data-date="2022-02-23"]{
    background-color:rgb(243, 203, 203);
}
*/

.fc-event-title {
	white-space: normal;
  word-wrap: break-word;
  text-align: left;
  /*変なとこで改行させたくないやつ
  line-break: strict;*/
}
a[aria-label="日曜日"]{
  color: red;
}
a[aria-label="月曜日"],[aria-label="火曜日"],[aria-label="水曜日"],[aria-label="木曜日"],[aria-label="金曜日"]{
  color: black;
}
.fc-sun {
	color: red;
	background-color: #fff0f0;
}
.fc-daygrid-day-top a{
	color: black;
}

.fc-day-sun .fc-daygrid-day-top a{
  color: red;
}
.fc-day-sat .fc-daygrid-day-top a{
  color: blue;
}

</style>


  <!--
  必要な機能  
  期間を長く借りるならドラッグでは手間なので、formでカレンダー入力できるようにする
  今日以前は管理者のみ登録できる
  消したイベントをDBに反映させる 
   /> -->

