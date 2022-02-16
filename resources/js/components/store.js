import { createStore } from "vuex";

export const store = createStore({
    state () {
      return {
        studentInfo: [],
      }
    },

    mutations: {
      setStudentInfo (state, payload) {
        state.studentInfo = payload;
        console.log("store.state.studentInfo=");
        console.log(state.studentInfo);
      }
    }
});

