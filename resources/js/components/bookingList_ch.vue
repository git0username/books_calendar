<template>
    <tbody class="text-left">
      <tr>
        <td>{{item.id}}</td>
        <td>{{item.title}}</td>
        <td>{{item.checkoutDate}}</td>
        <td>{{item.returnDate}}</td>
      </tr>
    </tbody>
</template>

<script>
import { reactive, onMounted } from "vue";

export default {
  name: "bookingList_ch",
  props: {
    item: Object,
    index: Number
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