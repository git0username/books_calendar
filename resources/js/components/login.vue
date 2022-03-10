<template>
  <div>
    <h3 class="text-primary">login form</h3>
    <div class="form-group" style="padding:30px">      
      <label for="studentNo">studentNo</label>
      <input id="studentNo" type="text" name="studentNo" v-model="data.studentNo" class="form-control" v-on:keydown ="enter"/>
      <br />
      <label for="password">パスワード</label>
      <input id="password" type="text" name="password" v-model="data.password" class="form-control" v-on:keydown ="enter"/>      
      <button class="btn- btn-info text-white mt-2" v-on:click="doAction_login">送信</button>      
    </div>
  </div>
</template>

<script>
import { reactive, onMounted, watch } from "vue";
import axios from "axios";
import { useRouter } from "vue-router"; //リダイレクト用
import { store } from "./store.js";

export default {
  name: "Login",
  setup() {
    const router = useRouter();

    const data = reactive({
      studentNo:"",
      password: "",  
    });  
    
      //子コンポーネントなので不要か？
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
    
    //送信ボタン押された処理
    const doAction_login = () => {
      //フォームの入力値チェック 未入力、半角数字以外をはじく
      if(data.studentNo == "" || data.password == ""){ //未入力はじく
        alert("入力してください。")
      }else{ //入力されてる場合
        if(!/^[0-9]*$/.test(data.studentNo) && !/^[0-9]*$/.test(data.password)){ //半角数字以外をはじく
          alert("入力値は半角数字のみです。\n 半角数字を入力してください。")
        }else{ //入力値が半角数字になってたら、post処理に進む
          const url = "/login"; 
          axios.post(url, {
            studentNo: data.studentNo,
            password: data.password,           
          })
          .then(response => {
            console.log("postレスポンス返ってきたよ");
            if(response.data["result"] == 'success'){ 
                  console.log("success");
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
            alert("認証エラー");
          });
        }
      }      
    };

    //form input でEnterkey押されたときの動作(送信処理させるdoAction_loginに飛ばす)
    const enter = (event) =>{      
      if( event.key == 'Enter' ){
        doAction_login();
      }
    };

    return { data, doAction_login ,enter, onMounted};
  },  
  
};

/*
↓不要コード 参考のため記載↓------------------------------
このコンポーネント以外のブラウザバック動作にも反応する なぜ？
    →addEventListenerはブラウザ全体の処理になるのでコンポーネントを越える

    addEventListener("popstate", () => {
      history.pushState(null, null, "/login");
      this.$router.push("/login");
      router.go(1);
      router.go(0);
    });
------------------------------------------------------------    
*/

</script>

<style scoped>
#studentNo, #password{
  width:300px;
}
</style>