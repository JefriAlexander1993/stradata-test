import Vue from 'vue';
import VueRouter from "vue-router";
import Home from "./Home";
import Login from "./Login";
import Register from "./Register";
import Dashboard from "./Dashboard";

Vue.use(VueRouter);

export const routes=[
    {
        path:"/",
        name: "home",
        component:Home
    },
    {
        path:"/login",
        name: "login",
        component:Login
    },
    ,{
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path:"/dashboard",
        name: "dashboard",
        component:Dashboard
    }

];
