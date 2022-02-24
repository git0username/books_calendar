<template>
  <div>
    <Header />    
    <p class="h5">{{data.title}}</p>    
    <table class="table table-light table-striped">
      <thead class="table-light text-center">
        <tr>
          <th>貸出しID</th>
          <th>本のタイトル</th>
          <th>貸出日</th>
          <th>返却日</th>
        </tr>
      </thead>
       <!-- <tbody class="text-left align-middle" id="tbodyID">     -->
      <bookingList_ch
        v-for="(item, index) in data.table_obj"
        v-bind:key="index"
        v-bind:item="item"
      />
       <!-- </tbody> -->
    </table>
  </div>
</template>

<script>
import { reactive, onMounted } from "vue";
import axios from "axios";
import bookingList_ch from "./bookingList_ch.vue";
import Header from "./header.vue";
import { store } from "./store.js";

export default {
  name: "bookingList",
  components: { bookingList_ch, Header },

  setup() {
    const data = reactive({
      title: "貸出し履歴一覧のページ",
      table_obj: {},
      studentNo:store.state.studentInfo.studentInfo.studentNo,      
    });

    const getAction = async () => {
      const url = "http://127.0.0.1:8000/api/bookonloan/" + data.studentNo ; //このページがAPI入出力の窓口として機能している
      const response = await axios.get(url);
      console.log(response.data);
      data.table_obj = response.data;  
      //  console.log(store.state.studentInfo.studentInfo);
    };

    onMounted(() => {
      getAction();
    });


    return { data, getAction };
  }
};
</script>

<style>
.h5 {
  color: rgb(245, 115, 212);
}
</style>