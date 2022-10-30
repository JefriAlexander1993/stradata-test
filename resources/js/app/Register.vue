<template>
    <div class="register">
        <p>There was an error, unable to complete registration.</p>
        <!-- <div class="alert alert-danger" v-if="error && !success">
            <p>There was an error, unable to complete registration.</p>
        </div>
       <div class="alert alert-success" v-if="success">
            <p>Registration completed. You can now <router-link :to="{name:'login'}">sign in.</router-link></p>
        </div>-->
        <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
            <div class="form-group" v-bind:class="{ 'has-error': error && errors.name }">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" v-model="user.name" required>
                <span class="help-block" v-if="error && errors.name">{{ errors.name }}</span>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': error && errors.email }">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="user.email" required>
                <span class="help-block" v-if="error && errors.email">{{ errors.email }}</span>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': error && errors.password }">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" v-model="user.password" required>
                <span class="help-block" v-if="error && errors.password">{{ errors.password }}</span>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
</template>

<script>

export default {
    name:"Register",
    data () {
        return {

            user:{
                name:"",
                email:"",
                password:""
            },
            success:"",
            errors:[]
        }
    },

  methods: {
    register() {
      axios
        .get("https://restcountries.com/api/v1/register")
        .then((response) => {
                console.log();
        })
        .catch((error) => {
          console.log(error);
        });
    },

  },

}
</script>
<style scoped>
.register{

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
