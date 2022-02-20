<template>
  <div>
    <Header />     
    <div style="display: flex">
      <div style="width:300px; margin:10px;">
        <p class="h5 a">{{data.title}}</p>

        <!-- <p>{{data.studentInfo}}</p>
        <p>{{data.studentInfo[0].studentNo}}</p>   -->

        <table class="table table-light table-striped" style="width:600px">
          <thead class="table-dark text-center">
            <tr>
              <th>BookID</th><th>Title</th><th>全冊数</th>
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
import Header from "./header.vue";
import { store } from "./store.js";


export default {
  name: "Index",
  components: { Index_ch, Header },

  setup() {
    const data = reactive({
      result:"",
      title: "BOOK_LIST",
      studentInfo:store.state.studentInfo.studentInfo, //使ってない？
      // checkoutDate: "",
      // returnDate: "",
      // userId:2      
    });

    const url = "http://127.0.0.1:8000/api/books";
    const getAPI_books = async () => {
      const result = await axios.get(url);
      data.result = result.data;       
      console.log("index.result.data=");
      // console.log(result.data); 
      console.log(store.state.studentInfo.studentInfo);
      console.log("index.data.studentinfo.studentNo");
      console.log(data.studentInfo.studentNo);
      // data.studentInfo = store.state.studentInfo;
    }
    onMounted(() => {
    getAPI_books();
    });
    
    // return { data, getAPI_books, doAction ,onMounted };
    return { data, getAPI_books, onMounted };

  }
};
</script>


<style>
/* @import "../css/style.css"; */
</style>