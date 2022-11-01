@extends('voyager::master')
@section('content')
<div class="content" >
    <h1> VENTAS TOTALES</h1>
    
    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">MES EJECUTADO</th>
                <th scope="col">ACEITES</th>
                <th scope="col">MARGARINAS</th>
                <th scope="col">SOLIDOS Y CREMOSOS</th>
                <th scope="col">TOTAL PRODUCTO TERMINADO</th>
                <th scope="col">INDUSTRIALES</th>
                <th scope="col">OTROS (AGL-ACIDULADO)</th>
                <th scope="col">SERVICIO DE MAQUILA</th>
                <th scope="col">TOTAL OTROS</th>
                <th scope="col">TOTAL VENTAS</th>
            </tr>
        </thead>
        @foreach ($dates as $info)
        @php
            $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES);
            $monthName = $dateObject->format('F');
            $infoACEITES=  number_format($info->ACEITES,0);
            $infoMARGARINAS=  number_format($info->MARGARINAS,0);
            $infoSOLIDOS_CREMOSOS=  number_format($info->SOLIDOS_CREMOSOS,0);
            $infoINDUSTRIALES=  number_format($info->INDUSTRIALES,0);
            $infoOTROS=  number_format($info->OTROS,0);
            $infoSERVICIO_MAQUILA=  number_format($info->SERVICIO_MAQUILA,0);
            $TOTALP = intval($infoACEITES)+intval($infoMARGARINAS)+intval($infoSOLIDOS_CREMOSOS); 
            dd($TOTALP);
        @endphp
        <tbody>
            <tr>
                <th scope="row">{{$monthName}}</th>
                <td>{{$infoACEITES}}</td>
                <td>{{$infoMARGARINAS}}</td>
                <td>{{$infoSOLIDOS_CREMOSOS}}</td>
                <td>{{TOTALP}}</td>
                <td>{{$infoINDUSTRIALES}}</td>
                <td>{{$infoOTROS}}</td>
                <td>{{$infoSERVICIO_MAQUILA}}</td>
                <td>TOTAL O</td>
                <td>TOTAL V</td>
            </tr>
        </tbody>
        @endforeach
    </table>
</div>





@stop