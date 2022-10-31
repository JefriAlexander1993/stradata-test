import Vuex from "vuex";
import Vue from "vue";


Vue.use(Vuex);


export const store =  new Vuex.Store({
    state:{
        token:localStorage.getItem('auth') || '',
        autorized:localStorage.getItem('auth') ? true: false,
        user:localStorage.getItem('user') || '',
    },
    mutations:{
        setToken(state,token){
            localStorage.setItem('auth',token);
            state.token = token;

        },
        setUser(state,name){
            state.user = name;
        },
        setAutorized(state,status){
            state.autorized = status;
        },
        clearToken(state){
            localStorage.removeItem('auth');
            state.token = "";
            state.user ="";
            state.autorized ="";

        }
    }

});
