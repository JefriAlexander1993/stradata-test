
<template>
    <div>
        <div v-if="loading">
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div>
        </div>
        <div class="login" v-else="">
            <!-- <p>There was an error, unable to sign in with those credentials.</p> -->
            <!-- <div class="alert alert-danger" v-if="error">
                <p>There was an error, unable to sign in with those credentials.</p>
            </div> -->

                <form autocomplete="off" @submit.prevent="login" method="post">
                    <div class="form-group">
                        <label for="email">E-mail</label>
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
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            class="form-control"
                            v-model="user.password"
                            required
                        />
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Sign in</button>
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
            loading:true
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
        }
    },
  methods: {
    login() {
      axios
        .post("/api/v1/login",this.user)
        .then((response) => {
            if (response.data.success) {
                this.$store.commit('setToken',response.data.token);
                this.$router.push("/dashboard");
            }

        })
        .catch((error) => {
          console.log(error);
        });
    },

  },

}
</script>
<style scoped>
.login{

    background-color: #fff;
    border:1px solid #EEE;
    padding: 10px;
    margin: 50px auto;
    width: 500px;

}
button{
    display: block;
    margin-top: 20px;

}

</style>
