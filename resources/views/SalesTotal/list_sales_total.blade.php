@extends('voyager::master')
@section('content')

<div class="content">
    <div class="text-center">
        <h1>VENTAS TOTALES</h1>
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
                <th scope="row" style=" background:#E62E2D;color:aliceblue">{{$head}}</th>
                @foreach($dates as $info)
                    @if($r<=$contador)
                        <td>{{'$'.number_format($info[$r])}}</td>
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