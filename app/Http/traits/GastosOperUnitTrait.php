<?php

namespace App\Http\Traits;

use DateTime;
use Illuminate\Support\Facades\DB;

trait GastosOperUnitTrait
{
 use CostosUnitTrait;
 use VentasNetasUnitTrait;
 use GastosOperTrait;
 use VentasToneladasTrait;
 
    public function tablaGastosOperacionalesUnit($fechaIni, $fechaFin)
    {
      if($fechaIni != null){
         $fechaIni = $fechaIni.'-1';
         $fechaFin = $fechaFin.'-1';
         $infoGastosUn = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->whereBetween('INF_D_FECHAS',[$fechaIni,$fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
         $infoGastosUn= $infoGastosUn->toArray();
         $infoTonsUn = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->whereBetween('INF_D_FECHAS',[$fechaIni,$fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
         $infoTonsUn= $infoTonsUn->toArray();
     }else{
         $fechaIni = null;
         $fechaFin = null;
         $infoGastosUn = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
         $infoGastosUn = $infoGastosUn->toArray();
         $infoTonsUn = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS', 'asc')->get();
         $infoTonsUn= $infoTonsUn->toArray();
     }

      $data1 = [];
      $ven=[];
      $gasOp = [];
      $arrTotCosVen=[];
      foreach ($infoGastosUn as $info1) {
         $totCosVen= round($info1->ACEITES2,5)+round($info1->MARGARINAS2,5)+round($info1->SOLIDOS_CREMOSOS2,5)+round($info1->INDUSTRIALES2,5)+round($info1->ACIDOS_GRASOS_ACIDULADO2)+round($info1->SERVICIO_MAQUILA2,5);
         $gasAdmon = round($info1->GASTOS_ADMINISTRACION,5);
         $gasPerson = round($info1->GASTOS_PERSONAL,5);
         $honorarios = round($info1->HONORARIOS,5);
         $servicios = round($info1->SERVICIOS,5);
         $otros = $gasAdmon-$gasPerson-$honorarios-$servicios;
         $gasVentas = round($info1->GASTOS_VENTAS,5);
         $gasPerson2 = round($info1->GASTOS_PERSONAL2,5);
         $polizCart = round($info1->POLIZA_CARTERA,5);
         $fletes = round($info1->FLETES,5);
         $servicLog = round($info1->SERVICIO_LOGISTICO,5);
         $estratComer = round($info1->ESTRATEGIA_COMERCIAL,5);
         $impuestos = round($info1->IMPUESTOS,5);
         $descuProntP = round($info1->DES_PRONTO_PAGO,5);
         $otros2 = $gasVentas-$gasPerson2-$polizCart-$fletes-$servicLog-$estratComer-$impuestos-$descuProntP;
         $depresiaci = round($info1->DEPRECIACIONES_AMORTIZACIONES,5);
         $totVen = round($info1->ACEITES,5) + round($info1->MARGARINAS,5) + round($info1->SOLIDOS_CREMOSOS,5) + round($info1->INDUSTRIALES,5) + round($info1->ACIDOS_GRASOS_ACIDULADO,5) + round($info1->SERVICIO_MAQUILA,5);
         $servMqui= round($info1->SERVICIO_MAQUILA,5);
         $totVEN = (round($info1->ACEITES,5) + round($info1->MARGARINAS,5) + round($info1->SOLIDOS_CREMOSOS,5) + round($info1->INDUSTRIALES,5) + round($info1->ACIDOS_GRASOS_ACIDULADO,5) + round($info1->SERVICIO_MAQUILA,5)) - round($info1->SERVICIO_MAQUILA,5);
         $totGasOper = round($gasAdmon+$gasVentas,5)+$depresiaci;
         $dateObject = DateTime::createFromFormat('m', $info1->INF_D_MES)->format('F');
         $infoTOT = round($info1->SOLIDOS_CREMOSOS2 + $info1->MARGARINAS2 + $info1->ACEITES2)+round($info1->INDUSTRIALES2 + $info1->ACIDOS_GRASOS_ACIDULADO2 + $info1->SERVICIO_MAQUILA2);
         array_push($data1, [$gasAdmon, $totVEN, $gasPerson,$honorarios,$servicios,$otros,$gasVentas,$gasPerson2,$polizCart,$fletes
                  ,$servicLog,$estratComer,$impuestos,$descuProntP,$otros2,$depresiaci,$totGasOper,$infoTOT]);
         array_push($ven,[$servMqui,$totVen]);
         array_push($arrTotCosVen,$totCosVen);
         array_push($gasOp, [$gasAdmon, $gasPerson,$honorarios,$servicios,$otros,$gasVentas,$gasPerson2,$polizCart,$fletes
                  ,$servicLog,$estratComer,$impuestos,$descuProntP,$otros2,$depresiaci]);
      }

      $data2 = [];
      foreach ($infoTonsUn as $info2) {
         $venTON = round($info2->TON_ACEITES,5) + round($info2->TON_MARGARINAS,5) + round($info2->TON_SOLIDOS_CREMOSOS,5) + round($info2->TON_INDUSTRIALES_OLEO,5) + round($info2->TON_ACIDOS_GRASOS_ACIDULADO,5);
         
         array_push($data2, round($venTON,5));
      }
 
      $amount = count($data2);
      $formOper = [];
      for ($i = 0; $i < $amount; $i++) {
         $restaVen= round($data1[$i][1],5);
         $sumTonel=round($data2[$i],5);
         $totVenUnit= $restaVen/$sumTonel;
         $gasAdmonR = round($data1[$i][0],5)/$sumTonel;
         $porceAdmonR = round($gasAdmonR/$totVenUnit,2);
         $gasPersonR = round($data1[$i][2],5) / $sumTonel;
         $porcePersonR = round($gasPersonR/$totVenUnit,2);
         $honorariosR= round($data1[$i][3],5) / $sumTonel;
         $porceHonorR = round($honorariosR/$totVenUnit,2);
         $serviciosR= round($data1[$i][4],5) / $sumTonel;
         $porceServiR = round($serviciosR/$totVenUnit,2);
         $otrosR= round($data1[$i][5],5) / $sumTonel;
         $porceOtrosR = round($otrosR/$totVenUnit,2);
         $gasVentasR= round($data1[$i][6],5) / $sumTonel;
         $porceGasVentR = round($gasVentasR/$totVenUnit,2);
         $gasPerson2R= round($data1[$i][7],5) / $sumTonel;
         $porcePerson2R = round($gasPerson2R/$totVenUnit,2);
         $polizCartR= round($data1[$i][8],5) / $sumTonel;
         $porcePolizCartR = round($polizCartR/$totVenUnit,2);
         $fletesR= round($data1[$i][9],5) / $sumTonel;
         $porceFletesR = round($fletesR/$totVenUnit,2);
         $servicLogR= round($data1[$i][10],5) / $sumTonel;
         $porceServicLogR = round($servicLogR/$totVenUnit,2);
         $estratComerR= round($data1[$i][11],5) / $sumTonel;
         $porceEstratComerR = round($estratComerR/$totVenUnit,2);
         $impuestosR= round($data1[$i][12],5) / $sumTonel;
         $porceImpuestosR = round($impuestosR/$totVenUnit,2);
         $descuentProntPR= round($data1[$i][13],5) / $sumTonel;
         $porceDescuentProntPR = round($descuentProntPR/$totVenUnit,2);
         $otros2R= round($data1[$i][14],5) / $sumTonel;
         $porceOtros2R = round($otros2R/$totVenUnit,2);
         $depreciaciR= round($data1[$i][15],5) / $sumTonel;
         $porceDepreciaR = round($depreciaciR/$totVenUnit,2);
         $totGasOperR= round($data1[$i][16],5) / $sumTonel;
         $porceTotGasOperR = round($totGasOperR/$totVenUnit,2);
         $TOTVEN= round($data1[$i][1])/round($data2[$i]);
         $totCosVen= $data1[$i][17]/$data2[$i];
         $utilBrut= intval($TOTVEN-$totCosVen);
         $utilOper= +$utilBrut-intval(round($totGasOperR));
         $porceUtilBrR = round($utilOper/$totVenUnit,2);
         array_push($formOper, [$gasAdmonR, $porceAdmonR.'%',intval($gasPersonR), $porcePersonR.'%',
         intval($honorariosR),$porceHonorR.'%',intval($serviciosR),$porceServiR.'%',intval($otrosR)
         ,$porceOtrosR.'%',intval($gasVentasR),$porceGasVentR.'%',intval($gasPerson2R)
         ,$porcePerson2R.'%',intval($polizCartR),$porcePolizCartR.'%',intval($fletesR)
         ,$porceFletesR.'%',intval($servicLogR),$porceServicLogR.'%',intval($estratComerR)
         ,$porceEstratComerR.'%',intval($impuestosR),$porceImpuestosR.'%',intval($descuentProntPR)
         ,$porceDescuentProntPR.'%',intval($otros2R),$porceOtros2R.'%',intval($depreciaciR)
         ,$porceDepreciaR.'%',intval($totGasOperR),$porceTotGasOperR.'%',intval($utilOper), $porceUtilBrR.'%']);
      }

      //Inicio acumulados
      $acumGasOper=[];
      for ($i = 0; $i < count($gasOp[0]); $i++) {
         $suma = 0;
         foreach ($gasOp as $gasO) {
          $suma += $gasO[$i];
          }
          array_push($acumGasOper,intval(round($suma)));
       }
       $suma = 0;
       for ($i = 0; $i < count($data2); $i++) {
           $suma += intval(round($data2[$i]));
           $acumTonel= $suma;
         }
      $costosVentasUnitarios= $this->TablaCostosUnit($fechaIni,$fechaFin);
      $ventasNetasUnitarios= $this->TablaVentasUnit($fechaIni,$fechaFin);
      $gastosOperacionales= $this->tablaGastosoperacionales($fechaIni,$fechaFin);
      $ventastoneladas= $this->TablaVentasToneladas($fechaIni,$fechaFin);

      $acumulados=[];
      for($i=0;$i<count($acumGasOper);$i++){
         $entero = intval(round($acumGasOper[$i]/$acumTonel));
         $porcentaje = round($entero*100/$ventasNetasUnitarios[11][7]);
         array_push($acumulados,$entero);
         array_push($acumulados, round($porcentaje,2).'%');
      }
      $acumTotGasOperUnit= $acumulados[0]+$acumulados[10];
      $porceAcumGasOperUnit= $acumTotGasOperUnit*100/$ventasNetasUnitarios[11][7];
      array_push($acumulados, $acumTotGasOperUnit);
      array_push($acumulados, round($porceAcumGasOperUnit,2).'%');
      $acumUtilOperUnit= $acumTotGasOperUnit-$costosVentasUnitarios[11][14];
      $porceAcumUtilOperUnit= $acumUtilOperUnit*100/$ventasNetasUnitarios[11][7];
      array_push($acumulados, $acumUtilOperUnit);
      array_push($acumulados, round($porceAcumUtilOperUnit,2).'%');
      //fin acumulados
      //dd($ventastoneladas, $ventastoneladas[12][0]);



      $promedios=[];
      for($i=0;$i<count($gastosOperacionales[12])-4;$i++){
         if ($i%2==0){
            $promGasAdmin= intval(round($gastosOperacionales[12][$i]/$ventastoneladas[12][0]));
            $porcePromGas= round($promGasAdmin*100/$ventasNetasUnitarios[12][7],2).'%';
            array_push($promedios,$promGasAdmin);
            array_push($promedios,$porcePromGas);
         }
      }
      $promTotalGasOper= $promedios[0]+$promedios[10]+$promedios[28];
      $promPorceTotalGasOper= round($promTotalGasOper*100/$ventasNetasUnitarios[12][7],2).'%';
      $promUtilOperUnit= $costosVentasUnitarios[12][18]-$promTotalGasOper;
      $promPorceUtilOperUnit= round($promUtilOperUnit*100/$ventasNetasUnitarios[12][7],2).'%';
      array_push($promedios,$promTotalGasOper);
      array_push($promedios,$promPorceTotalGasOper);
      array_push($promedios,$promUtilOperUnit);
      array_push($promedios,$promPorceUtilOperUnit);


      array_push($formOper, $acumulados);
      array_push($formOper, $promedios);
      return $formOper;
   }

}