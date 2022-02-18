<template>
<div>
<Header /> 
<div style="font-size: 30px">【{{data.title}}】 の貸出表</div>
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
import moment from 'moment';
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
    console.log("store_calendar");
    console.log(store_calendar.booktypeId);

    const data = reactive({    
      booktypeId: store_calendar.booktypeId,
      title: store_calendar.title,
      studentNo: store.state.studentInfo.studentInfo.studentNo,
      number: store_calendar.number,
      onloanDate_arr: [],
      today: dayjs().format('YYYY-MM-DD'),       
    });

    const calendar = reactive({      
      //calendar情報
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        dateClick: handleDateClick,
        weekends: true,
        editable: false,
        // allDay: false,
        events: 'http://127.0.0.1:8000/api/calendar/'+ data.booktypeId,
        
        // 日付をクリック、または範囲を選択したイベント
        selectable: true,
        select: function(info) {
          console.log("this");
          console.log(this.getEvents());
          console.log(info);
          console.log(info.endStr);

          //今日以前は選択できない。管理者しかできない仕様。途中
          if(info.startStr <= data.today){
            alert("今日以前は選択できません。\n必要な場合は管理者情報を入力してください");
            // const admin_name = window.prompt("admin name を入力","");
            // if(!admin_name == null){
            //   const admin_passwd = window.prompt("admin password を入力","");
            //   const adminInfo = {name: admin_name, passwd: admin_passwd};
            //   console.log(adminInfo);
            // } 
          }else{ 

            if(confirm("指定した日で貸出しますか？")){
              
              //ここにすでに全数借りられてたらアラートだす処理-------------------------
              this.addEvent({ //this = Calendar
                title: "studentNo" + data.studentNo, //studentNoが自動で入る
                start: info.start,
                end: info.end,
                bookId: 1, 
                edit:"yes",
                allDay: true
              });
              
              var start_beforeDate = moment(info.start, "YYYY-MM-DD"); // DBに渡せる形にする
              var start_afterDate = start_beforeDate.format('YYYY-MM-DD');
              var end_beforeDate = moment(info.end, "YYYY-MM-DD");             
              var end_afterDate = end_beforeDate.add(-1, "days").format('YYYY-MM-DD'); //endから1日引く

              //DBに渡す用arrにpush            
              data.onloanDate_arr.push({
                booktypeId: data.booktypeId,
                studentNo: data.studentNo,              
                start: start_afterDate,
                end: end_afterDate,
                bookId: 1,
                edit:"yes",              
              });            
            }else{
              // alert("選択しなおしてください。");
            }
          }
        },

         
        //入力した貸出日を削除する
        eventClick: function(item, jsEvent, view) {          
          if(item.event.extendedProps.edit == "yes"){
          const date_start = dayjs(item.event.start).format('YYYY-MM-DD');          
            if(confirm(item.event.title + "\n" + date_start + "～" + "を消しますか？" )){              
              item.event.remove(); //削除 
            }
          }else{
            //DBからきたeventは編集不可
            alert("このイベントは編集不可です。")
          }
        }, 
      },
    });
    
    const handleDateClick = (arg)=> {
      alert("date click! " + arg.dateStr);
    };

    //貸出日をpostする    
    const doAction = () => {      
      console.log(data.onloanDate_arr);
      if(!data.onloanDate_arr.length){
        alert("貸出日を指定してください。")
      }else{      
        const url = "http://127.0.0.1:8000/api/calendar/"; //このページがAPI入出力の窓口として機能している 
        axios.post(url,data.onloanDate_arr)
          .then(response => {
            console.log(response);
            if (confirm("予約が完了しました。\n OK：書籍一覧ページ　キャンセル：貸出履歴ページ")) {
              router.push("/");
            } else { //「キャンセル」ボタンをクリックした時              
              setTimeout(router.push("/bookingList"),2000);
            }
          })
          .catch(error => {
            console.log(error);
            alert("error");
          });
      }
     }

    
    return{ data, calendar, handleDateClick, onMounted, watch, doAction };
  }
};


</script>
  <!--
  必要な機能
  本の冊数に上限が必要
  期間を長く借りるならドラッグでは手間なので、formでカレンダー入力できるようにする
  今日以前は管理者のみ登録できる 
   /> -->

