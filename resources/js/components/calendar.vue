<template>
<div>
<Header />
<div id="sosen"><div id="oya"><div class="ko"></div></div></div>
<div style="font-size: 30px">【{{data.title}}】 の貸出表</div>
<div>※{{data.title}}の蔵書数は、{{data.number}}冊です。</div><br>
  <FullCalendar :options="calendar.calendarOptions" />
  <button class="btn- btn-info text-white mt-2 flag" v-on:click="doAction()">確定</button>
  <p>カレンダー上でドラッグすると日付を選べます。</p> 
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
      onloanDate_arr: [],      
      fullBooked_arr:[], //全数借りられている日
      bookedday_own_arr:[], //自分が借りてる日
      new_reserve_arr: [],     
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
        events:{
          url:  'http://127.0.0.1:8000/api/calendar/'+ data.booktypeId + '/' + data.studentNo,
          // color: 'yellow',   // an option!
          // textColor: 'black', // an option!
          // allDay: true,
          // allDayDefault:true,
                    
         },
         
        // eventSources:['https://holidays-jp.github.io/api/v1/datetime.json'],        
        
        // 日付をクリック、または範囲を選択したイベントの挙動を定義---------------------------------------------
        selectable: true,
        select: function(info) {                
          dayjs.extend(isBetween);
          const today =  dayjs().format('YYYY-MM-DD');
          let alert_mess = "";

          //DBに渡せる形にする
          const endStr = dayjs(info.endStr).add(-1, 'd').format("YYYY-MM-DD"); //fullcalendarのendは1日ずれるので調整
          const startStr = dayjs(info.start).format("YYYY-MM-DD")

          //今日以前は選択できない。管理者しかできない仕様。途中

          if(info.startStr < today){ //選択した日が今日以前なら
            alert("今日以前は選択できません。\n必要な場合は管理者情報を入力してください");
            return;

            //↓管理者しかできない仕様。途中
            // const admin_name = window.prompt("admin name を入力","");
            // if(!admin_name == null){
            //   const admin_passwd = window.prompt("admin password を入力","");
            //   const adminInfo = {name: admin_name, passwd: admin_passwd};
            //   console.log(adminInfo);
            // } 
          }      
          
          //選択した日にフルレンタルされてる日があれば、(ダブルブッキング防止)
          //または、すでに借りていたらalertでお知らせする。（二重貸出を防止）                   
           let flag ="";
           let count = 0; //alert message 切り替え用
          const check = [data.fullBooked_arr, data.bookedday_own_arr]; 

          check.some((arr)=>{ //some()はforEach()をreturnしたいときに代わりとして使える                                
            arr.some((date)=>{                            
               if(dayjs(date).isBetween(startStr, endStr, null, '[]')){
                 alert_mess += date + "\n";                        
              }
            })
            if(alert_mess){
              if(count==0){
                alert( `${alert_mess} は空きがありません。`);                              
              }else if(count==1){
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
              start: info.start,
              end: info.end,
              booktypeId: data.booktypeId, 
              edit:"yes",                
            });           
            
            //DBに渡す用arrにpush            
            data.onloanDate_arr.push({
              booktypeId: data.booktypeId,
              studentNo: data.studentNo,              
              start: startStr,
              end: endStr,              
              edit:"yes",              
            });            
               
           //自分が借りてる日の配列(bookedday_own_arr)にもpushする。
           let new_reserve =[]; 
            for(let date = dayjs(startStr); date<=dayjs(endStr); date=date.add(1, 'day')){
              let date_str = date.format("YYYY-MM-DD");
               data.bookedday_own_arr.push(date_str);
               new_reserve.push(date_str);           
               
               }
            data.new_reserve_arr.push(new_reserve);   //data.new_reserve_arrには中身がpushされているが、 data.new_reserve_arr には入っていない                                        
          }  
        },
      // 日付をクリック、または範囲を選択したイベントの挙動を定義ここまで-----------------------------------------------------  
         

       //入力した貸出日を削除する
        eventClick: function(item, jsEvent, view) {
          if(item.event.extendedProps.edit == "yes"){
          const date_start = dayjs(item.event.start).format('YYYY-MM-DD');          
            if(confirm(item.event.title + "\n" + date_start + "～" + "を消しますか？" )){              
              item.event.remove(); //fullcalendarから削除
              //new_reserve_arrとbookedday_own_arrから削除すること              
              data.new_reserve_arr.forEach((date)=>{                
                let count = 0;                
                if(date[0] == date_start){                  
                  data.new_reserve_arr.splice(count,1);
                  console.log(data.new_reserve_arr); //data.new_reserve_arrが空配列になっている？ なぜ？
                }
              })
            }            
          }else{
            //DBからきたeventは編集不可
            alert("このイベントは編集不可です。")
          }
        }, 
      },
    });
  //calendar情報ここまで▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲     

    //貸出日をpostする    
    const doAction = () => {       
      if(!data.onloanDate_arr.length){ //onloanDate_arrが空だった場合
        alert("貸出日を指定してください。")
      }else{      
        const url = "http://127.0.0.1:8000/api/calendar/"; //このページがAPI入出力の窓口として機能している 
        axios.post(url,data.onloanDate_arr)
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
     };

     //フルレンタルされてる日と自分が借りてる日の配列をそれぞれapiで取得
     const getfullBooked_own_date = async () => {
      const result = await axios.post("http://127.0.0.1:8000/api/calendar/"+ data.booktypeId ,
      { studentNo: data.studentNo, number: data.number}); 
      data.fullBooked_arr = result.data.fullBooked.sort();
      data.bookedday_own_arr =  result.data.bookedday_own.sort();
    }; //catch処理必要
     
     onMounted(() => {
       getfullBooked_own_date();
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
    
    return{ data, calendar, onMounted, watch, doAction };
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

