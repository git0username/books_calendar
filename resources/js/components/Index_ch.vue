<template>
<tr>
      <td>{{item.booktypeId}}</td>
      <td>{{item.title}}</td>
      <!-- <td>{{item.number}}</td>       -->
      <td><button class="btn- btn-info text-white mt-2 flag" v-on:click="doAction(item.booktypeId)">借りる</button></td>             
</tr>
     
</template>

<script>
import { reactive, onMounted } from "vue";
import axios from "axios";
import { useRouter } from 'vue-router'; //リダイレクト用
// import Router from 'Router.vue'
// import { Calendar } from '@fullcalendar/core';


export default {
  name: "Index_ch",
  props: {
    item: Object,
    key: String
  },

  setup(props) {
    const data = reactive({ 
      userId:2,
      item:props.item ,
      response:[]
    });
    

    const router = useRouter();

    const doAction = (bookId) => {
      const url = "http://127.0.0.1:8000/api/calendar/" + bookId; //このページがAPI入出力の窓口として機能している
      axios.get(url).then(response => {
        data.response = response.data;
        console.log(data.response);        
        // router.push("/calendar");
        router.push({
          name:"calendar",
          params:{id:bookId,}
        })
      }).catch(error => {
          console.log(error);
          alert("エラ－");
        });
    }; 

    return { data, doAction ,onMounted };
  }
};
</script>