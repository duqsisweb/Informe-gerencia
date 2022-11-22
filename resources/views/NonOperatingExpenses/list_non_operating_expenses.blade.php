@extends('voyager::master')
@section('content')

<div class="content">
    <div class="text-center">
        <div class="text-center">
            <h1>GASTOS NO OPERACIONALES 2022</h1>
        </div>
        <br>
        <div>
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
                        <a type="submit" name="para" value="mamá"></a>
                    </div>
                </div>
            </form>
            <form action="filter" method="POST" class="form-row mb-4">
                @csrf
                <div class="col">
                    <div>
                        <button type="submit" class="btn btn-danger">Limpiar-Filtro</button>
                        <a type="submit" name="para" value="mamá"></a>
                    </div>
                </div>
            </form>
        </div>
        <br>
    </div>
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
                @if($head == 'TOTAL NO OPERACIONALES' || $head == 'UTILIDAD ANTES DE IMPUESTOS' || $head == 'EBITDA' )
                <th scope="row" style=" background:#E62E2D;color:aliceblue">{{$head}}</th>
                @else
                <th scope="row" style=" background:#E62E2D;color:black">{{$head}}</th>
                @endif
                @foreach($dates as $info)
                @if($p<=$contador) @if($head=='TOTAL NO OPERACIONALES' || $head=='UTILIDAD ANTES DE IMPUESTOS' || $head=='EBITDA' ) @if(is_string($info[$p])==true) <td>{{$info[$p]}}</td>
                    @else
                    <td style=" background:#E62E2D;color:aliceblue">{{'$'.number_format($info[$p])}}</td>
                    @endif
                    @else
                    @if(is_string($info[$p])==true)
                    <td>{{$info[$p]}}</td>
                    @else
                    <td style="color:black">{{'$'.number_format($info[$p])}}</td>
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

@stop