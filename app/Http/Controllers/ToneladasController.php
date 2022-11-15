<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ToneladasController extends Controller
{
    public function tons(){
        $infoTons = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS','asc')->get();
        $infoTons= $infoTons->toArray();
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
            array_push($mes, ['mes' => 'ACUMULADO']);
            array_push($mes, ['mes' => 'PROMEDIO']);
            $sumados=[];
            foreach($infoTons as $ton){
                $aceitesT= round($ton->TON_ACEITES,5);
                $margarinasT= round($ton->TON_MARGARINAS,5);
                $soliCremT= round($ton->TON_SOLIDOS_CREMOSOS,5);
                $tonIndOlT= round($ton->TON_INDUSTRIALES_OLEO);
                $acGrAccidT= round($ton->TON_ACIDOS_GRASOS_ACIDULADO);
                $servMaquT= round($ton->TON_SERVICIO_MAQUILA);
                array_push($sumados,[$aceitesT,$margarinasT,$soliCremT,$tonIndOlT,$acGrAccidT,$servMaquT]);
        }

            $sumatorias = [];
            $promedios=[];
            for ($i = 0; $i < count($sumados[0]); $i++) {
            $suma = 0;
                foreach ($sumados as $sum) {
                $suma += $sum[$i];
                }
                array_push($sumatorias, intval(round($suma)));
                array_push($promedios, intval(round($suma / count($infoTons))));
            }

            $acumulados=[]; 
                $totalPTT =  $sumatorias[0]+$sumatorias[1]+$sumatorias[2];
                $ventTonels=  $totalPTT+$sumatorias[3]+$sumatorias[4];                      
               array_push($acumulados,$totalPTT); array_push($acumulados,$sumatorias[0]);
               array_push($acumulados,$sumatorias[1]);array_push($acumulados,$sumatorias[2]);
               array_push($acumulados,$ventTonels);array_push($acumulados,$sumatorias[3]);
               array_push($acumulados,$sumatorias[4]);array_push($acumulados,$sumatorias[5]);
               array_push($fomDates, $acumulados);

            $promedio=[];
            array_push($promedio,round($ventTonels/count($infoTons)));
            for($i=0;$i<3;$i++){
                array_push($promedio,$promedios[$i]);
            }
            array_push($promedio,round($totalPTT/count($infoTons)));
            for($i=3;$i<count($promedios);$i++){
                array_push($promedio,$promedios[$i]);
            }
            array_push($fomDates,$promedio);
            $form = 0;
            foreach($fomDates as $form){
                $form = count($form);
            }
            return view('Toneladas\list_tons',['headers'=>$headers, 'dates'=>$fomDates, 'mes'=>$mes, 'contador'=>$form ]);
    }
}
