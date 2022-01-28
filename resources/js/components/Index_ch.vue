<template>
<tr>
      <td>{{item.bookId}}</td>
      <td>{{item.title}}</td>
      <td>{{item.number}}</td>
      <td>{{item.onloan_number}}</td>
      <td>{{item.userId}}</td>
      <td v-if="item.available_loan_date">{{item.available_loan_date}}</td>
      <td v-else>"貸出可"</td>
      <td><input type="date" id="date" name="checkoutDate" v-model="data.checkoutDate" v-bind:min = item.available_loan_date  class="form-control" /></td>
      <td><input type="date" id="date1" name="returnDate" v-model="data.returnDate" class="form-control" /></td>      
      <td><button class="btn- btn-info text-white mt-2 flag" v-on:click="doAction(item.bookId)">送信</button></td>             
</tr>
     
</template>

<script>
import { reactive, onMounted, watch } from "vue";
import axios from "axios";
import dayjs from "dayjs";
import { useRouter } from "vue-router"; //リダイレクト用


export default {
  name: "Index_ch",
  props: {
    item: Object,
    key: String
  },

  setup(props) {
    const data = reactive({      
      checkoutDate: "",
      returnDate: "",
      userId:2,
      item:props.item     
    });
    
    const returnDate = () => {
      // //return日に条件を付ける場合add()の第一引数(7日)を変更
      const returnDay_max = dayjs(data.checkoutDate).add(7, "d").format("YYYY-MM-DD");
      console.log(returnDay_max)
      // //return_min日はcheckout日の翌日から
      const returnDay_min = dayjs(data.checkoutDate).add(1, "d").format("YYYY-MM-DD");
      console.log(document.getElementById("date1"))
      document.getElementById("date1").setAttribute("max", returnDay_max);
      document.getElementById("date1").setAttribute("min", returnDay_min);
      console.log("日=" + returnDay_min);
      console.log(typeof returnDay);
    };

    watch(()=>data.checkoutDate, () => {
      returnDate()
    });

    const router = useRouter();

    const doAction = (bookId) => {
      const url = "http://127.0.0.1:8000/api/bookonloan"; //このページがAPI入出力の窓口として機能している
      axios
        .post(url, {
          // book_title: data.book_title,
          bookId: bookId,
          checkoutDate: data.checkoutDate,
          returnDate: data.returnDate,
          userId: data.userId, 
        })
        .then(response => {
          console.log(response);
          if (confirm("続けて貸出し予約をしますか？")) {            
            //「キャンセル」ボタンをクリックした時
          } else {
            router.push("/bookingList");
          }
        })
        .catch(error => {
          console.log(error);
          alert("ご入力された本はありません。\n再入力してください。");
        });
    };   


    //ページ読込み時に貸出日のminを今日、返却日のminを明日にする。
    onMounted(() => {
      // const checkoutDay_min = dayjs().format("YYYY-MM-DD");
      // document.getElementById("date").setAttribute("min", checkoutDay_min);
      // const returnDay_min = dayjs(checkoutDay_min).add(1, "d").format("YYYY-MM-DD");
      // document.getElementById("date1").setAttribute("min", returnDay_min);      
    });


   
    
    
    return { data, doAction ,onMounted };
  }
};
</script>