<template>
  <div class="container">
      <h3 class="text-primary">studentNo：{{data.studentInfo.studentInfo.studentNo}}</h3>
      <h3 class="text-primary">{{data.studentInfo.studentInfo.name}} さんのページ</h3>
      
    <!-- <h1><a href="/index">Books</a></h1>    -->
    <nav>   
            <ul class="main-nav">
                <!-- <li><a href="/checkoutform">貸出し予約</a></li> -->
                <li><a href="/index">貸出し予約</a></li>
                <li><a href="/bookingList">過去に借りた本の一覧</a></li>
                <li><a href="api/logout">api_logout aタグ</a></li>
                <li><a href="#" v-on:click="doAction()" >api_logout</a></li>
                 <li><a href="/logout">logout aタグ</a></li>               
                <li><a href="api/calendar/1">api/bookonloan/2</a></li>
                <li><a href="api/calendar/1/2">api/calendar/1/2</a></li>
                <li><a href="api/calendar/1">api/calendar/1</a></li>
                <!-- <li><a href="url()->previous()">前のページに戻る</a></li> -->
            </ul>
        </nav>
  </div>
</template>

<script>
import { reactive, onBeforeMount } from "vue";
import { store } from "./store.js";
import { useRouter } from "vue-router";
import axios from "axios";

export default {
  name: "Header",
  components: {
    // 
  },

  setup() { 
      console.log("header.store.state.studentInfo.studentInfo=");
      console.log(store.state.studentInfo);

    const data = reactive({
        studentInfo: store.state.studentInfo, //store.state.(module名).(指定したmoduleのstateのキー名)        
    });
    console.log("data.studentInfo=");
    console.log(data.studentInfo);
    console.log("data.studentInfo.studentNo=");
    console.log(data.studentInfo.studentInfo.studentNo);


    const router = useRouter();
    // const deleteCookie = ()=>{
    //      document.cookie = "XSRF-TOKEN=true; expires=Fri, 31 Dec 9999 23:59:59 GMT";
    // }

   
    const doAction = ()=>{
        // store.commit("clearStudentInfo");
        // store.commit("clearCalendar");
        sessionStorage.clear();                
        // router.push("/logout");
              // window.location.href('http://127.0.0.1:8000/logout')

         const url = "http://127.0.0.1:8000/api/logout";
    axios.get(url)
    .then(function (response) {
    console.log(response.data);
  });
    
     
    };

    const noLink = ()=> {
    // これで画面の遷移を止めます
    return false;
  };

    

    // console.log("store.state.studentInfo_header=");
    // console.log(store.state.studentInfo);
    // console.log("data.studentInfo_header=");
    // console.log(data.studentInfo)2;
    // console.log("header.data.studentInfo[studentNo]=");
    //  console.log(data.studentInfo["studentNo"]);

     onBeforeMount(() => {
    // data.studentInfo = store.state.studentInfo;
    });

    

    return { data, onBeforeMount, doAction, noLink };
  }
};
</script>
