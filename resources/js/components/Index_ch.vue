<template>
<tr>
      <td>{{item.booktypeId}}</td>
      <td>{{item.title}}</td>
      <td>{{item.number}}</td>      
      <td><button class="btn- btn-info text-white mt-2 flag" v-on:click="doAction()">借りる</button></td>             
</tr>
     
</template>

<script>
import { reactive, onMounted } from "vue";
import { useRouter } from 'vue-router'; //リダイレクト用
import { store } from "./store.js";
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
      item:props.item ,      
    });
    

    const router = useRouter();

    const doAction = () => {
      store.commit('setCalendar', data.item);
      router.push("/calendar");
    };    

    return { data, doAction ,onMounted };
  }
};
</script>