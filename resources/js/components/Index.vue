<template>
  <div>
    <div style="display: flex">
      <div style="width:300px; margin:10px;">
        <p>{{data.title}}</p>

        <table class="table table-light table-striped" style="table-layout:auto">
          <thead class="table-dark text-center">
            <tr>
              <th>BookID</th><th>Title</th><th>全冊数</th><th>貸出中</th><th>userId</th><th>貸出し可能日</th>
            </tr>
          </thead>
          <tbody class="text-left">
            <tr v-for="(item, key) in data.result" v-bind:key="key">
              <td>{{key}}</td>
              <td>{{item.title}}</td>
              <td>{{item.number}}</td>
              <td>{{item.number}}</td>
              <td>{{item.userId}}</td>
              <td>{{item.title}}</td>                            
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { reactive } from "vue";
import axios from "axios";


export default {
  name: "Index",

  setup() {
    const data = reactive({
      result:"",
      title: "BOOK_LIST",      
    });

    const url = "http://127.0.0.1:8000/api/books";
    const getAPI_books = async () => {
      const result = await axios.get(url);
      data.result = result.data;
      console.log(result.data);      
    };

    getAPI_books();    

    return { data, getAPI_books };
  }
};
</script>