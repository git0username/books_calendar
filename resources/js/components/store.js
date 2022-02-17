import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";

export const store = createStore({
    state () {
      return {
        studentInfo: null,
      }
    },

    mutations: {
      setStudentInfo (state, payload) {
        state.studentInfo = payload;
        console.log("store.state.studentInfo=");
        console.log(state.studentInfo);
      },

      // stateを初期化するmutationを定義 https://qiita.com/AtsushiEsashika/items/de5c925f0a5107a5d294
      clearStudentInfo(state) {
        Object.assign(state, state());
        console.log("storeデータは初期化されました。");
        console.log(state.studentInfo);
      }
    },    

    actions: {},
    getters: {},
    plugins: [createPersistedState({storage: window.sessionStorage, key:'studentInfo'})] 
});

