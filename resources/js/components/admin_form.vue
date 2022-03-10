<template>
  <div>
    <div class="form-group" style="padding:30px">
      <h4 class="text-success">管理者login</h4>          
      <label for="name">name</label>
      <input id="name" type="text" name="name" v-model="data.name" class="form-control" v-on:keydown ="enter"/>
      <br />
      <label for="password">パスワード</label>
      <input id="password" type="text" name="password" v-model="data.password" class="form-control" v-on:keydown ="enter"/>
      <button class="btn- btn-info text-white mt-2" v-on:click="doAction_admin_form">送信</button>      
    </div>
  </div>
</template>

<script>
import { reactive, onMounted, watch } from "vue";
import axios from "axios";
import { useRouter } from "vue-router"; //リダイレクト用
import { store } from "./store.js";

export default {
  name: "Admin",
  setup() {
    const router = useRouter();

    const data = reactive({
      name: "",
      studentNo: 100,
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
    const doAction_admin_form = () => {
      //フォームの入力値チェック 未入力、半角数字以外をはじく
      if(data.name == "" || data.studentNo == "" || data.password == ""){ //未入力はじく
        alert("入力してください。")
      }else{ //post処理に進む
            const url = "/login";
            axios.post(url, {
              name: data.name,
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
              alert("postエラー");
            });
          } 
    };

    //form input でEnterkey押されたときの動作(送信処理させるdoAction_loginに飛ばす)
    const enter = (event) =>{      
      if( event.key == 'Enter' ){
        doAction_admin_form();
      }
    };

    return { data, doAction_admin_form ,enter, onMounted};
  },  
  
};
</script>

<style scoped>
#name, #password{
  width:300px;
}
</style>