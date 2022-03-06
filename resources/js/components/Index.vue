<template>
  <div>
    <Header />     
    <div style="display: flex">
      <div style="width:300px; margin:10px;">
        <p class="h5 a">{{data.title}}</p>
        <table class="table table-light table-striped" style="width:600px">
          <thead class="table-text-center">
            <tr>
              <th>BookID</th><th>Title</th><th>所蔵数</th><th></th>
            </tr>
          </thead>
          <tbody class="text-left align-middle" id="tbodyID">            
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
import { reactive, onMounted, } from "vue";
import axios from "axios";
import Index_ch from "./Index_ch.vue";
import Header from "./header.vue";
import { store } from "./store.js";


export default {
  name: "Index",
  components: { Index_ch, Header },

  setup() {
    const data = reactive({
      result:"",
      title: "書籍一覧 / 貸出し予約 のぺージ",
      studentInfo:store.state.studentInfo.studentInfo, //使ってない？            
    });

    const url = "/books";
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
    
    return { data, getAPI_books, onMounted };
  }
};
</script>


<style>
/* @import "../css/style.css"; */
.h5 {
  color: rgb(245, 115, 212);
}
</style>