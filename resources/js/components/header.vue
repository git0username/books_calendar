<template>
  <div class="container">
    <h3 class="text-primary"><span class="mgr-10">studentNo：{{data.studentInfo.studentInfo.studentNo}}</span>{{data.studentInfo.studentInfo.name}} さん
    </h3>  
    
    <nav> 
      <ul class="main-nav">               
        <li><a href="/index">書籍一覧 / 貸出し予約</a></li>
        <li><a href="/bookingList">貸出し履歴一覧</a></li>                
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
        window.location.href = '/logout';
    };

    return { data, doAction };
  }
};
</script>

<style scoped>
.container{
  /* width:100%;
  background-color: antiquewhite; */
}
.mgr-10{
  margin-right : 30px;
}
ul{
  padding-top: 10px; 
  padding-left: 0px;  
  margin-bottom: 50px;
  text-align: center;
}
ul li{
  margin-right : 20px;
}
</style>
