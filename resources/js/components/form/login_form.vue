<template>
  <div>
    <Login />
    <UserRegister />
    <Admin />
  </div>
</template>

<script>
import { reactive, onMounted, watch } from "vue";
import axios from "axios";
import Login from "./login.vue";
import UserRegister from "./userRegister.vue";
import Admin from "./admin_form.vue";

export default {
  name: "Login_form",
  components: { Login, UserRegister, Admin },
  setup() {
    const data = reactive({
      //
    });  
    
    //なくても正常動作する。index直打ちしてもloginに飛ばされるのでmiddleware認証が機能している。
      //でも公式docに書かれているので下記の処理をする。初期化のためなのかな？
      const getToken = async () => {
      await axios.get("sanctum/csrf-cookie");
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
<!--
passwordリセットシステム未導入
-->