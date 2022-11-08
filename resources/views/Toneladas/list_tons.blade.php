@extends('voyager::master')
@section('content')

<div class="content">
    <div class="text-center">
        <h1>TONELADAS 2022</h1>
    </div>
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
            $r=0;
            @endphp
            @foreach($headers as $head)
            <tr>
                @if($head == 'VENTAS (TONELADAS)' || $head == 'TOTAL PT' || $head == 'SERVICIO MAQUILA')
                <th scope="row" style=" background:#E62E2D;color:aliceblue">{{$head}}</th>
                @else
                <th scope="row" style=" background:#E62E2D;color:black">{{$head}}</th>
                @endif
                @foreach($dates as $info)
                    @if($r<=$contador)
                        @if($head == 'VENTAS (TONELADAS)' || $head == 'TOTAL PT' || $head == 'SERVICIO MAQUILA')
                            <td style=" background:#E62E2D;color:aliceblue">{{'$'.number_format($info[$r])}}</td>
                        @else
                        <td>{{'$'.number_format($info[$r])}}</td>
                        @endif
                    @endif
                @endforeach
                @php
                $r++;
                @endphp
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@stop