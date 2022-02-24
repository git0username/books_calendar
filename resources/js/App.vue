<template>
  <div class="container">      
    <h1><a href="/index">Books</a></h1>      
    <!-- <Header />        -->
    <router-view></router-view>
  </div>
</template>


<script>
import Header from "./components/header.vue";
import { reactive } from "vue";

export default {
  name: "App",
  components: {
    Header
  },

  setup() {
    const data = reactive({});

    return { data };
  }
};
</script>
<style>
.main_title {
    /* width:auto;
    background-color:antiquewhite */
}
div
    body {
        color: #333333;
        background-color: #FFFFFF;
        font-size: 100%;
        line-height: 1.7;
        margin: 10px auto;
        width: 90%;
        -webkit-text-size-adjust: 100%;
    }

    a {
        text-decoration: none;
    }

    .main-nav {
        display: flex;          
        list-style: none;
        margin: 10px;
    }

    /* .main-nav{
        display: flex; 
        border: solid black 1px;
    }

    .main-nav p{        
        margin: 10px;
    } */

    .main-nav li {
        margin-left: 15px;

    }

    header h1 {
        color: darksalmon;
    }

    table {
        width: 80%;
        border-collapse: collapse;
        border-spacing: 0;
        
    }

    table th,
    table td {
        padding: 10px;
        text-align: center;
    }

    table tr:nth-child(odd) {
        background-color: #eee;
    }

</style>