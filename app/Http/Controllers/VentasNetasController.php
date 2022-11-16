<?php

namespace App\Http\Controllers;

use App\Models\Total_sale;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;

class VentasNetasController extends Controller
{
    public function total_sales(Request $request)
    {
        try {
            //dd($request);
            if($request->filter1 != null){
                $fechaIni = $request->filter1.'-1';
                $fechaFin = $request->filter2.'-1';
                //dd($fechaFin);
                $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->whereBetween('INF_D_FECHAS',[$fechaIni,$fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
                $infoSales= $infoSales->toArray();
            }else{
                $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
                $infoSales->toArray();
                $infoSales = $infoSales->toArray();
            }
            $formates = [];
            $cabeceras = ['ACEITES', 'MARGARINAS', 'SOLIDOS_CREMOSOS', 'TOTAL PRODUCTO TERMINADO', 'INDUSTRIALES', 'OTROS(AGL-ACIDULADO)', 'SERVICIO DE MAQUILA', 'TOTAL OTROS', 'TOTAL VENTAS'];
            //dd(intval(count($infoSales)/3));
            $mes = [];
                $i=1;
                foreach ($infoSales as $info) {
                    if($i == 3 || $i == 6 || $i == 9 || $i == 12 ){

                        $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
                        $infoACEITES =  round($info->ACEITES, 3);
                        $infoMARGARINAS =  round($info->MARGARINAS, 2);
                        $infoSOLIDOS_CREMOSOS =  round($info->SOLIDOS_CREMOSOS, 3);
                        $infoINDUSTRIALES =  round($info->INDUSTRIALES, 3);
                        $infoOTROS =  round($info->ACIDOS_GRASOS_ACIDULADO, 3);
                        $infoSERVICIO_MAQUILA =  round($info->SERVICIO_MAQUILA, 3);
                        $TOTALP = $infoACEITES + $infoMARGARINAS + $infoSOLIDOS_CREMOSOS;
                        $TOTALO = $infoINDUSTRIALES + $infoOTROS + $infoSERVICIO_MAQUILA;
                        $TOTALV = $TOTALP + $TOTALO;
                        array_push($formates, [$infoACEITES, $infoMARGARINAS, intval(round($infoSOLIDOS_CREMOSOS)), $TOTALP, intval(round($infoINDUSTRIALES)), intval(round($infoOTROS)), intval(round($infoSERVICIO_MAQUILA)), $TOTALO, $TOTALV]);
                        array_push($mes, ['mes' => $dateObject]);
                        //array_push($mes, ['mes' => 'TRIMESTRE']);
                        $i++;
                    }else{
                        $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
                        $infoACEITES =  round($info->ACEITES, 3);
                        $infoMARGARINAS =  round($info->MARGARINAS, 2);
                        $infoSOLIDOS_CREMOSOS =  round($info->SOLIDOS_CREMOSOS, 3);
                        $infoINDUSTRIALES =  round($info->INDUSTRIALES, 3);
                        $infoOTROS =  round($info->ACIDOS_GRASOS_ACIDULADO, 3);
                        $infoSERVICIO_MAQUILA =  round($info->SERVICIO_MAQUILA, 3);
                        $TOTALP = $infoACEITES + $infoMARGARINAS + $infoSOLIDOS_CREMOSOS;
                        $TOTALO = $infoINDUSTRIALES + $infoOTROS + $infoSERVICIO_MAQUILA;
                        $TOTALV = $TOTALP + $TOTALO;
                        array_push($formates, [$infoACEITES, $infoMARGARINAS, intval(round($infoSOLIDOS_CREMOSOS)), $TOTALP, intval(round($infoINDUSTRIALES)), intval(round($infoOTROS)), intval(round($infoSERVICIO_MAQUILA)), $TOTALO, $TOTALV]);
                        array_push($mes, ['mes' => $dateObject]);
                        $i++;
                    }
                }
            array_push($mes, ['mes' => 'ACUMULADO']);
            array_push($mes, ['mes' => 'PROMEDIO']);
            $prmediosOperados=[];
            foreach ($infoSales as $promedio) {
                $infoACEITESP =  round($promedio->ACEITES, 3);
                $infoMARGARINASP =  round($promedio->MARGARINAS, 3);
                $infoSOLIDOS_CREMOSOSP =  round($promedio->SOLIDOS_CREMOSOS, 3);
                $infoINDUSTRIALESP =  round($promedio->INDUSTRIALES, 3);
                $infoOTROSP =  round($promedio->ACIDOS_GRASOS_ACIDULADO, 3);
                $infoSERVICIO_MAQUILAP =  round($promedio->SERVICIO_MAQUILA, 3);
                $TOTALPP = $infoACEITESP + $infoMARGARINASP + $infoSOLIDOS_CREMOSOSP;
                $TOTALOP = $infoINDUSTRIALESP + $infoOTROSP + $infoSERVICIO_MAQUILAP;
                $TOTALVP = $TOTALPP + $TOTALOP;
                array_push($prmediosOperados, [$infoACEITESP, $infoMARGARINASP, $infoSOLIDOS_CREMOSOSP, $TOTALPP, $infoINDUSTRIALESP, $infoOTROSP, $infoSERVICIO_MAQUILAP, $TOTALOP, $TOTALVP]);
            }
            $promedios = [];
            $sumatorias = [];
            for ($i = 0; $i < count($prmediosOperados[0]); $i++) {
                $suma = 0;
                foreach ($prmediosOperados as $prom) {
                    $suma += $prom[$i];
                }
                array_push($promedios, [intval(round($suma / count($formates)))]);
                array_push($sumatorias, [intval(round($suma))]);
            }
            array_push($formates,$sumatorias);
            array_push($formates,$promedios);

            //dd($formates);


            $form = 0;
            foreach ($formates as $form) {
                $form = count($form);
            }
            return view('SalesTotal/list_sales_total', ['dates' => $formates, 'headers' => $cabeceras, 'mes' => $mes, 'contador' => $form]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }



    public function unit_sales(Request $request)
    {
        if($request->filter1 != null){
            $fechaIni = $request->filter1.'-1';
            $fechaFin = $request->filter2.'-1';
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->whereBetween('INF_D_FECHAS',[$fechaIni,$fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoSales= $infoSales->toArray();
            $infoTons = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->whereBetween('INF_D_FECHAS',[$fechaIni,$fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoTons= $infoTons->toArray();
        }else{
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoSales = $infoSales->toArray();
            $infoTons = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS', 'asc')->get();            
            $infoTons= $infoTons->toArray();
        }
        $headers = [
            'ACEITES', 'MARGARINAS', 'SOLIDOS Y CREMOSOS', 'TOTAL PRODUCTO TERMINADO', 'INDUSTRIALES', 'OTROS (AGL-ACIDULADO)', 'SERVICIO DE MAQUILA', 'TOTAL VENTAS'
        ];
        $meses = [];
        $infos = [];
        $infoTs = [];
        $dates = [];
        //informacion de vista ventas netas
        foreach ($infoSales as $infoD) {
            $aceiteUnit = round($infoD->ACEITES, 2);
            $margaUnit = round($infoD->MARGARINAS, 2);
            $solCrUnit = round($infoD->SOLIDOS_CREMOSOS, 2);
            $totlPUnit = $aceiteUnit + $margaUnit + $solCrUnit;
            $indusUnit = round($infoD->INDUSTRIALES, 2);
            $aglAcid = round($infoD->ACIDOS_GRASOS_ACIDULADO, 2);
            $servMaq = round($infoD->SERVICIO_MAQUILA, 2);
            $totTBL = round(($totlPUnit + $indusUnit + $aglAcid + $servMaq) - $servMaq);
            $dateObject = DateTime::createFromFormat('m', $infoD->INF_D_MES)->format('F');
            array_push($infos, [$aceiteUnit, $margaUnit, $solCrUnit, $totlPUnit, $indusUnit, $aglAcid, $servMaq, $totTBL, $dateObject]);
        }
        //fin informacion
        //informacion de vista ventas toneladas
        foreach ($infoTons as $infoT) {
            $aceiUnit2 = round($infoT->TON_ACEITES, 2);
            $margaUnit2 = round($infoT->TON_MARGARINAS, 2);
            $solCrUnit2 = round($infoT->TON_SOLIDOS_CREMOSOS, 2);
            $totlPTUnit2 = $aceiUnit2 + $margaUnit2 + $solCrUnit2;
            $indusUnit2 = round($infoT->TON_INDUSTRIALES_OLEO, 2);
            $agAcid2 = round($infoT->TON_ACIDOS_GRASOS_ACIDULADO, 2);
            $servMaq2 = round($infoT->TON_SERVICIO_MAQUILA, 2);
            $totlVen2 = intval($totlPTUnit2) + intval($infoT->TON_INDUSTRIALES_OLEO) + intval(round($infoT->TON_ACIDOS_GRASOS_ACIDULADO));
            array_push($infoTs, [$aceiUnit2, $margaUnit2, $solCrUnit2, $totlPTUnit2, $indusUnit2, $agAcid2, $servMaq2, $totlVen2]);
        }
        //fin de informacion
        //contador de posiciones en arreglo de registros
        $amount = count($infoTs);
        //fin contador

        //repetidor para arreglo de informacion de vista
        for ($i = 0; $i < $amount; $i++) {
            $divAceit = $infos[$i][0] / $infoTs[$i][0];
            $divMarga = $infos[$i][1] / $infoTs[$i][1];
            $divSolCr = $infos[$i][2] / $infoTs[$i][2];
            $divTotlPt = $infos[$i][3] / $infoTs[$i][3];
            $divIndus = $infos[$i][4] / $infoTs[$i][4];
            $divAgAc = $infos[$i][5] / $infoTs[$i][5];
            $divServMaq = $infos[$i][6] / $infoTs[$i][6];
            $divTotlVen = $infos[$i][7] / $infoTs[$i][7];
            $mes = $infos[$i][8];

            array_push($dates, [
                intval($divAceit), intval(round($divMarga)), intval(round($divSolCr)), intval(round($divTotlPt)),
                intval(round($divIndus)), intval(round($divAgAc)), intval(round($divServMaq)), intval(round($divTotlVen))
            ]);
            array_push($meses, ['mes' => $mes]);
        }
            array_push($meses, ['mes' => 'ACUMULADO']);
            array_push($meses, ['mes' => 'PROMEDIO']);
            $sumatoriasV=[];
            foreach( $infoSales as $sums){
                $aceites =  round($sums->ACEITES, 5);
                $margarinas =  round($sums->MARGARINAS, 5);
                $solidCre =  round($sums->SOLIDOS_CREMOSOS, 5);
                $totPrTer= $aceites+$margarinas+$solidCre;
                $industriales =  round($sums->INDUSTRIALES, 5);
                $otros =  round($sums->ACIDOS_GRASOS_ACIDULADO, 5);
                $serviMaqui =  round($sums->SERVICIO_MAQUILA, 5);
                $totVen= $totPrTer+$industriales+$otros+$serviMaqui;
                array_push($sumatoriasV,[$aceites,$margarinas,$solidCre,$totPrTer,$industriales,$otros,$serviMaqui,$totVen]);
            }
            $sumatoriasT=[];
            foreach( $infoTons as $sumst){
                $aceites =  round($sumst->TON_ACEITES, 5);
                $margarinas =  round($sumst->TON_MARGARINAS, 5);
                $solidCre =  round($sumst->TON_SOLIDOS_CREMOSOS, 5);
                $totPt= $aceites+$margarinas+$solidCre;
                $industriales =  round($sumst->TON_INDUSTRIALES_OLEO, 5);
                $otros =  round($sumst->TON_ACIDOS_GRASOS_ACIDULADO, 5);
                $serviMaqui =  round($sumst->TON_SERVICIO_MAQUILA, 5);
                $venTon= $totPt+$industriales+$otros;
                array_push($sumatoriasT,[$aceites,$margarinas,$solidCre,$totPt,$industriales,$otros,$serviMaqui,$venTon]);
            }
            $sumsV=[];
            $promV=[];
            for ($i = 0; $i < (count($sumatoriasV[0])); $i++) {
            $suma = 0;
                foreach ($sumatoriasV as $sum) {
                $suma += $sum[$i];
                }
                $sumaV= $suma;
                array_push($sumsV, intval(round($sumaV)));
                array_push($promV, intval(round($sumaV))/count($infoSales));
            };

            $sumsT=[];
            $promT=[];
            for ($i = 0; $i < count($sumatoriasT[0]); $i++) {
            $suma = 0;
                foreach ($sumatoriasT as $sum) {
                $suma += $sum[$i];
                }
                $sumaT= $suma;
                array_push($sumsT, intval(round($sumaT)));
                array_push($promT, intval(round($sumaT))/count($infoSales));
            };


            $acumulado=[];
            for($i=0;$i<(count($sumsT)-1);$i++){
                array_push($acumulado,$sumsV[$i]/$sumsT[$i]);
            }
            array_push($acumulado,($sumsV[7]-$sumsV[6])/$sumsT[7]);
            array_push($dates,$acumulado);
            
            $promedio=[];
            for($i=0;$i<(count($promV)-1);$i++){
                array_push($promedio,$promV[$i]/$promT[$i]);
            }
            array_push($promedio,($promV[7]-$promV[6])/$promT[7]);
            array_push($dates,$promedio);

        $form = 0;
        foreach ($dates as $form) {
            $form = count($form);
        }
            return view('SalesTotal\list_total_sales_unit', ['headers' => $headers, 'dates' => $dates, 'mes' => $meses, 'contador' => $form]);
        
        } 
    
}
