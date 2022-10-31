
<template>
    <div class="container">
        <div v-if="loading">
            <br/><br/>
            <div class="d-flex justify-content-center text-primary">
                <div class="spinner-border" role="status text-primary">
                  <span class="sr-only"></span>
                </div>
              </div>
              <br/>
        </div>

        <div class="login" v-else="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-2" v-if="alert">
                        <div :class="classAlert" role="alert" style="font-size: 14px;">
                            {{message}}
                        </div>
                    </div>
                </div>
                <form autocomplete="off" @submit.prevent="login" method="post">
                    <div class="form-group">
                        <label for="email"><b>E-mail</b></label>
                        <input
                            type="email"
                            id="email"
                            class="form-control"
                            placeholder="user@example.com"
                            v-model="user.email"
                            required
                        />
                    </div>
                    <div class="form-group">
                        <label for="password"><b>Contraseña</b></label>
                        <input
                            type="password"
                            id="password"
                            class="form-control"
                            v-model="user.password"
                            placeholder="******"
                            required
                        />
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Ingresar</button>
                </form>
        </div>
</div>
</template>
<script>

import axios from "axios";
export default {
    name: 'Login',
    data () {
        return {

            user:{
                email:"",
                password:""
            },
            loading:true,
            classAlert:"",
            message:"",
            alert:false,
        }
    },
    mounted(){
        if (this.$store.state.token !== '') {
            axios
        .post("/api/v1/checkToken",{token:this.$store.state.token})
        .then((response) => {
            if (response) {
                this.loading = false;
                this.$router.push("/dashboard");
            }

        })
        .catch((error) => {
          this.loading = false;
          this.$store.commit('clearToken');
        });
        }else{
            this.loading = false;
            this.$store.state.token = "";
        }
    },
  methods: {
    login() {
        this.loading = true;
      axios
        .post("/api/v1/login",this.user)
        .then((response) => {

                this.loading = false;
                this.alert= true;
                this.message = response.data.message;
                this.classAlert = response.data.classAlert;
                this.$store.commit('setToken',response.data.data[0]);
                this.$store.commit('setUser',response.data.data[1]);
                localStorage.setItem('user', response.data.data[1]);
                this.$store.commit('setAutorized',true);
                this.$router.push("/dashboard");


        })
        .catch((error) => {
            this.loading = false;
            this.alert= true;
            this.message = error.response.data.message;
            this.classAlert = error.response.data.class;

        });
    },

  },

}
</script>
<style scoped>
.login{

    background-color: #fff;
    border:3px solid #EEE;
    padding: 10px;
    margin: 50px auto;
    width: 500px;

}
button{
    display: block;
    margin-top: 10px;

}

</style>
