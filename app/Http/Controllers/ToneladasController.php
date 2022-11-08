<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToneladasController extends Controller
{
    public function tons(){
        $infoTons = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS','asc')->get();
        $headers=['VENTAS (TONELADAS)', 'ACEITES TONELADAS', 'MARGARINAS TONELADAS', 'SOLIDOS Y CREMOSOS TONELADAS', 'TOTAL PT', 'INDUSTRIALES (OLEOQUIMICOS)', 
            'OTROS (AGL-ACIDULADO)', 'SERVICIO MAQUILA'];
            $fomDates= [];
            $mes= [];

            foreach($infoTons as $info){
                
                $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
                $aceitesOP= round($info->TON_ACEITES,2);
                $aceites= round($info->TON_ACEITES);
                $margarinasOP= round($info->TON_MARGARINAS,2);
                $margarinas= round($info->TON_MARGARINAS);
                $soliCremOP= round($info->TON_SOLIDOS_CREMOSOS,2);
                $soliCrem= round($info->TON_SOLIDOS_CREMOSOS);
                $totNrm= $aceites+$margarinas+$soliCrem;
                $totPt= $aceitesOP+$margarinasOP+$soliCremOP;
                $tonIndOl= round($info->TON_INDUSTRIALES_OLEO);
                $acGrAccid= round($info->TON_ACIDOS_GRASOS_ACIDULADO);
                $servMaqu= round($info->TON_SERVICIO_MAQUILA);
                $ventTon= $totPt+$tonIndOl+$acGrAccid;
             
                array_push($fomDates,[$ventTon,intval($aceites), intval($margarinas,0),intval($soliCrem), $totPt, $tonIndOl,$acGrAccid,
                $servMaqu ]);
                array_push($mes,['mes'=>$dateObject]);
            }
            $form = 0;
            foreach($fomDates as $form){
                $form = count($form);
            }
            return view('Toneladas\list_tons',['headers'=>$headers, 'dates'=>$fomDates, 'mes'=>$mes, 'contador'=>$form ]);
    }
}
