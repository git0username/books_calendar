<template>
  <div>
    <div style="display: flex">
      <div style="width:300px; margin:10px;">
        <p>{{data.title}}</p>

        <table class="table table-light table-striped" style="width:600px">
          <thead class="table-dark text-center">
            <tr>
              <th>BookID</th><th>Title</th><th>全冊数</th><th>貸出中</th><th>userId</th><th>貸出し可能日</th><th>貸出日</th><th>返却日</th>
            </tr>
          </thead>
          <tbody class="text-left" id="tbodyID">            
            <Index_ch
            v-for="(item, key) in data.result"
            v-bind:key="key"
            v-bind:item="item"
            />
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { reactive, onMounted, watch } from "vue";
import axios from "axios";
import dayjs from "dayjs";
import { useRouter } from "vue-router"; //リダイレクト用
import Index_ch from "./Index_ch.vue";


export default {
  name: "Index",
  components: { Index_ch },

  setup() {
    const data = reactive({
      result:"",
      title: "BOOK_LIST",
      // checkoutDate: "",
      // returnDate: "",
      // userId:2      
    });

    const url = "http://127.0.0.1:8000/api/books";
    const getAPI_books = async () => {
      const result = await axios.get(url);
      data.result = result.data;       
      console.log("result.data=");
      console.log(result.data); 
    }

    //
    // const returnDate = () => {
    //   // //return日に条件を付ける場合add()の第一引数(7日)を変更
    //   const returnDay_max = dayjs(data.checkoutDate).add(7, "d").format("YYYY-MM-DD");
    //   console.log(returnDay_max)
    //   // //return_min日はcheckout日の翌日から
    //   const returnDay_min = dayjs(data.checkoutDate).add(1, "d").format("YYYY-MM-DD");
    //   console.log(document.getElementById("date1"))
    //   document.getElementById("date1").setAttribute("max", returnDay_max);
    //   document.getElementById("date1").setAttribute("min", returnDay_min);
    //   console.log("日=" + returnDay_min);
    //   console.log(typeof returnDay);
    // };

    // watch(()=>data.checkoutDate, () => {
    //   returnDate()
    // });

    // const router = useRouter();

    // const doAction = (bookId) => {
    //   const url = "http://127.0.0.1:8000/api/bookonloan"; //このページがAPI入出力の窓口として機能している
    //   axios
    //     .post(url, {
    //       // book_title: data.book_title,
    //       bookId: bookId,
    //       checkoutDate: data.checkoutDate,
    //       returnDate: data.returnDate,
    //       userId: data.userId, 
    //     })
    //     .then(response => {
    //       console.log(response);
    //       if (confirm("続けて貸出し予約をしますか？")) {
    //         data.book_title = "";
    //         data.checkoutDate = "";
    //         data.returnDate = "";
    //         //「キャンセル」ボタンをクリックした時
    //       } else {
    //         router.push("/bookingList");
    //       }
    //     })
    //     .catch(error => {
    //       console.log(error);
    //       alert("ご入力された本はありません。\n再入力してください。");
    //     });
    // };
    //   


    //ページ読込み時に貸出日のminを今日、返却日のminを明日にする。
    // onMounted(() => {
      // const checkoutDay_min = dayjs().format("YYYY-MM-DD");
      // document.getElementById("date").setAttribute("min", checkoutDay_min);
      // const returnDay_min = dayjs(checkoutDay_min).add(1, "d").format("YYYY-MM-DD");
      // document.getElementById("date1").setAttribute("min", returnDay_min);
    // });


    onMounted(() => {
    getAPI_books();
    });
    
    // return { data, getAPI_books, doAction ,onMounted };
    return { data, getAPI_books, onMounted };

  }
};
</script>