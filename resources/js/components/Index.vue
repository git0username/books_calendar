<template>
  <div>
    <div style="display: flex">
      <div style="width:300px; margin:10px;">
        <p>{{data.title}}</p>

        <table class="table table-light table-striped">
          <thead class="table-dark text-center">
            <tr>
              <th>ID</th><th>Name</th>
            </tr>
          </thead>
          <tbody class="text-left">
            <tr v-for="(item, key) in data.result" v-bind:key="key">
              <td>{{key}}</td>
              <td>{{item.title}}</td>
              <!-- <td>{{item.age}}</td> -->              
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
      book_title: ""
    });

    const url = "http://127.0.0.1:8000/api/books";
    const getAPI = async () => {
      const result = await axios.get(url);
      data.result = result.data;
      console.log("result=" + result.data);

      data.book_title = result.title;
      console.log("book_title=" + result.data.title);
    };

    getAPI();

    return { data, getAPI };
  }
};
</script>