<template>
  <div>
    <div style="display: flex">
      <div style="width:300px; margin:10px;">
        <h3>{{ data.title }}</h3>
      </div>
    </div>

    <div class="form-group" style="padding:30px">
      <div>
        <!-- <form action="/checkoutForm" method="post"> -->
        <label for="date">本のタイトル：</label>
        <input type="text" placeholder="本のタイトルを入力" name="book_title" v-model="book_title" class="form-control" />
        <br />
        <label for="date">貸出しの日付：</label>
        <input type="date" name="checkoutDate" v-model="checkoutDate" max="2021-12-24" class="form-control" />
        <button class="btn- btn-info text-white mt-2" v-on:click="doAction">送信</button>
        <!-- <input type="submit" value="送信" > -->
        <!-- </form> -->
      </div>
    </div>
  </div>
</template>

<script>
import { reactive, onMounted } from "vue";
import axios from 'axios';
import dayjs from "dayjs";

export default {
  name: "checkoutForm",
  setup() {
      const data = reactive({
        title: "貸出し予約フォーム",
        book_title: "",
        checkoutDate:"",
        return:"",
      });

      onMounted(() => {
        const checkoutDay = dayjs().format("YYYY-MM-DD");
        const returnDay = dayjs(checkoutDay).add(7, "d").format("YYYY-MM-DD");
        document.getElementById("date").setAttribute("min", checkoutDay);
        document.getElementById("date").setAttribute("max", returnDay);
        console.log("日=" + returnDay);
        console.log(typeof returnDay);     
      });


     //doaction=>
      const doAction = (() =>{
          const url = "http://127.0.0.1:8000/api/books/check"; //このページがAPI入出力の窓口として機能している
          axios.post(url,{
              bookId: data.book_title,
              checkout: data.checkoutDate,
              return:'',
          })

          .then(() => {
            console.log(url)
        });       
        
     });
      
      return { data , doAction };

    }
    
};
</script>