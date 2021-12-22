<template>
  <div>
    <h3>{{ data.title }}</h3>
    <div class="form-group" style="padding:30px">
      <!-- <form action="/api/check" method="post"> -->
      <label for="date">本のタイトル：</label>
      <input
        type="text"
        placeholder="本のタイトルを入力"
        name="book_title"
        v-model="data.book_title"
        class="form-control"
      />
      <br />
      <label for="date">貸出し日：</label>
      <input type="date" id="date" name="checkoutDate" v-model="data.checkoutDate" class="form-control" />
      <br />
      <label for="date">返却日：</label>
      <input type="date" id="date1" name="returnDate" v-model="data.returnDate" class="form-control" />
      <button class="btn- btn-info text-white mt-2" v-on:click="doAction">送信</button>
      <!-- <router-link to="index">AA</router-link>  -->
    </div>
  </div>
</template>

<script>
import { reactive, onMounted, watch } from "vue";
import axios from "axios";
import dayjs from "dayjs";
import { useRouter } from "vue-router";

export default {
  name: "checkoutForm",
  setup() {
    const data = reactive({
      title: "貸出し予約フォーム",
      book_title: "こころ",
      checkoutDate: "",
      returnDate: ""
    });    

    const returnDate = () => {
      // const checkoutDay = dayjs().format("YYYY-MM-DD");
      const returnDay = dayjs(data.checkoutDate)
      .add(7, "d")
      .format("YYYY-MM-DD");
      // document.getElementById("date").setAttribute("min", checkoutDay);      
      document.getElementById("date1").setAttribute("max", returnDay);
      console.log("日=" + returnDay);
      console.log(typeof returnDay);
    };

    watch(data, () => {
      returnDate()
    });

    const router = useRouter();

    const doAction = () => {
      const url = "http://127.0.0.1:8000/api/check"; //このページがAPI入出力の窓口として機能している
      axios
        .post(url, {
          book_title: data.book_title,
          checkoutDate: data.checkoutDate,
          returnDate: data.returnDate
        })
        .then(response => {
          console.log(response);
          if (confirm("続けて貸出し予約をしますか？")) {
            data.book_title = "";
            data.checkoutDate = "";
            data.returnDate = "";
            //「キャンセル」ボタンをクリックした時
          } else {
            router.push("/");
          }
        })
        .catch(error => {
          console.log(error);
          alert("ご入力された本はありません。\n再入力してください。");
        });
    };

    return { data, doAction };
  }
};
</script>