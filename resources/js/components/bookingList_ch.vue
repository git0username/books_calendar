<template>
  <div>
    <h3>{{ data.booking }}</h3>
    <table class="table table-light table-striped">
          <thead class="table-dark text-center">
            <tr>
              <th>ID</th><th>Name</th>
            </tr>
          </thead>
          <tbody class="text-left">
            <tr>
              <td>{{item.title}}</td>
            </tr>
          </tbody>
    </table>
  </div>
</template>

<script>
import { reactive, onMounted } from "vue";

export default {
  name: "bookingList_ch",
  props: {
    item: Object,
    index:Number
  },

  setup(props) {
    const data = reactive({
      title: "貸出し履歴",
      response: "",
      table_obj: props.item
    });

    const getAction = () => {
      console.log("props=" + props.item);
    };

    onMounted(() => {
      getAction();
    });

    return { data, getAction };
  }
};
</script>