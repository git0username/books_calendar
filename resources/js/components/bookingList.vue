<template>
  <div>
    <h3>{{ data.title }}</h3>
    <div class="form-group" style="padding:30px">
      <!-- <form action="/api/check" method="post"> -->
      <label for="date">BOOKINGLIST：</label>
      <input
        type="text"
        placeholder="本のタイトルを入力"
        name="book_title"
        v-model="data.book_title"
        class="form-control"
      />
      <br />
      <label for="date">BOOKINGLIST：</label>
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
import { reactive, onMounted } from "vue";
// import { reactive, onMounted, watch } from "vue";
import axios from "axios";
// import dayjs from "dayjs";
// import { useRouter } from "vue-router"; //リダイレクト用

export default {
  name: "bookingList",
  setup() {
    const data = reactive({
      title: "貸出し履歴",
      response:"",      
    });       

    const getAction = () => {
      const url = "http://127.0.0.1:8000/api/bookonloan"; //このページがAPI入出力の窓口として機能している
      axios.get(url)
      .then((response) =>
        data.response = response.data
      );       
    };
        
    onMounted(() => {
      getAction();
    });

    return { data, getAction ,onMounted};
  }
};
</script>