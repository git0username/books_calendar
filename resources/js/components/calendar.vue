<template>
<div>
<div>{{this.$route.params.id}}</div>
  <FullCalendar :options="data.calendarOptions" />
</div>
</template>

<script>
import { reactive, onMounted } from "vue";
import "@fullcalendar/core/vdom"; // solves problem with Vite
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import { useRouter } from 'vue-router'
import axios from "axios";


export default {
  name: "calendar",
  components: {
    FullCalendar // make the <FullCalendar> tag available
  },
  props: ['id'],

  setup(){
    const data = reactive({
      id:'',
      onloanDate_arr:[],
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        dateClick: handleDateClick,
        weekends: true,
        events: [ //ここをapi取得に変更
          { title: "event 1", start: "2022-01-20", end: "2022-01-22" },
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
            this.addEventSource({ //composition api でthis使えている？
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

    // const router = useRouter()
    console.log("q");
    // console.log(this.$route.params.id);

    onMounted(() => {
    data.id = this.$route.params.id //飛んできたparamsの取り方が分からない
    });

    //apiからevevtsを取得 getAPI_books()をonMountedにいれる
    const url = "http://127.0.0.1:8000/api/calendar" ; //+ ここにindexから飛んでくるbookId を入れる$route.params.bookId
    const getAPI_books = async () => {
      const result = await axios.get(url);
      data.result = result.data;       
      console.log("result.data=");
      console.log(result.data); 
    }


    


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