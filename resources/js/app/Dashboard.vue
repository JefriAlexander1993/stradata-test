<template>
    <div class="container">
        <div class="card" v-if="this.$store.state.token">

            <!-- <button class="btn btn-primary" @click="logout">Logout</button> -->

            <div class="card-header primary">
                Buscador
              </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-4 col-sm-12 col-md-6 col-lg-3"><label><b>Nombres y apellidos</b></label><input class="form-control" v-model="dataFilter.search"/></div>
                    <div class="col-xs-4 col-sm-12 col-md-6 col-lg-3"><label><b>Porcentaje de concidencia</b></label><input class="form-control" v-model="dataFilter.percentage"/></div>
                    <div class="col-xs-4 col-sm-12 col-md-4 col-lg-3"><label><b>Acción</b></label><br/><button class="btn btn-secondary" @click="clearFilter">Limpiar</button> <button class="btn btn-primary" @click="filterPersonPublic">Buscar</button><!-- <button class="btn btn-success" @click="exportExcel">Export data</button>--></div>

                    <div class="col-xs-4 col-sm-12 col-md-8 col-lg-3 mt-2" v-if="alert">
                        <div :class="classAlert" role="alert" style="font-size: 14px;">
                            {{message}}
                        </div>
                    </div>
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
                percentage:""
            },
            message:"",
            alert:false,
            classAlert:""
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
        filterPersonPublic(){
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
                    this.classAlert=error.data.class;
                console.log(error);
                });
        },
        logout() {
            axios
                .post("/api/v1/logout",{token:this.$store.state.token})
                .then((response) => {

                        this.$store.commit('clearToken');
                        this.$router.push('/login');

                })
                .catch((error) => {
                console.log(error);
                });
        },
        exportExcel(){

            var data =localStorage.getItem('data');

            axios
                .post("/api/v1/file-export",{token:this.$store.state.token,data:data},{
                        headers:{
                            responseType: 'blob',
                        }
                })
                .then((response) => {
                    console.log(response);
                    let blob = new Blob([response.data.data], { type: 'application/vnd.ms-excel' })
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'comisionjv.xlsx'
                    link.click()

                })
                .catch((error) => {
                console.log(error);
                });
        },
        clearFilter(){
            this.dataFilter.search ="";
            this.dataFilter.porcentage = "";
            this.personPublics =[] ;
            this.alert = false;


        }
    },
}
</script>
<style scoped>
.card-header {
        background-color: #0d6efd;
        color: white !important;

}

</style>
