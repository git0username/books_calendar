<template>
  <div>
    <h1>{{ data.title }}</h1>
    <div style="display: flex">
        <div style="width:300px; margin:10px;">
          <h3>{{ book_title }}</h3>
        </div>   
    </div>
  </div>

</template>

<script>
import { reactive } from "vue";
export default {
  name: "HelloWorld",

props: {
    book_data: Object,
  },

  setup() {
    const data = reactive({
      title: "Book_list",
    })

const getData =()=>{
this.book_title = this.book_data.Item.title;

}


     onMounted(()=>{
      getData()
    })

    return {
      data,
    };
  },
};
</script>