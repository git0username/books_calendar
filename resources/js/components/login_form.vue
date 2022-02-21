<template>
  <div>
    <h3 class="text-primary">login form</h3>
    <div class="form-group" style="padding:30px">
      <!-- <form action="/api/check" method="post"> -->
      <label for="studentNo">studentNo</label>
      <input id="studentNo" type="text" pattern="^[0-9]*$" name="studentNo" v-model="data.studentNo" class="form-control" oninput="" />
      <br />
      <label for="password">パスワード</label>
      <input id="password" type="text" name="password" v-model="data.password" class="form-control" />      
      <button class="btn- btn-info text-white mt-2" v-on:click="doAction">送信</button>      
    </div>
  </div>
</template>

<script>
import { reactive, onMounted, watch } from "vue";
import axios from "axios";
import dayjs from "dayjs";
import { useRouter } from "vue-router"; //リダイレクト用
import { store } from "./store.js";


export default {
  name: "login",
  setup() {
    const router = useRouter();

    const data = reactive({
      studentNo:"",
      password: "",  
    });  
    
      //なくても正常動作する。index直打ちしてもloginに飛ばされるのでmiddleware認証が機能している。
      const getToken = async () => {
      const result = await axios.get("sanctum/csrf-cookie");
      // data.token = result.data;       
      console.log("csrf-cookie=");
      console.log(result); 
    };
    
    onMounted(() => {
      getToken();
      // sessionStorage.clear();   
    });
    
    watch(data, () => {
      //
    });

    

    const doAction = () => {
      const url = "http://127.0.0.1:8000/api/login"; //このページがAPI入出力の窓口として機能している      
      
        axios.post(url, {
          studentNo: data.studentNo,
          password: data.password,           
        })
        .then(response => {
           console.log("a");
          console.log(response.data);
          if(response.data["result"] == 'success'){ 
                 console.log("success");
                 console.log(response.data["studentInfo"]);
            //storeにlogin情報(studentInfo)を保存
            store.commit('setStudentInfo', response.data["studentInfo"]);
            //indexへリダイレクト
            router.push("/");
          }else{
            alert("ログイン出来ませんでした。\n再入力してください");
          }         
        })
        .catch(error => {
          console.log(error);
          alert("入力して下さい。");
        });      
    };

   //cookieの挙動を確認 よく分からない
    // console.log('document.cookie');    
    // console.log(document.cookie);
    // document.cookie = "XSRF-TOKEN=; max-age=0";
    // document.cookie = "laravel_session=; max-age=0";
    // console.log('document.cookie.delete');
    // console.log(document.cookie);
    

    return { data, doAction ,onMounted};
  },  
  
};

//このコンポーネント以外のブラウザバック動作にも反応する なぜ？
// addEventListener("popstate", () => {
//       history.pushState(null, null, "/login");
//       this.$router.push("/login");
      // router.go(1);
      // router.go(0);
    // });

</script>