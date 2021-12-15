<template>
<div class="container">
  <HelloWorld 
      v-for="book_data in result"
      v-bind:book_data="book_data"
      v-bind:key="book_data"
  />  
  <p>{{data.message}}</p>
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
      data.result = result;
      console.log(result);
    };

    getAPI();

    return {
      data,
      getAPI,
    };
  },
};
</script>