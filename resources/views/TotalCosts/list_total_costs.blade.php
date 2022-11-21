@extends('voyager::master')
@section('content')

<div class="content">
<div class="text-center">
    <div class="text-center">
        <h1>COSTO DE VENTAS</h1>
    </div>
    <br>
        <form action="filter" method="POST" class="form-row mb-4">
            @csrf
            <div class="col">
                <h4>Filtrar por mes</h4>
                <label for="fecha">Desde</label>
                <input type="month" name="filter1" value="filter1" required>
                <label for="fecha">Hasta</label>
                <input type="month" name="filter2" value="filter2" required>
                <div>
                    <button type="submit" class="btn btn-success">Filtrar</button>
                    <a type="submit"  name="para" value="mamá"></a>
                </div>
            </div>
        </form>
    <br>
    <table class="table" style="width: 100%; border: 1px solid #000; margin: 0 0 1em 1em">
        <thead style=" background:#FF0000">
            <tr>
                <th scope="col" style=" background:#E62E2D; color:aliceblue">CONCEPTO</th>
                @foreach($mes as $info)
                <th scope="col" style=" background:#E62E2D; color:aliceblue">{{$info['mes']}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
            $p = 0;
            @endphp
            @foreach($headers as $head)
            <tr>
                @if($head == 'TOTAL PRODUCTO TERMINADO' || $head == 'TOTAL OTROS' || $head == 'TOTAL COSTOS DE VENTAS' || $head == 'UTILIDAD BRUTA' || $mes == 'TRIMESTRE')
                    <th scope="row" style=" background:#E62E2D;color:aliceblue">{{$head}}</th>
                @else
                    <th scope="row" style=" background:#E62E2D;color:black">{{$head}}</th>
                @endif
                @foreach($dates as $info)

                @if($p<=$contador)
                        @if($head == 'TOTAL PRODUCTO TERMINADO' || $head == 'TOTAL OTROS' || $head == 'TOTAL COSTOS DE VENTAS' || $head == 'UTILIDAD BRUTA' || $mes == 'TRIMESTRE')
                            @if(is_string($info[$p]))==false) 
                            <td>{{$info[$p]}}</td>
                            @else 
                            <td style=" background:#E62E2D;color:aliceblue">{{'$'.number_format($info[$p])}}</td>
                            @endif
                        @else
                            @if(is_int($info[$p])==false) 
                            <td  style="color:#000">{{$info[$p]}}</td>
                            @else 
                            <td  style="color:#000">{{'$'.number_format($info[$p])}}</td>
                            @endif
                        @endif
                @endif
                @endforeach
                @php
                $p++;
                @endphp
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>

@stop