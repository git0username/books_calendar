<template>
<div>
  <FullCalendar :options="data.calendarOptions" />

</div>
</template>

<script>
import { reactive, onMounted } from "vue";
import axios from "axios";

import "@fullcalendar/core/vdom"; // solves problem with Vite
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

export default {
  name: "calendar",
  components: {
    FullCalendar // make the <FullCalendar> tag available
  },
  setup(){
    const data = reactive({
      onloanDate_arr:[],
      // result:[],
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        dateClick: handleDateClick,
        weekends: true,
        events: [
          { title: "event 1", start: "2022-01-08", end: "2022-01-10" },
          { title: "event 2", date: "2022-01-30" },
          // { title: "event 3", date: data.result[0].checkoutDate },
        ],
        // 日付をクリック、または範囲を選択したイベント
        selectable: true,
        select: function(info) {
          //alert("selected " + info.startStr + " to " + info.endStr);

          // 入力ダイアログ
          const eventName = prompt("イベントを入力してください");
          // console.log(this.calendarOptions)
          if (eventName) {
            // イベントの追加
            this.addEvent({ //composition api でthis使えている？
              title: eventName,
              start: info.start,
              end: info.end,
              allDay: true
            });

            console.log("1")
            console.log(data.calendarOptions)

            // data.onloanDate_arr.push({
            //   title: eventName,
            //   start: info.start,
            //   end: info.end
            // });

            console.log("2")
            console.log(data.onloanDate_arr)
          }
        },      

      }
      
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
      console.log( data.onloanDate_arr); //data の変数はproxyになる どうやって イベントstart&endを配列にいれたらよいのか。

    };

    test();

    const url = "http://127.0.0.1:8000/api/books";
    const getAPI_books = async () => {
      const result = await axios.get(url); //{params:{bookId:1}}
      // data.result = result.data;       
      console.log("result.data=");
      console.log(result.data); 
    };

     onMounted(() => {
    getAPI_books();
    });
    


    return{ data, handleDateClick, test, onMounted };
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