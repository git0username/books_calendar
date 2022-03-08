<template>
  <div>
    <div class="form-group" style="padding:30px">
      <h4 class="text-success">新規登録</h4> 
      <label for="name">お名前</label>
      <input id="name" type="text" name="name" v-model="data.name" class="form-control" v-on:keydown ="enter"/><!-- value="{{ old('name') }}" -->
      <br />     
      <label for="studentNo">studentNo</label>
      <input id="studentNo" type="text" name="studentNo" v-model="data.studentNo" class="form-control" v-on:keydown ="enter"/>
      <br />
      <label for="password">パスワード</label>
      <input id="password" type="text" name="password" v-model="data.password" class="form-control" v-on:keydown ="enter"/>      
      <button class="btn- btn-info text-white mt-2" v-on:click="doAction_userRegister">送信</button>      
    </div>
  </div>
</template>

<script>
import { reactive, onMounted, watch } from "vue";
import axios from "axios";
import { useRouter } from "vue-router"; //リダイレクト用
import { store } from "./store.js";

export default {
  name: "UserRegister",
  setup() {
    const router = useRouter();

    const data = reactive({
      name:"",
      studentNo:"",
      password: "",  
    });  
    
      //子コンポーネントなので不要か？
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
    
    //送信ボタン押された処理
    const doAction_userRegister = () => {
      //フォームの入力値チェック 未入力、半角数字以外をはじく
      if(data.name == "" || data.studentNo == "" || data.password == ""){ //未入力はじく
        alert("入力してください。")
      }else{ //入力されてる場合
        if(!/^[0-9]*$/.test(data.studentNo) || !/^[0-9]*$/.test(data.password)){ //studentNoとパスワードは半角数字のみ許容
          alert("お名前以外の入力値は半角数字のみです。\n 半角数字を入力してください。")
          }else{ //入力値が半角数字になってたら、post処理に進む
            const url = "/userRegister";
            axios.post(url, {
              name: data.name,
              studentNo: data.studentNo,
              password: data.password,           
            })
            .then(response => {
              console.log("postレスポンス返ってきたよ");
              console.log(response.data);
              if(response.data["result"] == 'exist'){ 
                    console.log("exist");
                    console.log(response.data["studentInfo"]);
                    alert("入力されたstudentNoはすでに登録されています。");
                    // alert("ログインフォームに入力し、ログインしてください。")
              }else{
                alert("登録エラー。\n再入力してください");
              }         
            })
            .catch(error => {
              console.log(error);
              alert("postエラー");
            });
          }      
      }      
    };

    //form input でEnterkey押されたときの動作(送信処理させるdoAction_loginに飛ばす)
    const enter = (event) =>{      
      if( event.key == 'Enter' ){
        doAction_userRegister();
      }
    };

    return { data, doAction_userRegister ,enter, onMounted};
  },  
  
};
</script>

<style scoped>
#name, #studentNo, #password{
  width:300px;
}
</style>