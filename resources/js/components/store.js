import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";

//studentInfoのstore
const studentInfo = {
  state: () => ({
     studentInfo: null,
    }),
  mutations: {
    setStudentInfo (state, payload) {
      state.studentInfo = payload;
      console.log("store.state.studentInfo=");
      console.log(state.studentInfo);
    },
    // stateを初期化するmutationを定義 https://qiita.com/AtsushiEsashika/items/de5c925f0a5107a5d294
    clearStudentInfo (state) {
      state.studentInfo = null;
      // Object.assign(state, getDefaultState());
      console.log("store_studentInfoデータは初期化されました。");
      console.log(state.studentInfo);
    }
  },
  actions: {
    //
  },
  getters: {
    //
  }, 
  
}

//calendar用のsotre
const calendar = {
  state: () => ({
    calendar: null, }),
  mutations: {
    setCalendar (state, payload) {
      state.calendar = payload;
      console.log("store.state.calendar=");
      console.log(state.calendar);
    },
    clearCalendar (state) {
      state.calendar = null;
      // Object.assign(state, getDefaultState());
      console.log("store_calendarデータは初期化されました。");
      console.log(state.calendar);
    }
  },
  actions: {
    //
  },
  getters: {
    //
  },  
}

//calendar編集中のデータ
const calendarEdit_data = {
  state: () => ({
    calendarEdit_data: null,
    }),
  mutations: {
    setCalendarEdit_data (state, payload) {
      state.calendarEdit_data = payload;
      console.log("store.calendarEdit_data=");
      console.log(state.calendarEdit_data);
    },
    // stateを初期化するmutationを定義 https://qiita.com/AtsushiEsashika/items/de5c925f0a5107a5d294
    clearCalendarEdit_data (state) {
      state.calendarEdit_data = null;
      // Object.assign(state, getDefaultState());
      console.log("store_calendarEdit_dataデータは初期化されました。");
      console.log(state.studentInfo);
    }
  },
  actions: {
    //
  },
  getters: {
    //
  }, 
  
}

export const store = createStore({
  modules: {
    studentInfo: studentInfo,
    calendar: calendar,
    calendarEdit_data: calendarEdit_data, 
  },
  plugins: [createPersistedState({storage: window.sessionStorage, key:'books'})]
})

// export const store = createStore({
//   // state:  getDefaultState(),

//     state(){
//       return{
//       studentInfo : null,

//       }
//     },
    
  

//     mutations: {
//       setStudentInfo (state, payload) {
//         state.studentInfo = payload;
//         console.log("store.state.studentInfo=");
//         console.log(state.studentInfo);
//       },

//       // stateを初期化するmutationを定義 https://qiita.com/AtsushiEsashika/items/de5c925f0a5107a5d294
//       clearStudentInfo (state) {
//         state.studentInfo = null;
//         // Object.assign(state, getDefaultState());
//         console.log("storeデータは初期化されました。");
//         console.log(state.studentInfo);
//       }
//     },    

//     actions: {},
//     getters: {},
//     plugins: [createPersistedState({storage: window.sessionStorage, key:'studentInfo'})] 
// });

