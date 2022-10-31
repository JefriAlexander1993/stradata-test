
<template>
    <div class="container">
        <div class="card" v-if="this.$store.state.token">
            <div class="card-header primary">
                <fa icon="search" /> Buscador
              </div>
            <div class="card-body">
                <div class="row" v-if="dataFilter.uuid ==''">

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
                    <div class="col-xs-4 col-sm-12 col-md-6 col-lg-4">
                        <label class="label-control"><b>Nombres y apellidos(*)</b> </label>
                        <input class="form-control" v-model="dataFilter.search" placeholder="Jorge Benavidez"/>

                    </div>
                    <div class="col-xs-4 col-sm-12 col-md-6 col-lg-2"><label class="label-control"><b>Porcentaje de concidencia</b></label><input class="form-control" type="number" min="0"  max="100" v-model="dataFilter.percentage" placeholder="75.00"/></div>
                    <div class="col-xs-4 col-sm-12 col-md-4 col-lg-3"><label class="label-control"><b>Acción</b></label><br/><button class="btn btn-primary btn-sm" @click="filterPersonPublic"><fa icon="search" /> Buscar</button> <button class="btn btn-secondary btn-sm" @click="clearFilter"><fa icon="fa-broom" />  Limpiar</button> <!-- <button class="btn btn-success" @click="exportExcel">Export data</button>--></div>

                    <div class="col-xs-4 col-sm-12 col-md-8 col-lg-3 mt-2" v-if="alert">
                        <div :class="classAlert" role="alert" style="font-size: 14px;">
                            {{message}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4 col-sm-12 col-md-6 col-lg-4"><label class="label-control"><b>Uuid</b></label><input class="form-control" type="text" v-model="dataFilter.uuid" placeholder="5f5c5087-5999-4380-aaba-a0a3cb387fd0"/></div>
                    <div class="col-xs-4 col-sm-12 col-md-6 col-lg-4"><br/><button class="btn btn-success btn-sm mt-1" @click="filterPersonPublicPrevious"><fa icon="fa-search" /> Buscar por uuid</button> <button class="btn btn-secondary btn-sm mt-1 ml-5" @click="clearFilter" v-if="dataFilter.uuid"><fa icon="fa-broom" />  Limpiar</button></div>
                </div>

            <div v-if="loading">
                <br/><br/>
                <div class="d-flex justify-content-center text-primary">
                    <div class="spinner-border" role="status text-primary">
                      <span class="sr-only"></span>
                    </div>
                  </div>
                  <br/>
            </div>
            <div class="table-responsive" v-else>
                    <table class="table">
                        <caption>{{this.personPublics.length>0 ? "Ciudadanos":"Sin datos"}}</caption>
                        <thead>
                        <tr>
                            <th>Uuid</th>
                            <th>Nombre</th>
                            <th>Tipo de persona</th>
                            <th>Tipo cargo</th>
                            <th>Departamento</th>
                            <th>Municipio</th>
                            <th>Considencia</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr   v-for="(item, index) in personPublics" v-bind:key="index"
                             :style="item.porcentage == 100?'background-color: #198754;color: white !important':''">
                             <td>{{item.uuid}}</td>
                            <td>{{item.name}}</td>
                            <td>{{item.person_type}}</td>
                            <td>{{item.type_of_load}}</td>
                            <td>{{item.department}}</td>
                            <td>{{item.municipality}}</td>
                            <td><b>{{item.porcentage}}%</b></td>
                        </tr>
                        </tbody>
                    </table>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default{
    name:"Register",
    data () {
        return {

            loading:false,
            file:null,
            personPublics:[],
            dataFilter:{
                search:"",
                percentage:"",
                uuid:""
            },
            message:"",
            alert:false,
            classAlert:"",
            errors:{}
        }
    },
    mounted(){
        if (this.$store.state.token !== '') {
            axios
            .post("/api/v1/checkToken",{token:this.$store.state.token})
            .then((response) => {
                if(response.data.error ==false){
                    this.loading = false;
                    this.$router.push("/dashboard");

                }else{
                    this.$store.commit('setToken',response.data.token);
                }
        })
        .catch((error) => {
          this.loading = false;
          this.$router.push("/login");
        });
        }else{
            this.loading = false;
            this.$router.push("/login");
        }
    },

    methods: {
        uploadFile() {

            this.file = this.$refs.uploadfile.files[0];
            console.log('this.file ',this.file );
        },
        filterPersonPublicPrevious(e){
            e.preventDefault();

            axios
                .post("/api/v1/filter-person-public-previous",{uuid:this.dataFilter.uuid,token:this.$store.state.token})
                .then((response) => {
                    this.personPublics =JSON.parse(response.data.data.data)

                })
                .catch((error) => {
                console.log(error);
                });
        },
        filterPersonPublic(){
                this.errors ={};
                this.loading = true;
                axios
                    .post("/api/v1/filter-person-public",{token:this.$store.state.token, data:this.dataFilter})
                    .then((response) => {
                        this.alert= true;
                        this.message =response.data.execution_status;
                        this.classAlert=response.data.class;
                        this.personPublics =
                        response.data.data.sort(function (a, b){
                            return (b.porcentage - a.porcentage)
                        })
                        localStorage.removeItem('data');
                        localStorage.setItem('data', JSON.stringify(this.personPublics));
                        this.loading = false;
                    })
                    .catch((error) => {

                        this.alert= true;
                        this.loading= false;
                        this.classAlert= error.response.data.class;
                        this.message = error.response.data.execution_status;
                        this.errors = error.response.data.data;
                        console.log("this.errors ",this.errors );
                    });
                // }
        },
        clearFilter(){

            this.dataFilter.search ="";
            this.dataFilter.percentage = "";
            this.personPublics =[] ;
            this.alert = false;
            this.errors = [];
            this.dataFilter.uuid = "";


        }
    },
}
</script>
<style scoped>
.card-header {
        background-color: #0d6efd;
        color: white !important;

}
.label-control{
    font-size: 13px;
}

</style>
