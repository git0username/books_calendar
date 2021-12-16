<template>
  <div>    
    <div style="display: flex">
      <div style="width:300px; margin:10px;">
        <!-- <h3>{{ data.book_title }}</h3> -->
        <h3>aaa</h3>

      </div>
    </div>
  </div>
</template>

<script>
import { reactive, onMounted } from "vue";
export default {
  name: "checkoutForm",

props: {
    book_data: Object,
  },

  setup(props) {
    const data = reactive({
      title: "check",
      book_title:""
    })

    const getData =()=>{
    data.book_title = props.book_data.title;
    console.log(data.book_title)
    };

    onMounted(()=>{
      getData()
    })

    return {data,getData};
  },
}
</script>