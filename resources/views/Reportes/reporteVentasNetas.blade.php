@extends('voyager::master')
@section('content')

<div class="content">
    <div class="text-center">
        <h1>REPORTE VENTAS TOTALES</h1>
        <br>
        <form action="download" method="POST" class="form-row mb-4">
            @csrf
            <div class="col">
                <h4>Filtrar por mes</h4>
                <label for="fecha">Desde</label>
                <input type="month" name="filter1" value="filter1" required>
                <label for="fecha">Hasta</label>
                <input type="month" name="filter2" value="filter2" required>
                <div>
                    <button type="submit" class="btn btn-success">Descargar</button>
                    <a type="submit"  name="para" value="mamá"></a>
                </div>
            </div>
        </form>
    </div>
    <br>
</div>

@stop