<?php

namespace App\Exports;

use App\Http\Traits\VentasNetasTrait;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;

class ReportExport implements FromArray
{
    use VentasNetasTrait;
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $dates;
    protected $cabeceras;

    public function __construct($dates,$cabeceras)
    {
        $this->dates = $dates;
        $this->cabeceras = $cabeceras;
    }
   
    public function headings(): array
    {
        return [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
        ];
    }
    public function array(): array
    {
          return [$this->cabeceras,$this->dates];

    }
}