<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class GastosOperacionalesController extends Controller
{
   public function operational_expenses()
   {
      $infoGastos = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
      $infoGastos= $infoGastos->toArray();
      $headers = [
         'GASTOS DE ADMINISTRACION', 'PORCENTAJE GASTOS DE ADMINISTRACION',
         'GATOS DE PERSONAL', 'PORCENTAJE GATOS DE PERSONAL', 'HONORARIOS', 'PORCENTAJE HONORARIOS',
         'SERVICIOS', 'PORCENTAJE SERVICIOS', 'OTROS', 'PORCENTAJE OTROS', 'GASTOS DE VENTAS', 'PORCENTAJE GASTOS DE VENTAS',
         'GATOS DE PERSONAL', 'PORCENTAJE GATOS DE PERSONAL', 'POLIZA CARTERA', 'PORCENTAJE POLIZA CARTERA', 'FLETES',
         'PORCENTAJE FLETES', 'SERVICIO LOGISTICO', 'PORCENTAJE SERVICIO LOGISTICO', 'ESTRATEGIA COMERCIAL',
         'PORCENTAJE ESTRATEGIA COMERCIAL', 'IMPUESTOS', 'PORCENTAJE IMPUESTOS', 'DESCUENTOS PRONTO PAGO',
         'PORCENTAJE DESCUENTOS PRONTO PAGO', 'OTROS', 'PORCENTAJE OTROS', 'DEPRECIACIONES Y AMORTIZACIONES',
         'PORCENTAJE DEPRECIACIONES Y AMORTIZACIONES', 'TOTAL GASTOS OPERACIONALES', 'PORCENTAJE TOTAL GASTOS OPERACIONALES',
         'UTILIDAD OPERACIONAL', 'PORCENTAJE UTILIDAD OPERACIONAL'
      ];
      $mes = [];
      $formGastos = [];

      foreach ($infoGastos as $data) {
         //informacion general tabla de ventas netas
         $infoACEITES =  round($data->ACEITES, 5);
         $infoMARGARINAS =  round($data->MARGARINAS, 5);
         $infoSOLIDOS_CREMOSOS =  round($data->SOLIDOS_CREMOSOS, 5);
         //informacion general tabla de ventas netas
         $infoINDUSTRIALES =  round($data->INDUSTRIALES, 5);
         $infoOTROS =  round($data->ACIDOS_GRASOS_ACIDULADO, 5);
         $infoSERVICIO_MAQUILA =  round($data->SERVICIO_MAQUILA, 5);
         //consulta alterna
         $TOTALP = intval($infoACEITES + $infoMARGARINAS + $infoSOLIDOS_CREMOSOS);
         $TOTALO = intval($infoINDUSTRIALES + $infoOTROS + $infoSERVICIO_MAQUILA);
         $TOTALV = intval($TOTALP + $TOTALO);
         //dd($TOTALV);
         //fin consulta externa
         //informacioin general tablacosto ventas
         $infoACEITES = intval(round($data->ACEITES2));
         $infoMarga = intval(round($data->MARGARINAS2));
         $infoSOLID = intval(round($data->SOLIDOS_CREMOSOS2));
         $infoTOTP = intval(round($data->SOLIDOS_CREMOSOS2 + $data->MARGARINAS2 + $data->ACEITES2));
         //fin consulta general tabla costo ventas
         //consulta general costo ventas2
         $infoINDU = intval(round($data->INDUSTRIALES2));
         $infoOTROS = intval(round($data->ACIDOS_GRASOS_ACIDULADO2));
         $infoSERVM = intval(round($data->SERVICIO_MAQUILA2));
         $infoTOLALO = $infoINDU + $infoOTROS + $infoSERVM;
         $TOTALO = intval(round($data->INDUSTRIALES + $data->ACIDOS_GRASOS_ACIDULADO + $data->SERVICIO_MAQUILA));
         $TOTSUMOTR = $TOTALO + $infoTOTP;
         $TOTLCOSVEN = $infoTOTP + $TOTALO;
         $UTLBRUTA = +$TOTALV - $TOTSUMOTR;
         //fin consulta
         //consulta utilidad bruta


         $gastAdmin = round($data->GASTOS_ADMINISTRACION, 5);
         $porceGasAdmin = round($gastAdmin * 100 / $TOTALV, 2) . '%';
         $garPersonal = round($data->GASTOS_PERSONAL, 5);
         $porcePerson = round($garPersonal * 100 / $TOTALV, 2) . '%';
         $honorarios = round($data->HONORARIOS, 5);
         $porceHonor = round($honorarios * 100 / $TOTALV, 2) . '%';
         $servicios = round($data->SERVICIOS, 2);
         $porceServi = round($servicios * 100 / $TOTALV, 2) . '%';
         $otros = round($gastAdmin - $garPersonal - $honorarios - $servicios, 2);
         $porceOtros = round($otros * 100 / $TOTALV, 2) . '%';
         $gasVentas = round($data->GASTOS_VENTAS, 2);
         $porceVentas = round($gasVentas * 100 / $TOTALV, 2) . '%';
         $gasPersonales2 = round($data->GASTOS_PERSONAL2);
         $porcePersonales2 = round($gasPersonales2 * 100 / $TOTALV, 2) . '%';
         $polCartera = round($data->POLIZA_CARTERA);
         $porcePrtCartera = round($polCartera * 100 / $TOTALV, 2) . '%';
         $fletes = round($data->FLETES, 2);
         $porceFletes = round($fletes * 100 / $TOTALV, 2) . '%';
         $servLogistico = round($data->SERVICIO_LOGISTICO);
         $porceservLog = round($servLogistico * 100 / $TOTALV, 2) . '%';
         $estrComer = round($data->ESTRATEGIA_COMERCIAL);
         $porceEstrComer = round($estrComer * 100 / $TOTALV, 2) . '%';
         $impuestos = round($data->IMPUESTOS);
         $porceImpu = round($impuestos * 100 / $TOTALV, 2) . '%';
         $descPronPa = round($data->DES_PRONTO_PAGO);
         $porceDesPr = round($descPronPa * 100 / $TOTALV, 2) . '%';
         $otr2 = +$gasVentas - $gasPersonales2 - $polCartera - $fletes - $servLogistico - $estrComer - $impuestos - $descPronPa;
         $porceOtr2 = round($otr2 * 100 / $TOTALV, 2) . '%';
         $depreAmorti = round($data->DEPRECIACIONES_AMORTIZACIONES);
         $porceDepreAmor = round($depreAmorti * 100 / $TOTALV, 2) . '%';
         $totGasOper = +$gastAdmin + $gasVentas + $depreAmorti;
         $porceTotGasOper = round($totGasOper * 100 / $TOTALV, 2) . '%';
         $UtilOper = $UTLBRUTA - $totGasOper;
         $porceUtilOper = round($UtilOper * 100 / $TOTALV, 2) . '%';
         $dateObject = DateTime::createFromFormat('m', $data->INF_D_MES)->format('F');

         array_push($formGastos, [
            $gastAdmin, $porceGasAdmin, $garPersonal, $porcePerson, $honorarios, $porceHonor, $servicios, $porceServi, $otros, $porceOtros, $gasVentas, $porceVentas, $gasPersonales2, $porcePersonales2, $polCartera, $porcePrtCartera, $fletes, $porceFletes, $servLogistico, $porceservLog,
            $estrComer, $porceEstrComer, $impuestos, $porceImpu, $descPronPa, $porceDesPr, $otr2, $porceOtr2, $depreAmorti, $porceDepreAmor, $totGasOper,
            $porceTotGasOper, $UtilOper, $porceUtilOper
         ]);
         array_push($mes, ['mes' => $dateObject]);
      }
      array_push($mes, ['mes' => 'ACUMULADO']);
      array_push($mes, ['mes' => 'PROMEDIO']);
      $ventTotales=[];
      $infoOper=[];
      foreach($infoGastos as $dataOper){
         $gasAdmonO = round($dataOper->GASTOS_ADMINISTRACION, 5);
         $gasPersonalO = round($dataOper->GASTOS_PERSONAL, 5);
         $honorariosO = round($dataOper->HONORARIOS, 5);
         $serviciosO = round($dataOper->SERVICIOS, 5);
         $otrosO = $gasAdmonO - $gasPersonalO - $honorariosO - $serviciosO;
         $gasVentasO = round($dataOper->GASTOS_VENTAS, 5);
         $gasPersonalesO = round($dataOper->GASTOS_PERSONAL2,5);
         $polCarteraO = round($dataOper->POLIZA_CARTERA,5);
         $fletesO = round($dataOper->FLETES, 5);
         $servLogisticoO = round($dataOper->SERVICIO_LOGISTICO,5);
         $estrComerO = round($dataOper->ESTRATEGIA_COMERCIAL,5);
         $impuestosO = round($dataOper->IMPUESTOS,5);
         $descPronPaO = round($dataOper->DES_PRONTO_PAGO,5);
         $otr2 = +$gasVentasO - $gasPersonalesO - $polCarteraO - $fletesO - $servLogisticoO - $estrComerO - $impuestosO - $descPronPaO;
         $depreAmorti = round($dataOper->DEPRECIACIONES_AMORTIZACIONES,5);
         $totGasOper = +$gasAdmonO + $gasVentasO + $depreAmorti;
         //---
         //informacion general tabla de ventas netas
         $infoACEITES =  round($dataOper->ACEITES, 5);
         $infoMARGARINAS =  round($dataOper->MARGARINAS, 5);
         $infoSOLIDOS_CREMOSOS =  round($dataOper->SOLIDOS_CREMOSOS, 5);
         //informacion general tabla de ventas netas
         $infoINDUSTRIALES =  round($dataOper->INDUSTRIALES, 5);
         $infoOTROS =  round($dataOper->ACIDOS_GRASOS_ACIDULADO, 5);
         $infoSERVICIO_MAQUILA =  round($dataOper->SERVICIO_MAQUILA, 5);
         //consulta alterna
         $TOTALP = intval($infoACEITES + $infoMARGARINAS + $infoSOLIDOS_CREMOSOS);
         $TOTALO = intval($infoINDUSTRIALES + $infoOTROS + $infoSERVICIO_MAQUILA);
         $TOTALV = intval($TOTALP + $TOTALO);
         //dd($TOTALV);
         //fin consulta externa
         //informacioin general tablacosto ventas
         $infoACEITES = intval(round($dataOper->ACEITES2));
         $infoMarga = intval(round($dataOper->MARGARINAS2));
         $infoSOLID = intval(round($dataOper->SOLIDOS_CREMOSOS2));
         $infoTOTP = intval(round($dataOper->SOLIDOS_CREMOSOS2 + $dataOper->MARGARINAS2 + $dataOper->ACEITES2));
         //fin consulta general tabla costo ventas
         //consulta general costo ventas2
         $infoINDU = intval(round($dataOper->INDUSTRIALES2));
         $infoOTROS = intval(round($dataOper->ACIDOS_GRASOS_ACIDULADO2));
         $infoSERVM = intval(round($dataOper->SERVICIO_MAQUILA2));
         $infoTOLALO = $infoINDU + $infoOTROS + $infoSERVM;
         $TOTALO = intval(round($dataOper->INDUSTRIALES + $dataOper->ACIDOS_GRASOS_ACIDULADO + $dataOper->SERVICIO_MAQUILA));
         $TOTSUMOTR = $TOTALO + $infoTOTP;
         $TOTLCOSVEN = $infoTOTP + $TOTALO;
         $UTLBRUTA = +$TOTALV - $TOTSUMOTR;
         //fin consulta
         //consulta utilidad bruta
         //---
         $UtilOper = $UTLBRUTA - $totGasOper;
         array_push($infoOper,[$gasAdmonO,$gasPersonalO,$honorariosO,$serviciosO, $otrosO,$gasVentasO,$gasPersonalesO,$polCarteraO,$fletesO,$servLogisticoO,$estrComerO,$impuestosO,$descPronPaO,$otr2,$depreAmorti,$totGasOper,$UtilOper]);
         array_push($ventTotales, $TOTALV);
      }
      
      $sumatorias = [];
      $promedios=[];
        for ($i = 0; $i < count($infoOper[0]); $i++) {
            $suma = 0;
            foreach ($infoOper as $sum) {
               //dd($sum[$i]); 
               $suma += $sum[$i];
            }
            array_push($sumatorias, intval(round($suma)));
            array_push($promedios, intval(round($suma / count($infoGastos))));
        }
        
        //dd($promedios);
        for ($i = 0; $i < count($infoGastos); $i++) {
           $suma = 0;
           foreach ($ventTotales as $tot) {
            $suma += $tot;
            }
         }         
         $sumtot=$suma;

         $acumulados=[]; 
         for ($i = 0; $i < count($infoOper[0]); $i++) {
               $sumD = $sumatorias[$i];
               $porceD = $sumD/$sumtot;
               array_push($acumulados, $sumD);
               array_push($acumulados, round($porceD,2).'%');
            }
        array_push($formGastos, $acumulados);

        $promediosF=[]; 
         for ($i = 0; $i < count($infoOper[0]); $i++) {
               $promD = $promedios[$i];
               $promPorce = $promD/($sumtot/count($infoGastos));
               array_push($promediosF, $promD);
               array_push($promediosF, round($promPorce,2).'%');
            }
        array_push($formGastos, $promediosF);


      $form = 0;
      foreach ($formGastos as $form) {
         $form = count($form);
      }
      return view('OperationalExpenses/list_operational_expenses', ['headers' => $headers, 'dates' => $formGastos, 'mes' => $mes, 'contador' => $form]);
   }




   public function unit_operational_expenses()
   {
      $infoGastosUn = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')
         ->orderBy('INF_D_FECHAS', 'asc')->get();
      $infoTonsUn = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')
         ->orderBy('INF_D_FECHAS', 'asc')->get();
      $headers= ['GASTOS DE ADMINISTRACION','PORCENTAJE GASTOS DE ADMINISTRACION','GATOS DE PERSONAL','PORCENTAJE GATOS DE PERSONAL','HONORARIOS','PORCENTAJE HONORARIOS',
   'SERVICIOS','PORCENTAJE SERVICIOS','OTROS','PORCENTAJE OTROS','GASTOS DE VENTAS','PORCENTAJE GASTOS DE VENTAS','GATOS DE PERSONAL','PORCENTAJE GATOS DE PERSONAL','POLIZA CARTERA',
   'PORCENTAJE POLIZA CARTERA','FLETES','PORCENTAJE FLETES','SERVICIO LOGISTICO','PORCENTAJE SERVICIO LOGISTICO','ESTRATEGIA COMERCIAL','PORCENTAJE ESTRATEGIA COMERCIAL','IMPUESTOS',
   'PORCENTAJE IMPUESTOS','DESCUENTOS PRONTO PAGO','PORCENTAJE DESCUENTOS PRONTO PAGO','OTROS','PORCENTAJE OTROS','DEPRECIACIONES Y AMORTIZACIONES','PORCENTAJE DEPRECIACIONES Y AMORTIZACIONES',
   'TOTAL GASTOS OPERACIONALES','PORCENTAJE TOTAL GASTOS OPERACIONALES','UTILIDAD OPERACIONAL','PORCENTAJE UTILIDAD OPERACIONAL'];
      $mes=[];
      $data1 = [];
      foreach ($infoGastosUn as $info1) {
         $gasAdmon = $info1->GASTOS_ADMINISTRACION;
         $totVEN = ($info1->ACEITES + $info1->MARGARINAS + $info1->SOLIDOS_CREMOSOS + $info1->INDUSTRIALES + $info1->ACIDOS_GRASOS_ACIDULADO + $info1->SERVICIO_MAQUILA) - $info1->SERVICIO_MAQUILA;
         $gasPerson = $info1->GASTOS_PERSONAL;
         $honorarios = $info1->HONORARIOS;
         $servicios = $info1->SERVICIOS;
         $otros = $gasAdmon-$gasPerson-$honorarios-$servicios;
         $gasVentas = $info1->GASTOS_VENTAS;
         $gasPerson2 = $info1->GASTOS_PERSONAL2;
         $polizCart = $info1->POLIZA_CARTERA;
         $fletes = $info1->FLETES;
         $servicLog = $info1->SERVICIO_LOGISTICO;
         $estratComer = $info1->ESTRATEGIA_COMERCIAL;
         $impuestos = $info1->IMPUESTOS;
         $descuProntP = $info1->DES_PRONTO_PAGO;
         $otros2 = $gasVentas-$gasPerson2-$polizCart-$fletes-$servicLog-$estratComer-$impuestos-$descuProntP;
         $depresiaci = $info1->DEPRECIACIONES_AMORTIZACIONES;
         $totGasOper = $gasAdmon+$gasVentas+$depresiaci;
         $dateObject = DateTime::createFromFormat('m', $info1->INF_D_MES)->format('F');
         $infoTOT = round($info1->SOLIDOS_CREMOSOS2 + $info1->MARGARINAS2 + $info1->ACEITES2)+round($info1->INDUSTRIALES2 + $info1->ACIDOS_GRASOS_ACIDULADO2 + $info1->SERVICIO_MAQUILA2);
         array_push($data1, [$gasAdmon, $totVEN, $gasPerson,$honorarios,$servicios,$otros,$gasVentas,$gasPerson2,$polizCart,$fletes
                  ,$servicLog,$estratComer,$impuestos,$descuProntP,$otros2,$depresiaci,$totGasOper,$infoTOT]);
         array_push($mes, ['mes' => $dateObject]);
      }

      $data2 = [];
      foreach ($infoTonsUn as $info2) {
         $venTON = $info2->TON_ACEITES + $info2->TON_MARGARINAS + $info2->TON_SOLIDOS_CREMOSOS + $info2->TON_INDUSTRIALES_OLEO + $info2->TON_ACIDOS_GRASOS_ACIDULADO;
         array_push($data2, [$venTON]);
      }

      $amount = count($data2) - 1;
      $formOper = [];
      for ($i = 0; $i <= $amount; $i++) {
         $gasAdmonR = $data1[$i][0] / $data2[$i][0];
         $porceAdmonR = intval(round($gasAdmonR)) * 100 / ($data1[$i][1] / $data2[$i][0]);
         $gasPersonR = $data1[$i][2] / $data2[$i][0];
         $porcePersonR = intval(round($gasPersonR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $honorariosR= $data1[$i][3] / $data2[$i][0];
         $porceHonorR = intval(round($gasPersonR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $serviciosR= $data1[$i][4] / $data2[$i][0];
         $porceServiR = intval(round($serviciosR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $otrosR= $data1[$i][5] / $data2[$i][0];
         $porceOtrosR = intval(round($otrosR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $gasVentasR= $data1[$i][6] / $data2[$i][0];
         $porceGasVentR = intval(round($gasVentasR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $gasPerson2R= $data1[$i][7] / $data2[$i][0];
         $porcePerson2R = intval(round($gasPerson2R)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $polizCartR= $data1[$i][8] / $data2[$i][0];
         $porcePolizCartR = intval(round($polizCartR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $fletesR= $data1[$i][9] / $data2[$i][0];
         $porceFletesR = intval(round($fletesR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $servicLogR= $data1[$i][10] / $data2[$i][0];
         $porceServicLogR = intval(round($servicLogR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $estratComerR= $data1[$i][11] / $data2[$i][0];
         $porceEstratComerR = intval(round($estratComerR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $impuestosR= $data1[$i][12] / $data2[$i][0];
         $porceImpuestosR = intval(round($impuestosR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $descuentProntPR= $data1[$i][13] / $data2[$i][0];
         $porceDescuentProntPR = intval(round($descuentProntPR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $otros2R= $data1[$i][14] / $data2[$i][0];
         $porceOtros2R = intval(round($otros2R)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $depreciaciR= $data1[$i][15] / $data2[$i][0];
         $porceDepreciaR = intval(round($depreciaciR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $totGasOperR= $data1[$i][16] / $data2[$i][0];
         $porceTotGasOperR = intval(round($totGasOperR)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         $TOTVEN= round($data1[$i][1])/round($data2[$i][0]);
         $totCosVen= $data1[$i][17]/$data2[$i][0];
         $utilBrut= intval($TOTVEN-$totCosVen);
         $utilOper= +$utilBrut-intval(round($totGasOperR));
         $porceUtilBrR = intval(round($utilOper)) * 100 / round(round($data1[$i][1]) / round($data2[$i][0]));
         array_push($formOper, [intval(round($honorariosR)), round($porceAdmonR, 2) . '%',intval(round($gasPersonR)), round($porcePersonR,2).'%',
                     intval(round($honorariosR)),round($porceHonorR,2).'%',intval(round($serviciosR)),round($porceServiR,2).'%',intval(round($otrosR))
                     ,round($porceOtrosR,2).'%',intval(round($gasVentasR)),round($porceGasVentR,2).'%',intval(round($gasPerson2R))
                     ,round($porcePerson2R,2).'%',intval(round($polizCartR)),round($porcePolizCartR,2).'%',intval(round($fletesR))
                     ,round($porceFletesR,2).'%',intval(round($servicLogR)),round($porceServicLogR,2).'%',intval(round($estratComerR))
                     ,round($porceEstratComerR,2).'%',intval(round($impuestosR)),round($porceImpuestosR,2).'%',intval(round($descuentProntPR))
                     ,round($porceDescuentProntPR,2).'%',intval(round($otros2R)),round($porceOtros2R,2).'%',intval(round($depreciaciR))
                     ,round($porceDepreciaR,2).'%',intval(round($totGasOperR)),round($porceTotGasOperR,2).'%',$utilOper,round($porceUtilBrR,2).'%']);
      }
      $form = 0;
      foreach ($formOper as $form) {
         $form = count($form);
      }
      //dd($data1[$i][16]);
      return view('OperationalExpenses\list_operational_expensesUnit', ['headers' => $headers, 'dates' => $formOper, 'mes' => $mes, 'contador' => $form]);
   }
}
