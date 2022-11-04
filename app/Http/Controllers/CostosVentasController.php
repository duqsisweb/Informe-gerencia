<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\type;

class CostosVentasController extends Controller
{
    public function total_costs(){
        $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->select('INF_D_MES','ACEITES2','ACEITES','MARGARINAS','MARGARINAS2','SOLIDOS_CREMOSOS','SOLIDOS_CREMOSOS2','INDUSTRIALES2','INDUSTRIALES','ACIDOS_GRASOS_ACIDULADO2','ACIDOS_GRASOS_ACIDULADO','SERVICIO_MAQUILA2','SERVICIO_MAQUILA')->orderBy('INF_D_FECHAS','asc')->get();
        $headers=['ACEITES','PORCENTAJE ACEITES','MARGARINAS','PORCENTAJE MARGARINAS','SOLIDOS Y CREMOSOS','PORCENTAJE SOLIDOS Y CREMOSOS','TOTAL PRODUCTO TERMINADO','PORCENTAJE TOTAL PRODUCTO TERMINADO','INDUSTRIALES','PORCENTAJE INDUSTRIALES','OTROS PRODUCTOS','PORCENTAJE OTROS PRODUCTOS','SERVICIO DE MAQUILA','PORCENTAJE SERVICIO DE MAQUILA','TOTAL OTROS','PORCENTAJE TOTAL OTROS','TOTAL COSTOS DE VENTAS','PORCENTAJE TOTAL COSTOS DE VENTAS','UTILIDAD BRUTA','PORCENTAJE UTILIDAD BRUTA'];  
        $formates=[];
        $mes=[];
        foreach($infoSales as $info){
            $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
            $infoACEITES= intval(round($info->ACEITES2));
            $porceAc = round($info->ACEITES2*100/$info->ACEITES).'%';
            $infoMarga= intval(round($info->MARGARINAS2));
            $porceMarga= round($info->MARGARINAS2*100/$info->MARGARINAS,2).'%';
            $infoSOLID= intval(round($info->SOLIDOS_CREMOSOS2));
            $porceSOLID= round($info->SOLIDOS_CREMOSOS2*100/$info->SOLIDOS_CREMOSOS,2).'%';
            $infoTOTP= intval(round($info->SOLIDOS_CREMOSOS2+$info->MARGARINAS2+$info->ACEITES2));
            $TOTALPT = $info->ACEITES+$info->MARGARINAS+$info->SOLIDOS_CREMOSOS;
            $porceTOTALP= round($infoTOTP*100/$TOTALPT,2).'%';
            $totalProd= intval(round($info->ACEITES,3)+round($info->MARGARINAS,3)+round($info->SOLIDOS_CREMOSOS,3));  
            $TOTALO = intval(round($info->INDUSTRIALES,3))+intval(round($info->ACIDOS_GRASOS_ACIDULADO,3))+intval(round($info->SERVICIO_MAQUILA,3));
            $porceTOTP= round($infoTOTP*100/$totalProd,2).'%';
            $infoINDU= intval(round($info->INDUSTRIALES2));
            $porceINDU= round($info->INDUSTRIALES2*100/round($info->INDUSTRIALES,2),2).'%';
            $infoOTROS= intval(round($info->ACIDOS_GRASOS_ACIDULADO2));
            $porceOTROS= round($info->ACIDOS_GRASOS_ACIDULADO2*100/$info->ACIDOS_GRASOS_ACIDULADO,2).'%';
            $infoSERVM= intval(round($info->SERVICIO_MAQUILA2));
            $porceSERVM= round($info->SERVICIO_MAQUILA2*100/$info->SERVICIO_MAQUILA,2).'%';
            $infoTOLALO= $infoINDU+$infoOTROS+$infoSERVM;
            $TOTALO = intval(round($info->INDUSTRIALES+$info->ACIDOS_GRASOS_ACIDULADO+$info->SERVICIO_MAQUILA));
            $porceTOTALO= round($infoTOLALO*100/$TOTALO,2).'%';
            $infoTOTCOSV= $infoTOLALO+$infoTOTP;
            //dd($infoTOTCOSV);
            $TOTALV = $totalProd+$infoTOTCOSV;
            $porceTOTCOSV= round($infoTOTCOSV*100/$TOTALV,2).'%';
            $infoTOTALBR= $TOTALV-$infoTOTCOSV;
            $porceTOTALBR=  round($infoTOTALBR*100/$TOTALV,2).'%';
            array_push($formates,[$infoACEITES,$porceAc,$infoMarga,$porceMarga,$infoSOLID,$porceSOLID,$infoTOTP,$porceTOTALP,$infoINDU,$porceINDU,$infoOTROS,$porceOTROS,$infoSERVM,$porceSERVM,$TOTALO,$porceTOTALO,$infoTOTCOSV,$porceTOTCOSV,$infoTOTALBR,$porceTOTALBR]);
            array_push($mes,[ 'mes'=>$dateObject]);
        }
        $form = 0;
            foreach($formates as $form){
                $form = count($form);
            }
        return view('TotalCosts\list_total_costs', ['dates'=> $formates, 'headers'=>$headers, 'mes'=>$mes, 'contador'=>$form]);
    }

  
}
