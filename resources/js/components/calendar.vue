<template>
  <FullCalendar :options="data.calendarOptions" />
</template>

<script>
import { reactive } from "vue";
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
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        dateClick: handleDateClick,
        weekends: true,
        events: [
          { title: "event 1", start: "2022-01-20", end: "2022-01-22" },
          { title: "event 2", date: "2022-01-30" }
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
            this.addEvent({
              title: eventName,
              start: info.start,
              end: info.end,
              allDay: true
            });

            console.log("aa")
            console.log(data.calendarOptions.events)
            data.events.push({
              title: eventName,
              start: info.start,
              end: info.end
            });
          }
        }
      }
    });
     

    const handleDateClick = (arg)=> {
      alert("date click! " + arg.dateStr);
    };

    return{ data, handleDateClick };
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