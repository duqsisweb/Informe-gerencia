<?php
namespace App\Http\Traits;

use DateTime;
use Illuminate\Support\Facades\DB;

trait VentasToneladasTrait {
    
    public function TablaVentasToneladas($fechaIni, $fechaFin) {
        if ($fechaIni != null) {
            $fechaIni = $fechaIni . '-1';
            $fechaFin = $fechaFin. '-1';
            $infoTons = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->whereBetween('INF_D_FECHAS', [$fechaIni, $fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoTons = $infoTons->toArray();
        } else {
            $infoTons = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoTons = $infoTons->toArray();
        }
        $infoTons = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS','asc')->get();
        $infoTons= $infoTons->toArray();
        /* $headers=['VENTAS (TONELADAS)', 'ACEITES TONELADAS', 'MARGARINAS TONELADAS', 'SOLIDOS Y CREMOSOS TONELADAS', 'TOTAL PT', 'INDUSTRIALES (OLEOQUIMICOS)', 
            'OTROS (AGL-ACIDULADO)', 'SERVICIO MAQUILA']; */
            $fomDates= [];
            $mes= [];
            foreach($infoTons as $info){
                
                $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
                $aceitesOP= round($info->TON_ACEITES,5);
                $aceites= round($info->TON_ACEITES,5);
                $margarinasOP= round($info->TON_MARGARINAS,5);
                $margarinas= round($info->TON_MARGARINAS,5);
                $soliCremOP= round($info->TON_SOLIDOS_CREMOSOS,5);
                $soliCrem= round($info->TON_SOLIDOS_CREMOSOS,5);
                $totNrm= $aceites+$margarinas+$soliCrem;
                $totPt= $aceitesOP+$margarinasOP+$soliCremOP;
                $tonIndOl= round($info->TON_INDUSTRIALES_OLEO,5);
                $acGrAccid= round($info->TON_ACIDOS_GRASOS_ACIDULADO,5);
                $servMaqu= round($info->TON_SERVICIO_MAQUILA,5);
                $ventTon= $totPt+$tonIndOl+$acGrAccid;
             
                array_push($fomDates,[$ventTon,intval($aceites), intval($margarinas),intval($soliCrem), $totPt, $tonIndOl,$acGrAccid,
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
                $tonIndOlT= round($ton->TON_INDUSTRIALES_OLEO,5);
                $acGrAccidT= round($ton->TON_ACIDOS_GRASOS_ACIDULADO,5);
                $servMaquT= round($ton->TON_SERVICIO_MAQUILA,5);
                $ventt= $aceitesT+$margarinasT+$soliCremT;
                $ttot= $ventt+$tonIndOlT+$acGrAccidT;
                array_push($sumados,[$aceitesT,$margarinasT,$soliCremT,$tonIndOlT,$acGrAccidT,$servMaquT,$ttot,$ventt]);
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
               array_push($acumulados,$sumatorias[6]); array_push($acumulados,$sumatorias[0]);
               array_push($acumulados,$sumatorias[1]);array_push($acumulados,$sumatorias[2]);
               array_push($acumulados,$sumatorias[7]);array_push($acumulados,$sumatorias[3]);
               array_push($acumulados,$sumatorias[4]);array_push($acumulados,$sumatorias[5]);
               array_push($fomDates, $acumulados);

            $promedio=[];
            array_push($promedio,round($sumatorias[6]/count($infoTons)));
            for($i=0;$i<3;$i++){
                array_push($promedio,$promedios[$i]);
            }
            array_push($promedio,round($sumatorias[7]/count($infoTons)));
            for($i=3;$i<count($promedios);$i++){
                array_push($promedio,$promedios[$i]);
            }
            array_push($fomDates,$promedio);
            $form = 0;
            foreach($fomDates as $form){
                $form = count($form);
            }
            return $fomDates;
        }
    }