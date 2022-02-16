<template>
  <div class="container">
      <h3 class="text-primary">studentNo：{{}}</h3>
    <!-- <h1><a href="/index">Books</a></h1>    -->
    <nav>   
            <ul class="main-nav">
                <!-- <li><a href="/checkoutform">貸出し予約</a></li> -->
                <li><a href="/index">貸出し予約</a></li>
                <li><a href="/bookingList">過去に借りた本の一覧</a></li>
                <li><a href="/bookingList">logout</a></li>
                <li><a href="http://127.0.0.1:8000/api/bookonloan/3">api/bookonloan/3</a></li>
                <!-- <li><a href="url()->previous()">前のページに戻る</a></li> -->
            </ul>
        </nav>
  </div>
</template>

<script>
import { reactive } from "vue";

export default {
  name: "Header",
  components: {
    // 
  },

  setup() {
    const data = reactive({});

    return { data };
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
