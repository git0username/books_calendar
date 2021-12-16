<template>
<div class="container">
  <h1>Book_list</h1>
  <HelloWorld       
      v-for="book_data in data.result"
      v-bind:book_data="book_data"
      v-bind:key="book_data"
  />  
  <router-view></router-view>
  <!-- <p>{{data.message}}</p> -->
  </div>
</template>


<script>
import HelloWorld from "./components/HelloWorld.vue";
import { reactive } from "vue"; 
import axios from 'axios';

export default {
  name: "App",
  components: {
    HelloWorld,
  },

  setup() {
    const data = reactive({
      message: "Hello Vue!!!!!!!!",
      result:'',
    });
    const url = "http://127.0.0.1:8000/api/books";
    const getAPI = async () => {
      const result = await axios.get(url);
      console.log(result);
      data.result = result.data;
    };

    getAPI();

    return {
      data,
      getAPI,
    };
  },
};
</script>