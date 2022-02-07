<template>
  <div>
    <p>{{data.title}}</p>
    <h3>{{ data.booking }}</h3>
    <table class="table table-light table-striped">
      <thead class="table-dark text-center">
        <tr>
          <th>貸出しID</th>
          <th>本のタイトル</th>
          <th>貸出日</th>
          <th>返却日</th>
        </tr>
      </thead>
      <bookingList_ch
        v-for="(item, index) in data.table_obj"
        v-bind:key="index"
        v-bind:item="item"
      />
    </table>
  </div>
</template>

<script>
import { reactive, onMounted } from "vue";
import axios from "axios";
import bookingList_ch from "./bookingList_ch.vue";

export default {
  name: "bookingList",
  components: { bookingList_ch },

  setup() {
    const data = reactive({
      title: "貸出し履歴",
      table_obj: {},
      userId:2,
    });

    const getAction = async () => {
      const url = "http://127.0.0.1:8000/api/bookonloan" + data.userId; //このページがAPI入出力の窓口として機能している
      const response = await axios.get(url);
      console.log(response.data);
      data.table_obj = response.data;
    };

    onMounted(() => {
      getAction();
    });

    return { data, getAction };
  }
};
</script>