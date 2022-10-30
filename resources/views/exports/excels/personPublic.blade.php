<table class="table">
    <caption>{{count($personPublics)>0 ? "Ciudadanos":"Sin datos"}}</caption>
    <thead>
    <tr>
        <th idth="170px" style="text-align:center">Nombre</th>
        <th idth="170px" style="text-align:center">Tipo de persona</th>
        <th idth="170px" style="text-align:center">Tipo cargo</th>
        <th idth="170px" style="text-align:center">Departamento</th>
        <th idth="170px" style="text-align:center">Municipio</th>
        <th idth="170px" style="text-align:center">Considencia</th>
    </tr>
    </thead>
    <tbody>

        @foreach ($personPublics as $personPublic)
            <tr >
                <td>{{$personPublic->name}}</td>
                <td>{{$personPublic->person_type}}</td>
                <td>{{$personPublic->type_of_load}}</td>
                <td>{{$personPublic->department}}</td>
                <td>{{$personPublic->municipality}}</td>
                <td><b>{{$personPublic->porcentage}}%</b></td>
            </tr>
        @endforeach
        {{-- v-for="(item, index) in personPublics" v-bind:key="index"
        :style="item.porcentage == 100?'background-color: #198754;color: white !important':''" --}}

    </tbody>
</table>
