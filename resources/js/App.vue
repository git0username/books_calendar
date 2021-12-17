<template>
  <div class="container">
    <h1>Book_list</h1>
    <router-view></router-view>
    <!-- <p>{{data.message}}</p> -->
  </div>
</template>


<script>
// import HelloWorld from "./components/HelloWorld.vue";
import { reactive } from "vue";

export default {
  name: "App",
  components: {
    // HelloWorld
  },

  setup() {
    const data = reactive({});

    return { data };
  }
};
</script>