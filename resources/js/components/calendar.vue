<template>
<div>
<div>{{this.$route.params.id}}</div>
  <FullCalendar :options="data.calendarOptions" />
  <button class="btn- btn-info text-white mt-2 flag" v-on:click="doAction()">確定</button> 
</div>
</template>

<script>
import { reactive, onMounted, watch, ref } from "vue";
import "@fullcalendar/core/vdom"; // solves problem with Vite
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import { useRouter, useRoute } from 'vue-router';
import axios from "axios";
import dayjs from 'dayjs';
import moment from 'moment';


export default {
  name: "calendar",
  components: {
    FullCalendar // make the <FullCalendar> tag available
  },
  
  setup(){   
    const route = useRoute();
    const data = reactive({    
      booktypeId:route.params.booktypeId,
      userId:2,
      number:route.params.number,
      onloanDate_arr:[],
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        dateClick: handleDateClick,
        weekends: true,
        editable: false,
        // allDay: false,
        events: 'http://127.0.0.1:8000/api/calendar/'+ route.params.booktypeId,
        
        // 日付をクリック、または範囲を選択したイベント
        selectable: true,
        select: function(info) {         
          if(confirm("指定した日で貸出しますか？")){
            //ここにすでに全数借りられてたらアラートだす処理-------------------------
            this.addEvent({ //this = Calendar
              title: "userId" + data.userId, //userIdが自動で入る
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
              userId: data.userId,              
              start: start_afterDate,
              end: end_afterDate,
              bookId: 1,
              edit:"yes",              
            });            
          }else{
              // alert("選択しなおしてください。");
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
    const router = useRouter();
    const doAction = () => {      
      console.log(data.onloanDate_arr);      
      const url = "http://127.0.0.1:8000/api/calendar/"; //このページがAPI入出力の窓口として機能している 
      axios.post(url,data.onloanDate_arr)
        .then(response => {
          console.log(response);
          if (confirm("続けて貸出し予約をしますか？")) {
            router.push("/");
          } else { //「キャンセル」ボタンをクリックした時
            alert("貸出予約が完了しました。過去に借りた本一覧のページに遷移します。")
            setTimeout(router.push("/bookingList"),2000);
          }
        })
        .catch(error => {
          console.log(error);
          alert("error");
        });
     }

    
    return{ data, handleDateClick, onMounted, watch, doAction };
  }
};
  
</script>