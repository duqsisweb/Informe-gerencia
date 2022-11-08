<?php

namespace App\Http\Controllers;

use App\Models\Total_sale;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;

class VentasNetasController extends Controller
{
    public function total_sales()
    {
        try {
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS','asc')->get();
            $formates = [];
            $cabeceras = ['ACEITES','MARGARINAS','SOLIDOS_CREMOSOS','TOTAL PRODUCTO TERMINADO','INDUSTRIALES','OTROS(AGL-ACIDULADO)','SERVICIO DE MAQUILA','TOTAL OTROS','TOTAL VENTAS'];
            $mes=[];
            foreach($infoSales as $info){
                $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
                $infoACEITES=  round($info->ACEITES,2);
                $infoMARGARINAS=  round($info->MARGARINAS,2);
                $infoSOLIDOS_CREMOSOS=  round($info->SOLIDOS_CREMOSOS,3);
                $infoINDUSTRIALES=  round($info->INDUSTRIALES,3);
                $infoOTROS=  round($info->ACIDOS_GRASOS_ACIDULADO,3);
                $infoSERVICIO_MAQUILA=  round($info->SERVICIO_MAQUILA,3);
                $TOTALP = $infoACEITES+$infoMARGARINAS+$infoSOLIDOS_CREMOSOS;
                $TOTALO = $infoINDUSTRIALES+$infoOTROS+$infoSERVICIO_MAQUILA;
                $TOTALV = $TOTALP+$TOTALO;
                array_push($formates,[$infoACEITES,$infoMARGARINAS,intval(round($infoSOLIDOS_CREMOSOS)),$TOTALP,intval(round($infoINDUSTRIALES)),intval(round($infoOTROS)),intval(round($infoSERVICIO_MAQUILA)),$TOTALO,$TOTALV]);
                array_push($mes,[ 'mes'=>$dateObject]);
            }
            $form = 0;
            foreach($formates as $form){
                $form = count($form);
            }

            return view('SalesTotal/list_sales_total', ['dates'=> $formates, 'headers'=>$cabeceras,'mes'=>$mes,'contador'=>$form]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }



    public function unit_sales(){
        $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS','asc')->get();
        $infoTons = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS','asc')->get();
        $headers= ['ACEITES','MARGARINAS','SOLIDOS Y CREMOSOS','TOTAL PRODUCTO TERMINADO','INDUSTRIALES','OTROS (AGL-ACIDULADO)'
                ,'SERVICIO DE MAQUILA','TOTAL VENTAS'];
        $meses=[];
        $infos=[];
        $infoTs=[];
        $dates=[];
        //informacion de vista ventas netas
        foreach($infoSales as $infoD){
            $aceiteUnit= round($infoD->ACEITES,2);
            $margaUnit= round($infoD->MARGARINAS,2);
            $solCrUnit= round($infoD->SOLIDOS_CREMOSOS,2);
            $totlPUnit= $aceiteUnit+$margaUnit+$solCrUnit;
            $indusUnit= round($infoD->INDUSTRIALES,2);
            $aglAcid= round($infoD->ACIDOS_GRASOS_ACIDULADO,2);
            $servMaq= round($infoD->SERVICIO_MAQUILA,2);
            $totTBL= round(($totlPUnit+$indusUnit+$aglAcid+$servMaq)-$servMaq);
            $dateObject = DateTime::createFromFormat('m', $infoD->INF_D_MES)->format('F');
            array_push($infos,[$aceiteUnit,$margaUnit,$solCrUnit,$totlPUnit,$indusUnit,$aglAcid,$servMaq,$totTBL,$dateObject]);
        }
        //fin informacion
        //informacion de vista ventas toneladas
        foreach($infoTons as $infoT){
            $aceiUnit2 = round($infoT->TON_ACEITES,2);
            $margaUnit2 = round($infoT->TON_MARGARINAS,2);
            $solCrUnit2 = round($infoT->TON_SOLIDOS_CREMOSOS,2);
            $totlPTUnit2 = $aceiUnit2+$margaUnit2+$solCrUnit2;
            $indusUnit2 = round($infoT->TON_INDUSTRIALES_OLEO,2);
            $agAcid2 = round($infoT->TON_ACIDOS_GRASOS_ACIDULADO,2);
            $servMaq2 = round($infoT->TON_SERVICIO_MAQUILA,2);
            $totlVen2 = intval($totlPTUnit2)+intval($infoT->TON_INDUSTRIALES_OLEO)+intval(round($infoT->TON_ACIDOS_GRASOS_ACIDULADO));
            array_push($infoTs,[$aceiUnit2, $margaUnit2,$solCrUnit2,$totlPTUnit2,$indusUnit2,$agAcid2,$servMaq2,$totlVen2]);
        }
        //fin de informacion
        //contador de posiciones en arreglo de registros
        $amount = count($infoTs);
        //fin contador

        //repetidor para arreglo de informacion de vista
        for($i=0;$i<$amount;$i++){
            $divAceit=$infos[$i][0]/$infoTs[$i][0];
            $divMarga=$infos[$i][1]/$infoTs[$i][1];
            $divSolCr=$infos[$i][2]/$infoTs[$i][2];
            $divTotlPt=$infos[$i][3]/$infoTs[$i][3];
            $divIndus=$infos[$i][4]/$infoTs[$i][4];
            $divAgAc=$infos[$i][5]/$infoTs[$i][5];
            $divServMaq=$infos[$i][6]/$infoTs[$i][6];
            $divTotlVen=$infos[$i][7]/$infoTs[$i][7];
            $mes= $infos[$i][8];

            array_push($dates,[intval($divAceit),intval(round($divMarga)),intval(round($divSolCr)),intval(round($divTotlPt)),
            intval(round($divIndus)),intval(round($divAgAc)) ,intval(round($divServMaq)),intval(round($divTotlVen))]);
            array_push($meses,['mes'=>$mes]);
            $form = 0;
            foreach($dates as $form){
                $form = count($form);
            }
        }
        //dd($headers, $dates, $meses, $form);
        //fin del generador del arreglo
        return view('TotalSalesUnit\list_total_sales_unit',['headers'=>$headers, 'dates'=>$dates, 'mes'=>$meses, 'contador'=>$form ]);
   


    }
}




