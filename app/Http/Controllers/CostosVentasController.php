<?php

namespace App\Http\Controllers;

use App\Http\Traits\CostosTrait;
use App\Http\Traits\CostosUnitTrait;
use App\Http\Traits\VentasNetasTrait;
use App\Http\Traits\VentasToneladasTrait;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CostosVentasController extends Controller
{
    use CostosTrait;
    use VentasToneladasTrait;
    use CostosUnitTrait;
    use VentasNetasTrait;
    public function total_costs(Request $request)
    {
        if ($request->filter1 != null) {
            $fechaIni = $request->filter1 . '-1';
            $fechaFin = $request->filter2 . '-1';
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->whereBetween('INF_D_FECHAS', [$fechaIni, $fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoSales = $infoSales->toArray();
        } else {
            $fechaIni = null;
            $fechaFin = null;
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoSales->toArray();
            $infoSales = $infoSales->toArray();
        }
        $headers = ['ACEITES', 'PORCENTAJE ACEITES', 'MARGARINAS', 'PORCENTAJE MARGARINAS', 'SOLIDOS Y CREMOSOS', 'PORCENTAJE SOLIDOS Y CREMOSOS', 'TOTAL PRODUCTO TERMINADO', 'PORCENTAJE TOTAL PRODUCTO TERMINADO', 'INDUSTRIALES', 'PORCENTAJE INDUSTRIALES', 'OTROS PRODUCTOS', 'PORCENTAJE OTROS PRODUCTOS', 'SERVICIO DE MAQUILA', 'PORCENTAJE SERVICIO DE MAQUILA', 'TOTAL OTROS', 'PORCENTAJE TOTAL OTROS', 'TOTAL COSTOS DE VENTAS', 'PORCENTAJE TOTAL COSTOS DE VENTAS', 'UTILIDAD BRUTA', 'PORCENTAJE UTILIDAD BRUTA'];
        $formates = [];
        $formates2 = [];
        $mes = [];
        $acumPorceSinSu = [];
        $acumSinSum = [];
        $c=1;
        foreach ($infoSales as $info) {
            if ($c == 3 || $c == 7 || $c == 11 || $c == 15) {
                $infoACEITESP =  round($info->ACEITES, 5);
                $infoMARGARINASP =  round($info->MARGARINAS, 5);
                $infoSOLIDOS_CREMOSOSP =  round($info->SOLIDOS_CREMOSOS, 5);
                $infoINDUSTRIALESP =  round($info->INDUSTRIALES, 5);
                $infoOTROSP =  round($info->ACIDOS_GRASOS_ACIDULADO, 5);
                $infoSERVICIO_MAQUILAP =  round($info->SERVICIO_MAQUILA, 5);
                $TOTALPP = $infoACEITESP + $infoMARGARINASP + $infoSOLIDOS_CREMOSOSP;
                $TOTALOP = $infoINDUSTRIALESP + $infoOTROSP + $infoSERVICIO_MAQUILAP;
                $TOTALVP = $TOTALPP + $TOTALOP;
                $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
                $infoACEITES = round($info->ACEITES2, 5);
                $porceAc = round($info->ACEITES2 * 100 / $info->ACEITES) . '%';
                $infoMarga = round($info->MARGARINAS2, 5);
                $porceMarga = round($info->MARGARINAS2 * 100 / $info->MARGARINAS, 2) . '%';
                $infoSOLID = round($info->SOLIDOS_CREMOSOS2, 5);
                $porceSOLID = round($info->SOLIDOS_CREMOSOS2 * 100 / $info->SOLIDOS_CREMOSOS, 2) . '%';
                $infoTOTP = round($info->SOLIDOS_CREMOSOS2 + $info->MARGARINAS2 + $info->ACEITES2);
                $TOTALPT = $info->ACEITES + $info->MARGARINAS + $info->SOLIDOS_CREMOSOS;
                $porceTOTALP = round($infoTOTP * 100 / $TOTALPT, 2) . '%';
                $totalProd = intval(round($info->ACEITES, 3) + round($info->MARGARINAS, 3) + round($info->SOLIDOS_CREMOSOS, 3));
                $porceTOTP = round($infoTOTP * 100 / $totalProd, 2) . '%';
                $infoINDU = intval(round($info->INDUSTRIALES2, 5));
                $porceINDU = round($info->INDUSTRIALES2 * 100 / round($info->INDUSTRIALES, 2), 2) . '%';
                $infoOTROS = intval(round($info->ACIDOS_GRASOS_ACIDULADO2, 5));
                $porceOTROS = round($info->ACIDOS_GRASOS_ACIDULADO2 * 100 / $info->ACIDOS_GRASOS_ACIDULADO, 2) . '%';
                $infoSERVM = round($info->SERVICIO_MAQUILA2, 5);
                $porceSERVM = round($info->SERVICIO_MAQUILA2 * 100 / $info->SERVICIO_MAQUILA, 2) . '%';
                $TOTALOT = round($info->INDUSTRIALES2 + $info->ACIDOS_GRASOS_ACIDULADO2 + $info->SERVICIO_MAQUILA2);
                $infoTOLALO = $infoINDU + $infoOTROS + $infoSERVM;
                $TOTALO = round($info->INDUSTRIALES + $info->ACIDOS_GRASOS_ACIDULADO + $info->SERVICIO_MAQUILA);
                $porceTOTALO = round($infoTOLALO * 100 / $TOTALO, 2) . '%';
                $infoTOTCOSV = $TOTALOT + $infoTOTP;
                $TOTALV = $totalProd + $TOTALO;
                $porceTOTCOSV = round($infoTOTCOSV * 100 / $TOTALV, 2) . '%';
                $infoTOTALBR = $TOTALV - $infoTOTCOSV;
                $porceTOTALBR =  round($infoTOTALBR * 100 / $TOTALV, 2) . '%';
                array_push($formates, [intval($infoACEITES), $porceAc, intval($infoMarga), $porceMarga, intval($infoSOLID), $porceSOLID, intval($infoTOTP), $porceTOTALP, intval($infoINDU), $porceINDU, intval($infoOTROS), $porceOTROS, intval($infoSERVM), $porceSERVM, intval($TOTALOT), $porceTOTALO, intval($infoTOTCOSV), $porceTOTCOSV, intval($infoTOTALBR), $porceTOTALBR]);
                array_push($acumPorceSinSu, [intval($infoACEITESP), intval($infoMARGARINASP), intval($infoSOLIDOS_CREMOSOSP), intval($TOTALPP), intval($infoINDUSTRIALESP), intval($infoOTROSP), intval($infoSERVICIO_MAQUILAP), intval($TOTALOP), intval($TOTALVP)]);
                array_push($acumSinSum, [$infoACEITES, $infoMarga, $infoSOLID, $infoTOTP, $infoINDU, $infoOTROS, $infoSERVM, $TOTALOT, $infoTOTCOSV, $infoTOTALBR]);
                array_push($mes, ['mes' => $dateObject]);
                array_push($mes, ['mes' => 'TRIMESTRE']); 

                switch ($c) {
                    case $c <= 3:
                        $ventasNetasTabla= $this->TablaVentas($fechaIni,$fechaFin);
                        $ventasNetasTabla= array_slice($ventasNetasTabla,0,3);
                        $sumaVentas = [];
                        for ($i = 0; $i < count($ventasNetasTabla[0]); $i++) {
                            $suma = 0;
                            foreach ($ventasNetasTabla as $prom) {
                                $suma += $prom[$i];
                            }
                            array_push($sumaVentas, intval(round($suma / 3)));
                        }

                        $formEdit= $formates;
                        $sumaCostos = [];
                        for ($i = 0; $i < count($formEdit[0]); $i++) {
                            $suma = 0;
                            foreach ($formEdit as $prom) {
                                if($i%2==0){
                                    $suma += $prom[$i];
                                }
                            }
                            array_push($sumaCostos, intval(round($suma / 3)));
                        }
                        $cuenta= count($sumaCostos);
                        for($i=0;$i<$cuenta;$i++){
                            if($i%2==0){
                            }else{
                                unset($sumaCostos[$i]);
                            }
                        }
                        $sumaCostos = array_values($sumaCostos);
                        $sumfinals=[];
                        for ($i = 0; $i < count($sumaVentas); $i++) {
                            array_push($sumfinals, $sumaCostos[$i]);
                            array_push($sumfinals, round($sumaCostos[$i]*100/$sumaVentas[$i],2).'%');
                        }
                        array_push($sumfinals, $sumaCostos[$i]);
                        array_push($sumfinals, round($sumaCostos[$i]*100/$sumaVentas[8],2).'%');
                        
                        array_push($formates, $sumfinals);
                        $c++;
                        break;
                    case $c >3 && $c < 8:
                        $formEdit1 = array_slice($formates,4,3);
                        $ventasNetasTabla= $this->TablaVentas($fechaIni,$fechaFin);
                        $ventasNetasTabla= array_slice($ventasNetasTabla,3,3);
                        $sumaVentas = [];
                        for ($i = 0; $i < count($ventasNetasTabla[0]); $i++) {
                            $suma = 0;
                            foreach ($ventasNetasTabla as $prom) {
                                $suma += $prom[$i];
                            }
                            array_push($sumaVentas, intval(round($suma / 3)));
                        }

                        $formEdit= $formates;
                        $sumaCostos = [];
                        for ($i = 0; $i < count($formEdit1[0]); $i++) {
                            $suma = 0;
                            foreach ($formEdit1 as $prom) {
                                if($i%2==0){
                                    $suma += $prom[$i];
                                }
                            }
                            array_push($sumaCostos, intval(round($suma / 3)));
                        }
                        $cuenta= count($sumaCostos);
                        for($i=0;$i<$cuenta;$i++){
                            if($i%2==0){
                            }else{
                                unset($sumaCostos[$i]);
                            }
                        }
                        $sumaCostos = array_values($sumaCostos);
                        $sumfinals=[];
                        for ($i = 0; $i < count($sumaVentas); $i++) {
                            array_push($sumfinals, $sumaCostos[$i]);
                            array_push($sumfinals, round($sumaCostos[$i]*100/$sumaVentas[$i],2).'%');
                        }
                        array_push($sumfinals, $sumaCostos[$i]);
                        array_push($sumfinals, round($sumaCostos[$i]*100/$sumaVentas[8],2).'%');
                        array_push($formates, $sumfinals);
                        $c++;
                        break;
                    case $c > 7 && $c <= 11:
                        $formEdit2 = array_slice($formates,8,3);
                        $ventasNetasTabla= $this->TablaVentas($fechaIni,$fechaFin);
                        $ventasNetasTabla= array_slice($ventasNetasTabla,6,3);
                        $sumaVentas = [];
                        for ($i = 0; $i < count($ventasNetasTabla[0]); $i++) {
                            $suma = 0;
                            foreach ($ventasNetasTabla as $prom) {
                                $suma += $prom[$i];
                            }
                            array_push($sumaVentas, intval(round($suma / 3)));
                        }

                        $formEdit= $formates;
                        $sumaCostos = [];
                        for ($i = 0; $i < count($formEdit2[0]); $i++) {
                            $suma = 0;
                            foreach ($formEdit2 as $prom) {
                                if($i%2==0){
                                    $suma += $prom[$i];
                                }
                            }
                            array_push($sumaCostos, intval(round($suma / 3)));
                        }
                        $cuenta= count($sumaCostos);
                        for($i=0;$i<$cuenta;$i++){
                            if($i%2==0){
                            }else{
                                unset($sumaCostos[$i]);
                            }
                        }
                        $sumaCostos = array_values($sumaCostos);
                        $sumfinals=[];
                        for ($i = 0; $i < count($sumaVentas); $i++) {
                            array_push($sumfinals, $sumaCostos[$i]);
                            array_push($sumfinals, round($sumaCostos[$i]*100/$sumaVentas[$i],2).'%');
                        }
                        array_push($sumfinals, $sumaCostos[$i]);
                        array_push($sumfinals, round($sumaCostos[$i]*100/$sumaVentas[8],2).'%');
                        array_push($formates, $sumfinals);
                        $c++;
                        break;
                    case $c > 11 :
                        $formEdit2 = array_slice($formates,12,3);
                        $ventasNetasTabla= $this->TablaVentas($fechaIni,$fechaFin);
                        $ventasNetasTabla= array_slice($ventasNetasTabla,9,3);
                        $sumaVentas = [];
                        for ($i = 0; $i < count($ventasNetasTabla[0]); $i++) {
                            $suma = 0;
                            foreach ($ventasNetasTabla as $prom) {
                                $suma += $prom[$i];
                            }
                            array_push($sumaVentas, intval(round($suma / 3)));
                        }

                        $formEdit= $formates;
                        $sumaCostos = [];
                        for ($i = 0; $i < count($formEdit2[0]); $i++) {
                            $suma = 0;
                            foreach ($formEdit2 as $prom) {
                                if($i%2==0){
                                    $suma += $prom[$i];
                                }
                            }
                            array_push($sumaCostos, intval(round($suma / 3)));
                        }
                        $cuenta= count($sumaCostos);
                        for($i=0;$i<$cuenta;$i++){
                            if($i%2==0){
                            }else{
                                unset($sumaCostos[$i]);
                            }
                        }
                        $sumaCostos = array_values($sumaCostos);
                        $sumfinals=[];
                        for ($i = 0; $i < count($sumaVentas); $i++) {
                            array_push($sumfinals, $sumaCostos[$i]);
                            array_push($sumfinals, round($sumaCostos[$i]*100/$sumaVentas[$i],2).'%');
                        }
                        array_push($sumfinals, $sumaCostos[$i]);
                        array_push($sumfinals, round($sumaCostos[$i]*100/$sumaVentas[8],2).'%');
                        array_push($formates, $sumfinals);
                        $c++;
                        break;
                }
                $c++;
            }else{
                $infoACEITESP =  round($info->ACEITES, 5);
                $infoMARGARINASP =  round($info->MARGARINAS, 5);
                $infoSOLIDOS_CREMOSOSP =  round($info->SOLIDOS_CREMOSOS, 5);
                $infoINDUSTRIALESP =  round($info->INDUSTRIALES, 5);
                $infoOTROSP =  round($info->ACIDOS_GRASOS_ACIDULADO, 5);
                $infoSERVICIO_MAQUILAP =  round($info->SERVICIO_MAQUILA, 5);
                $TOTALPP = $infoACEITESP + $infoMARGARINASP + $infoSOLIDOS_CREMOSOSP;
                $TOTALOP = $infoINDUSTRIALESP + $infoOTROSP + $infoSERVICIO_MAQUILAP;
                $TOTALVP = $TOTALPP + $TOTALOP;
                $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
                $infoACEITES = round($info->ACEITES2, 5);
                $porceAc = round($info->ACEITES2 * 100 / $info->ACEITES) . '%';
                $infoMarga = round($info->MARGARINAS2, 5);
                $porceMarga = round($info->MARGARINAS2 * 100 / $info->MARGARINAS, 2) . '%';
                $infoSOLID = round($info->SOLIDOS_CREMOSOS2, 5);
                $porceSOLID = round($info->SOLIDOS_CREMOSOS2 * 100 / $info->SOLIDOS_CREMOSOS, 2) . '%';
                $infoTOTP = round($info->SOLIDOS_CREMOSOS2 + $info->MARGARINAS2 + $info->ACEITES2);
                $TOTALPT = $info->ACEITES + $info->MARGARINAS + $info->SOLIDOS_CREMOSOS;
                $porceTOTALP = round($infoTOTP * 100 / $TOTALPT, 2) . '%';
                $totalProd = intval(round($info->ACEITES, 3) + round($info->MARGARINAS, 3) + round($info->SOLIDOS_CREMOSOS, 3));
                $porceTOTP = round($infoTOTP * 100 / $totalProd, 2) . '%';
                $infoINDU = intval(round($info->INDUSTRIALES2, 5));
                $porceINDU = round($info->INDUSTRIALES2 * 100 / round($info->INDUSTRIALES, 2), 2) . '%';
                $infoOTROS = intval(round($info->ACIDOS_GRASOS_ACIDULADO2, 5));
                $porceOTROS = round($info->ACIDOS_GRASOS_ACIDULADO2 * 100 / $info->ACIDOS_GRASOS_ACIDULADO, 2) . '%';
                $infoSERVM = round($info->SERVICIO_MAQUILA2, 5);
                $porceSERVM = round($info->SERVICIO_MAQUILA2 * 100 / $info->SERVICIO_MAQUILA, 2) . '%';
                $TOTALOT = round($info->INDUSTRIALES2 + $info->ACIDOS_GRASOS_ACIDULADO2 + $info->SERVICIO_MAQUILA2);
                $infoTOLALO = $infoINDU + $infoOTROS + $infoSERVM;
                $TOTALO = round($info->INDUSTRIALES + $info->ACIDOS_GRASOS_ACIDULADO + $info->SERVICIO_MAQUILA);
                $porceTOTALO = round($infoTOLALO * 100 / $TOTALO, 2) . '%';
                $infoTOTCOSV = $TOTALOT + $infoTOTP;
                $TOTALV = $totalProd + $TOTALO;
                $porceTOTCOSV = round($infoTOTCOSV * 100 / $TOTALV, 2) . '%';
                $infoTOTALBR = $TOTALV - $infoTOTCOSV;
                $porceTOTALBR =  round($infoTOTALBR * 100 / $TOTALV, 2) . '%';
                array_push($formates, [intval($infoACEITES), $porceAc, intval($infoMarga), $porceMarga, intval($infoSOLID), $porceSOLID, intval($infoTOTP), $porceTOTALP, intval($infoINDU), $porceINDU, intval($infoOTROS), $porceOTROS, intval($infoSERVM), $porceSERVM, intval($TOTALOT), $porceTOTALO, intval($infoTOTCOSV), $porceTOTCOSV, intval($infoTOTALBR), $porceTOTALBR]);
                array_push($acumPorceSinSu, [intval($infoACEITESP), intval($infoMARGARINASP), intval($infoSOLIDOS_CREMOSOSP), intval($TOTALPP), intval($infoINDUSTRIALESP), intval($infoOTROSP), intval($infoSERVICIO_MAQUILAP), intval($TOTALOP), intval($TOTALVP)]);
                array_push($acumSinSum, [$infoACEITES, $infoMarga, $infoSOLID, $infoTOTP, $infoINDU, $infoOTROS, $infoSERVM, $TOTALOT, $infoTOTCOSV, $infoTOTALBR]);
                array_push($mes, ['mes' => $dateObject]);
                $c++;
            }


        }
        array_push($mes, ['mes' => 'ACUMULADO']);
        array_push($mes, ['mes' => 'PROMEDIO']);

        //inicio de costo de ventas-- solo productos--
        $acumEnt = [];
        $promEnt = [];
        for ($i = 0; $i < count($acumSinSum[0]); $i++) {
            $suma = 0;
            foreach ($acumSinSum as $prom) {
                $suma += $prom[$i];
            }
            array_push($acumEnt, intval(round($suma)));
            array_push($promEnt, intval(round($suma / count($infoSales))));
        }
        //fin de la sumatoria
        //iniciop de sumatoria de ventas netas
        $acumPorc = [];
        $promPorc = [];
        for ($i = 0; $i < count($acumPorceSinSu[0]); $i++) {
            $suma = 0;
            foreach ($acumPorceSinSu as $prom) {
                $suma += $prom[$i];
            }
            array_push($acumPorc, intval(round($suma)));
            array_push($promPorc, intval(round($suma / count($infoSales))));
        }
        //fin de la sumatoria
        //inicio de creacion de el acumularor
        $acumulados = [];
        for ($i = 0; $i < count($acumPorceSinSu[0]); $i++) {
            $valorSum = $acumEnt[$i];
            $valorInd = $acumPorc[$i];
            array_push($acumulados, $valorSum);
            array_push($acumulados, round($valorSum / $valorInd, 2) . '%');
        }
        array_push($acumulados, intval($acumEnt[9]));
        array_push($acumulados, round($acumEnt[9] / $acumPorc[8], 2) . '%');
        //fin de creacion del acumulador     
        array_push($formates, $acumulados);
        //fin del arreglo

        //generando arreglo de acumulados por producto
        $promedios = [];
        for ($i = 0; $i < count($acumPorceSinSu[0]); $i++) {
            $valorSumP = $promEnt[$i];
            $valorIndP = $promPorc[$i];
            array_push($promedios, $valorSumP);
            array_push($promedios, round($valorSumP / $valorIndP, 2) . '%');
        }
        array_push($promedios, $promEnt[9]);
        array_push($promedios, round($promEnt[9] / $promPorc[8], 2) . '%');
        //fin de creacion del acumulador     
        array_push($formates, $promedios);
        //fin de arreglo de acumulados por producto




        return view('TotalCosts\list_total_costs', ['dates' => $formates, 'headers' => $headers, 'mes' => $mes, 'contador' => count($formates[0])]);
        
    }







    public function unit_sales_costs()
    {
        

        $infoCosts = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
        $infoCosts = $infoCosts->toArray();
        $infoUnits = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS', 'asc')->get();
        $infoUnits = $infoUnits->toArray();
        $headers = [
            'ACEITES', 'PORCENTAJE ACEITES', 'MARGARINAS', 'PORCENTAJE MARGARINAS', 'SOLIDOS Y CREMOSOS', 'PORCENTAJE SOLIDOS Y CREMOSOS', 'INDUSTRIALES', 'PORCENTAJE INDUSTRIALES',
            'OTROS PRODUCTOS', 'PORCENTAJE OTROS PRODUCTOS', 'SERVICIO DE MAQUILA', 'PORCENTAJE SERVICIO DE MAQUILA', 'TOTAL COSTOS DE VENTAS', 'PORCENTAJE TOTAL COSTOS DE VENTAS', 'UTILIDAD BRUTA', 'PORCENTAJE UTILIDAD BRUTA'
        ];
        $data1 = [];
        $mes = [];
        $sumaPrd = [];
        $sumaVn = [];
        $venNe = [];
        $ttven=[];
        foreach ($infoCosts as $info) {
            $aceites = round($info->ACEITES2, 5);
            $aceiteDiv = round($info->ACEITES,5);
            $margarinas = round($info->MARGARINAS2, 5);
            $margarinasDiv = round($info->MARGARINAS,5);
            $solidCrem = round($info->SOLIDOS_CREMOSOS2, 5);
            $solidCreDiv = round($info->SOLIDOS_CREMOSOS,5);
            $industriales = round($info->INDUSTRIALES2, 5);
            $induastrialesDiv = round($info->INDUSTRIALES,5);
            $otrosAcGr = round($info->ACIDOS_GRASOS_ACIDULADO2, 5);
            $otrosAcGrDiv = round($info->ACIDOS_GRASOS_ACIDULADO,5);
            $serviciosMqu = round($info->SERVICIO_MAQUILA2, 5);
            $serviciosMaqDiv = round($info->SERVICIO_MAQUILA,5);
            $TOTALOT = round($industriales+$otrosAcGr+$serviciosMqu);
            $infoTOTP = round($aceites+$margarinas+$solidCrem);
            $infoTOTCOSV = round($TOTALOT + $infoTOTP);
            $totPt = $aceites + $margarinas + $solidCrem;
            $totVent = $aceiteDiv + $margarinasDiv + $solidCreDiv + $induastrialesDiv + $otrosAcGrDiv + $serviciosMaqDiv;
            $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
            array_push($data1, [
                $aceites, $aceiteDiv, $margarinas, $margarinasDiv, $solidCrem, $solidCreDiv, $industriales,
                $induastrialesDiv, $otrosAcGr, $otrosAcGrDiv,
                $serviciosMqu, $serviciosMaqDiv, $infoTOTCOSV, $totPt, $totVent
            ]);
            array_push($sumaVn, [$serviciosMaqDiv, $totVent]);
            array_push($venNe, [$aceiteDiv, $margarinasDiv, $solidCreDiv, $induastrialesDiv, $otrosAcGrDiv, $serviciosMaqDiv]);
            array_push($sumaPrd, [$aceites, $margarinas, $solidCrem, $industriales, $otrosAcGr, $serviciosMqu, $infoTOTCOSV]);
            array_push($ttven,intval(round($totVent)));
            array_push($mes, ['mes' => $dateObject]);
        }
        
        $data2 = [];
        foreach ($infoUnits as $info) {
            $aceites2 = round($info->TON_ACEITES, 5);
            $margarinas2 = round($info->TON_MARGARINAS, 5);
            $solidCrem2 = round($info->TON_SOLIDOS_CREMOSOS, 5);
            $industriales2 = round($info->TON_INDUSTRIALES_OLEO, 5);
            $otrosAcGr2 = round($info->TON_ACIDOS_GRASOS_ACIDULADO, 5);
            $serviciosMaq2 = round($info->TON_SERVICIO_MAQUILA, 5);
            $totp = $aceites2 + $margarinas2 + $solidCrem2;
            $sumTt = $totp + $industriales2 + $otrosAcGr2;
            array_push($data2, [$aceites2, $margarinas2, $solidCrem2, $industriales2, $otrosAcGr2, $serviciosMaq2, $sumTt]);
        }

        $formates = [];
        $cosVenUnit=[];
        for ($i = 0; $i < count($data2); $i++) {
            $aceiteF = $data1[$i][0] / $data2[$i][0];
            $divAceit1 = $data1[$i][1] / $data2[$i][0];
            $divMarga1 = $data1[$i][3] / $data2[$i][1];
            $divSolidCre1 = $data1[$i][5] / $data2[$i][2];
            $divIndustriales1 = $data1[$i][7] / $data2[$i][3];
            $divOtrosAcGr1 = $data1[$i][9] / $data2[$i][4];
            $divservMaq1 = $data1[$i][11] / $data2[$i][5];
            $porceAceiteF = $aceiteF * 100 / $divAceit1;
            $margaF = $data1[$i][2] / $data2[$i][1];
            $porceMargaF = round($margaF, 2) * 100 / $divMarga1;
            $solidCreF = $data1[$i][4] / $data2[$i][2];
            $porceSolidCreF = round($solidCreF, 2) * 100 / $divSolidCre1;
            $industrialesF = $data1[$i][6] / $data2[$i][3];
            $porceIndustrialesF = round($industrialesF, 2) * 100 / $divIndustriales1;
            $otrosAcGrF = $data1[$i][8] / $data2[$i][4];
            $porceOtrosAgF = round($otrosAcGrF, 2) * 100 / $divOtrosAcGr1;
            $servMaqF = $data1[$i][10] / $data2[$i][5];
            $porceservMaq = round($servMaqF, 2) * 100 / $divservMaq1;
            $ventTon = $data1[$i][12] / $data2[$i][6];
            $porceCosVen = round($ventTon, 2) * 100 / (($data1[$i][14] - $data1[$i][11]) / $data2[$i][6]);
            $utlBrut = ((round($data1[$i][14]) - round($data1[$i][11])) / round($data2[$i][6])) - $ventTon;
            $porceTotlBrut = round($utlBrut, 2) * 100 / intval(round((round($data1[$i][14]) - round($data1[$i][11])) / round($data2[$i][6])));
            array_push($formates, [
                intval(round($aceiteF)), round($porceAceiteF, 2) . '%', intval(round($margaF)), round($porceMargaF, 2) . '%',
                intval(round($solidCreF)), round($porceSolidCreF, 2) . '%', intval(round($industrialesF)), round($porceIndustrialesF, 2) . '%',
                intval(round($otrosAcGrF)), round($porceOtrosAgF, 2) . '%', intval(round($servMaqF)), round($porceservMaq) . '%', intval(round($ventTon)),
                round($porceCosVen, 2) . '%', intval(round($utlBrut)), round($porceTotlBrut, 2) . '%'
            ]);
            array_push($cosVenUnit,[intval(round($aceiteF)), intval(round($margaF)),
            intval(round($solidCreF)), intval(round($industrialesF)), 
            intval(round($otrosAcGrF)), intval(round($servMaqF))]);
        }
        array_push($mes, ['mes' => 'ACUMULADO']);
        array_push($mes, ['mes' => 'PROMEDIO']);
        
        //sumatorias de tonelajes
        $promTonsTot = [];
        $acumTons = [];
        for ($i = 0; $i < count($data2[0]); $i++) {
            $suma = 0;
            foreach ($data2 as $ton) {
                $suma += $ton[$i];
            }
            array_push($acumTons, intval(round($suma)));
            array_push($promTonsTot, intval(round($suma / count($infoCosts))));
        }

        //sumatorias de productos
        $promCosVen = [];
        $acumCosVen = [];
        for ($i = 0; $i < count($sumaPrd[0]); $i++) {
            $suma = 0;
            foreach ($sumaPrd as $prod) {
                $suma += $prod[$i];
            }
            array_push($acumCosVen, intval(round($suma)));
            array_push($promCosVen, round($suma / count($infoCosts)));
        }
        $promVenNe = [];
        $acumVenNe = [];
        for ($i = 0; $i < count($venNe[0]); $i++) {
            $suma = 0;
            foreach ($venNe as $prod) {
                $suma += $prod[$i];
            }
            array_push($acumVenNe, intval(round($suma)));
            array_push($promVenNe, intval(round($suma/ count($infoCosts))));
        }
        $promS15S13 = [];
        $acumS15S13= [];
        for ($i = 0; $i < count($sumaVn[0]); $i++) {
            $suma = 0;
            foreach ($sumaVn as $prod) {
                $suma += $prod[$i];
            }
            array_push($acumS15S13, intval(round($suma)));
            array_push($promS15S13, intval(round($suma / count($infoCosts))));
        }
        $restaS15S13=  round($acumS15S13[1]- $acumS15S13[0]);

        $acumVenNetUnit=[];
        for($i=0;$i<count($acumVenNe);$i++){
            array_push($acumVenNetUnit, intval(round($acumVenNe[$i]/$acumTons[$i])));
        }
        array_push($acumVenNetUnit, intval(round($restaS15S13/$acumTons[6])));

        $acumulados=[];
        for($i=0;$i<count($acumCosVen);$i++){
            $acuM= intval(round($acumCosVen[$i]/$acumTons[$i]));
            $porceM= round($acuM/$acumVenNetUnit[$i],2).'%';
            array_push($acumulados,$acuM);
            array_push($acumulados,$porceM);
        }
        array_push($acumulados,$acumulados[6]-$acumVenNetUnit[6]);
        array_push($acumulados,round($acumulados[14]/$acumVenNetUnit[6],2).'%');
        
        $promVenNetUnit=[];
        for($i=0;$i<count($promVenNe);$i++){
            array_push($promVenNetUnit, intval(round($promVenNe[$i]/$promTonsTot[$i])));
        }
        $promedios=[];
        for($i=0;$i<count($formates[0]);$i++){
            $suma = 0;
            foreach ($formates as $prod){
                if($i%2==0){
                    $suma += round($prod[$i]/count($infoCosts));
                };
            }
            if($i%2==0){
                $a=0;
                array_push($promedios,intval($suma));
                array_push($promedios,round($suma/$promVenNetUnit[$a],2).'%');
                $a++;
            };
        }
        $suma = 0;
        for ($i = 0; $i < count($ttven); $i++) {
            $suma += $ttven[$i];
            $promVenNetT= intval(round($suma/ count($infoCosts)));
        }
        $resPromVenNe= $promVenNetT-$promVenNe[5];
        $t127= intval(round($promCosVen[6]/$promTonsTot[6]));
        $t113= intval(round($resPromVenNe/$promTonsTot[6]));
        $t129= intval(round($t113-$t127));
        array_push($promedios,$t127);
        array_push($promedios,$t127/$t113);
        array_push($promedios,$t129);
        array_push($promedios,$t129/$t113);
        //dd($promedios);
        
        
        
        
        
        array_push($formates,$acumulados);
        array_push($formates,$promedios);
        $form = 0;
        foreach ($formates as $form) {
            $form = count($form);
        }
        //dd($formates,$headers,$mes,$form); 
        return view('TotalCosts\list_total_costs_unit', ['dates' => $formates, 'headers' => $headers, 'mes' => $mes, 'contador' => $form]);
    }
}













/* $promVenNe = [];
        for ($i = 0; $i < count($venNe[0]); $i++) {
            $suma = 0;
            foreach ($venNe as $prod) {
                $suma += $prod[$i] / count($infoCosts);
            }
            array_push($promVenNe, intval(round($suma)));
        }
        $promVenNeUni = [];
        for ($i = 0; $i < count($promVenNe); $i++) {
            array_push($promVenNeUni, intval(round($promVenNe[$i] / $promTonsTot[$i])));
        }
        $suma = 0;
        foreach ($sumaPrd as $prod) {
            $suma += $prod[6];
        }
        $promTotCosVen = intval(round($suma / count($infoCosts)));
        $promTotCosVenUnit = intval(round($promTotCosVen / $promVenTot));

        //sumatorias de ventas//
        //$promVn=[];
        $sumatoriasVn = [];
        for ($i = 0; $i < count($sumaVn[0]); $i++) {
            $suma = 0;
            foreach ($sumaVn as $prod) {
                $suma += $prod[$i];
            }
            array_push($sumatoriasVn, intval(round($suma)));
            //array_push($promVn, intval(round($suma/count($infoCosts))));
        }
        $promSumSerMaq = intval(round($sumatoriasVn[0] / count($infoCosts)));
        $promSumTotV = intval(round($sumatoriasVn[1] / count($infoCosts)));
        $promVenTon = intval(round($sumatoriasTons[6] / count($infoCosts)));
        $promtotVenUni = ($promSumTotV - $promSumSerMaq) / $promVenTon;
        $promUtilBrUnit = intval(round($promtotVenUni / $promTotCosVenUnit));

        $divid = [];
        for ($i = 0; $i < count($infoCosts); $i++) {
            $ac = round($sumaPrd[$i][0] / $data2[$i][0]);
            $mg = round($sumaPrd[$i][1] / $data2[$i][1]);
            $sc = round($sumaPrd[$i][2] / $data2[$i][2]);
            $ind = round($sumaPrd[$i][3] / $data2[$i][3]);
            $ol = round($sumaPrd[$i][4] / $data2[$i][4]);
            $mq = round($sumaPrd[$i][5] / $data2[$i][5]);
            array_push($divid, [$ac, $mg, $sc, $ind, $ol, $mq]);
        }
        //sumatorias de porcentajes ventas//
        $promedios = [];
        for ($i = 0; $i < count($divid[0]); $i++) {
            $suma = 0;
            foreach ($divid as $prod) {
                $suma += $prod[$i] / count($infoCosts);
            }
            array_push($promedios, intval(round($suma)));
        }
        $pronfinal = [];
        for ($i = 0; $i < count($promVenNeUni); $i++) {
            array_push($pronfinal, $promedios[$i]);
            array_push($pronfinal, round($promedios[$i] / $promVenNeUni[$i], 2) . '%');
        }
        array_push($pronfinal, $promTotCosVenUnit);
        array_push($pronfinal, round($promTotCosVenUnit / $promtotVenUni, 2) . '%');
        array_push($pronfinal, $promUtilBrUnit);
        array_push($pronfinal, round($promUtilBrUnit / $promtotVenUni, 2) . '%');

        //sumatorias de ventas//
        $sumatoriasAc = [];
        for ($i = 0; $i < count($sumaPrd[0]); $i++) {
            $suma = 0;
            foreach ($sumaPrd as $prod) {
                $suma += $prod[$i];
            }
            array_push($sumatoriasAc, intval(round($suma)));
        }
        $tonelsP = round($sumatoriasTons[6] / count($infoCosts));
        $totCosVen = round($sumatoriasAc[6] / count($infoCosts));
        $totCosVenP = $totCosVen / $tonelsP;

        //fin de sumatorias de ventas


        $acumuladF = [];
        for ($i = 0; $i < count($sumatoriasAc); $i++) {
            array_push($acumuladF, intval(round($sumatoriasAc[$i] / $sumatoriasTons[$i])));
            array_push($acumuladF, round($sumatoriasTons[$i] / ($sumatoriasAc[$i] / $sumatoriasTons[$i]), 2) . '%');
        }
        $totVen = round(($sumatoriasVn[1] - $sumatoriasVn[0]) / $sumatoriasTons[6]);
        array_push($acumuladF, intval(round($acumuladF[12] / $totVen)));
        array_push($acumuladF, round(($acumuladF[12] / $totVen) / $totVen, 2) . '%');
        array_push($formates, $acumuladF);
        array_push($formates, $pronfinal);

        /* $promF=[];
        for($i=0;$i<count($acumuladF);$i++)
        array_push($promF,round($acumuladF[$i]/count($infoCosts))); */ 