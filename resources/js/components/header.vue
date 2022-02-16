<template>
  <div class="container">
      <h3 class="text-primary">studentNo：{{data.studentInfo[0].studentNo}}</h3>
      <h3 class="text-primary">ようこそ　{{data.studentInfo[0].name}}　さん</h3>
      
    <!-- <h1><a href="/index">Books</a></h1>    -->
    <nav>   
            <ul class="main-nav">
                <!-- <li><a href="/checkoutform">貸出し予約</a></li> -->
                <li><a href="/index">貸出し予約</a></li>
                <li><a href="/bookingList">過去に借りた本の一覧</a></li>
                <li><a href="/bookingList">logout</a></li>
                <li><a href="http://127.0.0.1:8000/api/bookonloan/2">api/bookonloan/2</a></li>
                <!-- <li><a href="url()->previous()">前のページに戻る</a></li> -->
            </ul>
        </nav>
  </div>
</template>

<script>
import { reactive, onBeforeMount } from "vue";
import { store } from "./store.js";

export default {
  name: "Header",
  components: {
    // 
  },

  setup() {
    const data = reactive({
        studentInfo: store.state.studentInfo,
    });

    // console.log("store.state.studentInfo_header=");
    // console.log(store.state.studentInfo);
    // console.log("data.studentInfo_header=");
    // console.log(data.studentInfo);
    // console.log("header.data.studentInfo[studentNo]=");
    //  console.log(data.studentInfo["studentNo"]);

     onBeforeMount(() => {
    // data.studentInfo = store.state.studentInfo;
    });

    

    return { data, onBeforeMount };
  }
};
</script>
<!--
<style>
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
-->
