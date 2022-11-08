<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class GastosOperacionalesController extends Controller
{
   public function operational_expenses(){
    $infoGastos = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')
    ->orderBy('INF_D_FECHAS','asc')->get();
    $headers=['GASTOS DE ADMINISTRACION','PORCENTAJE GASTOS DE ADMINISTRACION',
      'GATOS DE PERSONAL','PORCENTAJE GATOS DE PERSONAL','HONORARIOS','PORCENTAJE HONORARIOS',
      'SERVICIOS','PORCENTAJE SERVICIOS','OTROS','PORCENTAJE OTROS','GASTOS DE VENTAS','PORCENTAJE GASTOS DE VENTAS',
      'GATOS DE PERSONAL','PORCENTAJE GATOS DE PERSONAL','POLIZA CARTERA','PORCENTAJE POLIZA CARTERA','FLETES',
      'PORCENTAJE FLETES','SERVICIO LOGISTICO','PORCENTAJE SERVICIO LOGISTICO','ESTRATEGIA COMERCIAL',
      'PORCENTAJE ESTRATEGIA COMERCIAL','IMPUESTOS','PORCENTAJE IMPUESTOS','DESCUENTOS PRONTO PAGO',
      'PORCENTAJE DESCUENTOS PRONTO PAGO','OTROS','PORCENTAJE OTROS','DEPRECIACIONES Y AMORTIZACIONES',
      'PORCENTAJE DEPRECIACIONES Y AMORTIZACIONES','TOTAL GASTOS OPERACIONALES','PORCENTAJE TOTAL GASTOS OPERACIONALES',
      'UTILIDAD OPERACIONAL','PORCENTAJE UTILIDAD OPERACIONAL'];
      $mes=[];  
     $formGastos=[];

      foreach($infoGastos as $data){
         //informacion general tabla de ventas netas
         $infoACEITES=  round($data->ACEITES,5);
         $infoMARGARINAS=  round($data->MARGARINAS,5);
         $infoSOLIDOS_CREMOSOS=  round($data->SOLIDOS_CREMOSOS,5);
         //informacion general tabla de ventas netas
         $infoINDUSTRIALES=  round($data->INDUSTRIALES,5);
         $infoOTROS=  round($data->ACIDOS_GRASOS_ACIDULADO,5);
         $infoSERVICIO_MAQUILA=  round($data->SERVICIO_MAQUILA,5);
         //consulta alterna
         $TOTALP = intval($infoACEITES+$infoMARGARINAS+$infoSOLIDOS_CREMOSOS);
         $TOTALO = intval($infoINDUSTRIALES+$infoOTROS+$infoSERVICIO_MAQUILA);
         $TOTALV = intval($TOTALP+$TOTALO);
         //dd($TOTALV);
         //fin consulta externa
         //informacioin general tablacosto ventas
         $infoACEITES= intval(round($data->ACEITES2));
         $infoMarga= intval(round($data->MARGARINAS2));
         $infoSOLID= intval(round($data->SOLIDOS_CREMOSOS2));
         $infoTOTP= intval(round($data->SOLIDOS_CREMOSOS2+$data->MARGARINAS2+$data->ACEITES2));
         //fin consulta general tabla costo ventas
         //consulta general costo ventas2
         $infoINDU= intval(round($data->INDUSTRIALES2));
         $infoOTROS= intval(round($data->ACIDOS_GRASOS_ACIDULADO2));
         $infoSERVM= intval(round($data->SERVICIO_MAQUILA2));
         $infoTOLALO= $infoINDU+$infoOTROS+$infoSERVM;
         $TOTALO = intval(round($data->INDUSTRIALES+$data->ACIDOS_GRASOS_ACIDULADO+$data->SERVICIO_MAQUILA));
         $TOTSUMOTR=$TOTALO+$infoTOTP;
         $TOTLCOSVEN=$infoTOTP+$TOTALO;
         $UTLBRUTA= +$TOTALV-$TOTSUMOTR;
         //fin consulta
         //consulta utilidad bruta
         

         $gastAdmin= round($data->GASTOS_ADMINISTRACION,5);
         $porceGasAdmin = round($gastAdmin*100/$TOTALV,2).'%';
         $garPersonal= round($data->GASTOS_PERSONAL,5);
         $porcePerson= round($garPersonal*100/$TOTALV,2).'%';
         $honorarios= round($data->HONORARIOS,5);
         $porceHonor= round($honorarios*100/$TOTALV,2).'%';
         $servicios= round($data->SERVICIOS,2);
         $porceServi= round($servicios*100/$TOTALV,2).'%';
         $otros= round($gastAdmin-$garPersonal-$honorarios-$servicios,2);
         $porceOtros= round($otros*100/$TOTALV,2).'%';
         $gasVentas= round($data->GASTOS_VENTAS,2);
         $porceVentas= round($gasVentas*100/$TOTALV,2).'%';
         $gasPersonales2= round($data->GASTOS_PERSONAL2);
         $porcePersonales2= round($gasPersonales2*100/$TOTALV,2).'%';
         $polCartera= round($data->POLIZA_CARTERA);
         $porcePrtCartera= round($polCartera*100/$TOTALV,2).'%';
         $fletes= round($data->FLETES,2);
         $porceFletes= round($fletes*100/$TOTALV,2).'%';
         $servLogistico= round($data->SERVICIO_LOGISTICO);
         $porceservLog= round($servLogistico*100/$TOTALV,2).'%';
         $estrComer= round($data->ESTRATEGIA_COMERCIAL);
         $porceEstrComer= round($estrComer*100/$TOTALV,2).'%';
         $impuestos= round($data->IMPUESTOS);
         $porceImpu= round($impuestos*100/$TOTALV,2).'%';
         $descPronPa= round($data->DES_PRONTO_PAGO);
         $porceDesPr= round($descPronPa*100/$TOTALV,2).'%';
         $otr2= +$gasVentas-$gasPersonales2-$polCartera-$fletes-$servLogistico-$estrComer-$impuestos-$descPronPa;
         $porceOtr2=round($otr2*100/$TOTALV,2).'%';
         $depreAmorti= round($data->DEPRECIACIONES_AMORTIZACIONES);
         $porceDepreAmor=round($depreAmorti*100/$TOTALV,2).'%';
         $totGasOper= +$gastAdmin+$gasVentas+$depreAmorti;
         $porceTotGasOper=round($totGasOper*100/$TOTALV,2).'%';
         $UtilOper= $UTLBRUTA-$totGasOper;
         $porceUtilOper=round($UtilOper*100/$TOTALV,2).'%';
         $dateObject = DateTime::createFromFormat('m', $data->INF_D_MES)->format('F');

         array_push($formGastos,[$gastAdmin,$porceGasAdmin,$garPersonal,$porcePerson,$honorarios,$porceHonor,$servicios,$porceServi,$otros,$porceOtros
         ,$gasVentas,$porceVentas,$gasPersonales2,$porcePersonales2,$polCartera,$porcePrtCartera,$fletes,$porceFletes,$servLogistico,$porceservLog,
         $estrComer,$porceEstrComer,$impuestos,$porceImpu,$descPronPa,$porceDesPr,$otr2,$porceOtr2,$depreAmorti,$porceDepreAmor,$totGasOper,
         $porceTotGasOper,$UtilOper,$porceUtilOper]);
         array_push($mes,[ 'mes'=>$dateObject]);
      }
      $form = 0;
            foreach($formGastos as $form){
                $form = count($form);
            }
      return view('OperationalExpenses/list_operational_expenses',['headers'=>$headers, 'dates'=>$formGastos, 'mes'=>$mes, 'contador'=>$form ]);
   }
}
