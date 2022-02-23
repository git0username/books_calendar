<template>
  <div class="container">
      <h3 class="text-primary">studentNo：{{data.studentInfo.studentInfo.studentNo}}</h3>
      <h3 class="text-primary">{{data.studentInfo.studentInfo.name}} さんのページ</h3>      
    
    <nav>   
            <ul class="main-nav">               
                <li><a href="/index">貸出し予約</a></li>
                <li><a href="/bookingList">過去に借りた本の一覧</a></li>                
                <li><a href="#" v-on:click='doAction()'>logout</a></li>                
            </ul>
        </nav>
  </div>
</template>

<script>
import { reactive } from "vue";
import { store } from "./store.js";

export default {
  name: "Header",
  components: {
    // 
  },

  setup() {  
    const data = reactive({
        studentInfo: store.state.studentInfo, //store.state.(module名).(指定したmoduleのstateのキー名)        
    });  
   
    const doAction = ()=>{        
        sessionStorage.clear(); 
        window.location.href = 'http://127.0.0.1:8000/logout';
    };

    return { data, doAction };
  }
};
</script>
