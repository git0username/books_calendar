<template>
  <div>
    <Login />
    <UserRegister />
  </div>
</template>

<script>
import { reactive, onMounted, watch } from "vue";
import axios from "axios";
import Login from "./login.vue";
import UserRegister from "./userRegister.vue";

export default {
  name: "Login_form",
  components: { Login, UserRegister },
  setup() {
    const data = reactive({
      //
    });  
    
    //なくても正常動作する。index直打ちしてもloginに飛ばされるのでmiddleware認証が機能している。
      //でも公式docに書かれているので下記の処理をする。初期化のためなのかな？
      const getToken = async () => {
      const result = await axios.get("sanctum/csrf-cookie");
      console.log("csrf-cookie=");
      console.log(result); 
    };
    
    onMounted(() => {
      getToken();      
    });
    
    watch(data, () => {
      //
    });
    return { data, onMounted};
  },  
  
};
</script>