<template>
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Registro
             </div>
            <div class="card-body">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" v-if="Object.entries(errors).length>0">
                    <div :class="classAlert" role="alert" style="font-size: 14px;">
                        <p v-if="errors">
                            <b>Por favor corrige los siguientes errores:</b>
                            <ul>
                                <li v-for="value in errors">
                                    {{ value[0] }}
                                </li>
                            </ul>
                        </p>
                    </div>

                </div>
                <form autocomplete="off" @submit.prevent="register" method="post">
                    <div class="form-group" >
                        <label for="name"><b>Nombre</b></label>
                        <input type="text" id="name" class="form-control" v-model="user.name" required>

                    </div>
                    <div class="form-group">
                        <label for="email"><b>E-mail</b></label>
                        <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="user.email" required>

                    </div>
                    <div class="form-group">
                        <label for="password"><b>Contraseña</b></label>
                        <input type="password" id="password" class="form-control" v-model="user.password"  placeholder="******" min="6" required>

                    </div>
                    <div class="form-group">
                        <label for="confirmedPassword"><b>Confirmar contraseña</b></label>
                        <input type="password" id="confirmedPassword" class="form-control" v-model="user.confirmedPassword"  placeholder="******" min="6">

                    </div>
                    <button type="submit" class="btn btn-success" v-if="user.confirmedPassword == user.password">Guardar</button>
                </form>
            </div>
        </div>
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
                password:"",
                confirmedPassword:""
            },
            loading:true,
            classAlert:"",
            message:"",
            alert:false,
            errors:[]

        }
    },

  methods: {
    register() {
      axios
        .post("/api/v1/register",this.user)
        .then((response) => {

            this.loading = false;
            this.alert= true;
            this.message = response.data.message;
            this.classAlert = response.data.classAlert;
            this.$router.push("/login");


            })
            .catch((error) => {
            this.errors = error.response.data.data;
            this.loading = false;
            this.alert= true;
            this.message = error.response.data.message;
            this.classAlert = error.response.data.class;
            console.log(error);
        });
    },

  },

}
</script>
<style scoped>

button{
    display: block;
    margin-top: 20px;

}

</style>
