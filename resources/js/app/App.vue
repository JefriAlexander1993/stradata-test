<template>
    <div>
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand pl-2" href="#">STRADATA</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                <li class="nav-item active">
                  <router-link class="nav-link" :to="{ name: 'home' }"  v-if="this.$store.state.token != ''">Home</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'dashboard' }"  v-if="this.$store.state.token != ''">Dashboard</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'login' }" v-if="this.$store.state.token == ''">Login</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{ name: 'register' }"  v-if="this.$store.state.token == ''">Registro</router-link>
                </li>
              </ul>
            </div>
          </nav>
        <div class="panel-body">
            <br/>
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default{
    name:"App",
}
</script>
<style scoped>

   #app {
      font-family: 'Avenir', Helvetica, Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-align: center;
      color: #2c3e50;
      margin-top: 60px;
    }

    .navbar {
        background-color: #0d6efd;
        color: white !important;

    }

    .nav-link {

        color: white !important;

    }


    .navbar-brand {
        padding-left: 3px;
        color: white !important;
    }

    @import'~bootstrap/dist/css/bootstrap.css';






</style>
