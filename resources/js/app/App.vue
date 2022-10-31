<template>
    <div>

        <nav class="navbar navbar-dark bg-primary navbar-expand-lg navbar-expand-xs navbar-expand-sm">
            <a class="navbar-brand pl-2" href="#">STRADATA</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">

                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'dashboard' }"  v-if="this.$store.state.autorized">Dashboard</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'login' }" v-if="!this.$store.state.autorized">Login</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'register' }"  v-if="!this.$store.state.autorized">Registro</router-link>
                </li>

              </ul>

            </div>
            <span class="navbar-text">
                <a class="btn btn-primary btn-sm text-white" @click="logout"  v-if="this.$store.state.autorized"><fa icon="fa-solid fa-right-from-bracket" /> Logout</a>
            </span>
          </nav>


        <div class="card-body">
            <br/>
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import router from "./routes"

export default{
    name:"App",
    methods:{

        logout() {
            axios.post("/api/v1/logout",{token:this.$store.state.token}).then((response) => {
                this.$store.commit('clearToken');
                this.$store.commit('setAutorize',false);
                this.$router.push("/login");

            }).catch((error) => {
                console.log(error);
            }).finally(function(){
                router.push("/login");
                console.log('test');
            });
        }
    },
}
</script>
<style scope>
    @import'~bootstrap/dist/css/bootstrap.css';
</style>
        <!--  -->
