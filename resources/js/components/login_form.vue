<template>
  <div>
    <h3>login form</h3>
    <div class="form-group" style="padding:30px">
      <!-- <form action="/api/check" method="post"> -->
      <label for="studentNo">studentNo</label>
      <input id="studentNo" type="text" name="studentNo" v-model="data.studentNo" class="form-control" />
      <br />
      <label for="password">パスワード</label>
      <input id="password" type="text" name="password" v-model="data.password" class="form-control" />      
      <button class="btn- btn-info text-white mt-2" v-on:click="doAction">送信</button>      
    </div>
  </div>
</template>

<script>
import { reactive, onMounted, watch } from "vue";
import axios from "axios";
import dayjs from "dayjs";
import { useRouter } from "vue-router"; //リダイレクト用

export default {
  name: "login",
  setup() {
    const data = reactive({
      studentNo:"",
      password: "",      
      // token:""
    });  
    
      const getToken = async () => {
      const result = await axios.get("sanctum/csrf-cookie");
      // data.token = result.data;       
      console.log("result.data=");
      console.log(result.data); 
    }
    onMounted(() => {
    getToken();
    });
    
    watch(data, () => {
      //
    });

    const router = useRouter();

    const doAction = () => {
      const url = "http://127.0.0.1:8000/api/login"; //このページがAPI入出力の窓口として機能している
      axios
        .post(url, {
          studentNo: data.studentNo,
          password: data.password,           
        })
        .then(response => {
          console.log(response);
          // router.push("/");
          // if (confirm("続けて貸出し予約をしますか？")) {
          //   data.book_title = "";
          //   data.checkoutDate = "";
          //   data.returnDate = "";
          //   //「キャンセル」ボタンをクリックした時
          // } else {
          //   router.push("/");
          // }
        })
        .catch(error => {
          console.log(error);
          // alert(" ");
        });
    };


    return { data, doAction ,onMounted};
  }
};
</script>