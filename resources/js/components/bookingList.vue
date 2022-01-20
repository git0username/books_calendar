<template>
  <bookingList_ch
  <!-- v-for="(item, index) in tweeted"
    v-bind:key="index" 
    v-bind:item="item"
    v-bind:index="index" -->
    />
</template>

<script>
import { reactive, onMounted } from "vue";
import axios from "axios";
import bookingList_ch from "/bookingList_ch.vue";

export default {
  name: "bookingList",
  components: { bookingList_ch },   
  
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