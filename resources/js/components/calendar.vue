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
import { useRouter } from 'vue-router'
import axios from "axios";
import dayjs from 'dayjs'


export default {
  name: "calendar",
  components: {
    FullCalendar // make the <FullCalendar> tag available
  },
  props: ['id'],

  setup(){
    const data = reactive({
      bookId:"",
      userId:2,
      onloanDate_arr:[],
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        dateClick: handleDateClick,
        weekends: true,
        editable: false,
        // allDay: false,


        events: 'http://127.0.0.1:8000/api/calendar/2',

        // events: [ //ここをapi取得に変更 addressの末尾に$router.paramsのbookidをつけたい edit keyもつける
        //   { title: "event 1", start: "2022-02-15", end: "2022-02-16", edit:"no" },
        //   { title: "event 1", start: "2022-02-20", end: "2022-02-22", edit:"no" },
        //   { title: "event 2", date: "2022-02-10", edit:"yes" }
        // ],
        // 日付をクリック、または範囲を選択したイベント
        selectable: true,
        select: function(info) {          
          //alert("selected " + info.startStr + " to " + info.endStr);
          if(confirm("指定した日で貸出しますか？")){

          this.addEvent({ //this = Calendar
              title: "userId" + data.userId, //userIdが自動で入る
              start: info.start,
              end: info.end,
              edit:"yes",
              allDay: true
            });            
            data.onloanDate_arr.push({
              title: data.userId, //userIdが自動で入る
              start: info.start,
              end: info.end,
              edit:"yes",
              allDay: true
            }); 
              console.log(this);

          }else{
            // alert("選択しなおしてください。");
          }
        },

          // 入力ダイアログ
          // const eventName = prompt("イベントを入力してください");
          // // console.log(this.calendarOptions)
          // if (eventName) {
          //   // イベントの追加            
          //   this.addEvent({ //this = Calendar
          //     title: eventName, //userIdが自動で入る
          //     start: info.start,
          //     end: info.end,
          //     allDay: true
          //   });

        //入力した貸出日を削除する
        eventClick: function(item, jsEvent, view) {
          console.log(item.event.extendedProps.edit);

          console.log(item.event.title);
          console.log(item.event.title == "event 2")
          // console.log(item.extendedProps.editable);
          if(item.event.extendedProps.edit == "yes"){
          const date_start = dayjs(item.event.start).format('YYYY-MM-DD');          
            if(confirm(item.event.title + "\n" + date_start + "～" + "を消しますか？" )){
              // item.event.fullCalendar("removeEvents",item.event.title);
              item.event.remove();          
              console.log(item);
              console.log(item.event.title);
            }
          }else{
            //DBからきたeventは編集不可
            alert("このイベントは編集不可です。")
          }
        }, 
        
          
  

            // console.log("1")
            // console.log(data.calendarOptions)

            

            // console.log("2")
            // console.log(data.onloanDate_arr)
          
              

      },

      
    });

    console.log("3")
    // data.onloanDate_arr.push("abc");
    console.log(data.onloanDate_arr); //proxyになる なぜ？
     

    const handleDateClick = (arg)=> {
      alert("date click! " + arg.dateStr);
    };

    const test= () =>{
      // const abc =[];
      data.onloanDate_arr.push("abcde");
      console.log( data.onloanDate_arr); //data の変数はproxyになる どうやって イベントstart&endを配列にいれたらよいのか。→watch

    };

    test();

    // const router = useRouter()
    console.log("q");
    // console.log(this);
    // console.log(this.$route.params.id);

    //貸出日をpostする
    const router = useRouter();
    const doAction = () => {
      const url = "http://127.0.0.1:8000/api/bookonloan"; //このページがAPI入出力の窓口として機能している
      axios
        .post(url, {
          bookId: "", //$route.paramの値
          checkoutDate: data.onloanDate_arr["start"],
          returnDate: data.onloanDate_arr["end"],
          userId: data.userId, 
        })
        .then(response => {
          console.log(response);
          if (confirm("続けて貸出し予約をしますか？")) {
            router.push("/");
            //「キャンセル」ボタンをクリックした時
          } else {
            alert("貸出予約が完了しました。過去に借りた本一覧のページに遷移します。")
            setTimeout(router.push("/bookingList"),2000);
          }
        })
        .catch(error => {
          console.log(error);
          alert("error");
        });
     }

    
    return{ data, handleDateClick, test, onMounted, watch, doAction };
  }
};


 

  // data() {
  //   return {
  //     calendarOptions: {
  //       plugins: [dayGridPlugin, interactionPlugin],
  //       initialView: "dayGridMonth",
  //       dateClick: this.handleDateClick,
  //       weekends: true,
  //       events: [
  //         { title: "event 1", start: "2022-01-20", end: "2022-01-22" },
  //         { title: "event 2", date: "2022-01-30" }
  //       ],
  //       // 日付をクリック、または範囲を選択したイベント
  //       selectable: true,
  //       select: function(info) {
  //         //alert("selected " + info.startStr + " to " + info.endStr);

  //         // 入力ダイアログ
  //         const eventName = prompt("イベントを入力してください");
  //         // console.log(this.calendarOptions)
  //         if (eventName) {
  //           // イベントの追加
  //           this.addEvent({
  //             title: eventName,
  //             start: info.start,
  //             end: info.end,
  //             allDay: true
  //           });
  //           console.log(this)
  //         //   this.events.push({
  //         //     title: eventName,
  //         //     start: info.start,
  //         //     end: info.end
  //         //   });
  //         }
  //       }
  //     }
  //   };
  // },
  // methods: {
  //   handleDateClick: function(arg) {
  //     alert("date click! " + arg.dateStr);
  //   }
  // }
// };
</script>