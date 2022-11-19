<?php
namespace App\Http\Traits;

use DateTime;
use Illuminate\Support\Facades\DB;

trait CostosTrait {
    
    public function TablaCostos(){
    $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
    $infoSales = $infoSales->toArray();
    

    //$headers = ['ACEITES', 'PORCENTAJE ACEITES', 'MARGARINAS', 'PORCENTAJE MARGARINAS', 'SOLIDOS Y CREMOSOS', 'PORCENTAJE SOLIDOS Y CREMOSOS', 'TOTAL PRODUCTO TERMINADO', 'PORCENTAJE TOTAL PRODUCTO TERMINADO', 'INDUSTRIALES', 'PORCENTAJE INDUSTRIALES', 'OTROS PRODUCTOS', 'PORCENTAJE OTROS PRODUCTOS', 'SERVICIO DE MAQUILA', 'PORCENTAJE SERVICIO DE MAQUILA', 'TOTAL OTROS', 'PORCENTAJE TOTAL OTROS', 'TOTAL COSTOS DE VENTAS', 'PORCENTAJE TOTAL COSTOS DE VENTAS', 'UTILIDAD BRUTA', 'PORCENTAJE UTILIDAD BRUTA'];
    $formates = [];
    $formates2 = [];
    $mes = [];
    $acumPorceSinSu = [];
    $c = 1;
    $acumSinSum = [];
    foreach ($infoSales as $info) {
        if ($c == 3 || $c == 6 || $c == 9 || $c == 12) {
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
            $infoACEITES = intval(round($info->ACEITES2, 5));
            $porceAc = round($info->ACEITES2 * 100 / $info->ACEITES) . '%';
            $infoMarga = intval(round($info->MARGARINAS2, 5));
            $porceMarga = round($info->MARGARINAS2 * 100 / $info->MARGARINAS, 2) . '%';
            $infoSOLID = intval(round($info->SOLIDOS_CREMOSOS2, 5));
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
            $infoSERVM = intval(round($info->SERVICIO_MAQUILA2, 5));
            $porceSERVM = round($info->SERVICIO_MAQUILA2 * 100 / $info->SERVICIO_MAQUILA, 2) . '%';
            $TOTALOT = round($info->INDUSTRIALES2 + $info->ACIDOS_GRASOS_ACIDULADO2 + $info->SERVICIO_MAQUILA2);
            $infoTOLALO = $infoINDU + $infoOTROS + $infoSERVM;
            $TOTALO = intval(round($info->INDUSTRIALES + $info->ACIDOS_GRASOS_ACIDULADO + $info->SERVICIO_MAQUILA));
            $porceTOTALO = round($infoTOLALO * 100 / $TOTALO, 2) . '%';
            $infoTOTCOSV = $TOTALOT + $infoTOTP;
            $TOTALV = $totalProd + $TOTALO;
            $porceTOTCOSV = round($infoTOTCOSV * 100 / $TOTALV, 2) . '%';
            $infoTOTALBR = $TOTALV - $infoTOTCOSV;
            $porceTOTALBR =  round($infoTOTALBR * 100 / $TOTALV, 2) . '%';
            array_push($formates, [$infoACEITES, $porceAc, $infoMarga, $porceMarga, $infoSOLID, $porceSOLID, $infoTOTP, $porceTOTALP, $infoINDU, $porceINDU, $infoOTROS, $porceOTROS, $infoSERVM, $porceSERVM, intval(round($TOTALOT)), $porceTOTALO, intval(round($infoTOTCOSV)), $porceTOTCOSV, intval(round($infoTOTALBR)), $porceTOTALBR]);
            array_push($acumPorceSinSu, [$infoACEITESP, $infoMARGARINASP, $infoSOLIDOS_CREMOSOSP, $TOTALPP, $infoINDUSTRIALESP, $infoOTROSP, $infoSERVICIO_MAQUILAP, $TOTALOP, $TOTALVP]);
            array_push($acumSinSum, [$infoACEITES, $infoMarga, $infoSOLID, $infoTOTP, $infoINDU, $infoOTROS, $infoSERVM, $TOTALOT, $infoTOTCOSV, $infoTOTALBR]);
            array_push($mes, ['mes' => $dateObject]);
            /* array_push($mes, ['mes' => 'TRIMESTRE']); */
            $sumP = [];
            foreach ($infoSales as $prom1) {
                $infoACEITES = intval(round($prom1->ACEITES2));
                $infoMarga = intval(round($prom1->MARGARINAS2));
                $infoSOLID = intval(round($prom1->SOLIDOS_CREMOSOS2));
                $infoTOTP = round($prom1->SOLIDOS_CREMOSOS2 + $prom1->MARGARINAS2 + $prom1->ACEITES2);
                $TOTALPT = $prom1->ACEITES + $prom1->MARGARINAS + $prom1->SOLIDOS_CREMOSOS;
                $totalProd = intval(round($prom1->ACEITES, 3) + round($prom1->MARGARINAS, 3) + round($prom1->SOLIDOS_CREMOSOS, 3));
                $infoINDU = intval(round($prom1->INDUSTRIALES2));
                $infoOTROS = intval(round($prom1->ACIDOS_GRASOS_ACIDULADO2));
                $infoSERVM = intval(round($prom1->SERVICIO_MAQUILA2));
                $TOTALOT = round($prom1->INDUSTRIALES2 + $prom1->ACIDOS_GRASOS_ACIDULADO2 + $prom1->SERVICIO_MAQUILA2);
                $infoTOLALO = $infoINDU + $infoOTROS + $infoSERVM;
                $TOTALO = intval(round($prom1->INDUSTRIALES + $prom1->ACIDOS_GRASOS_ACIDULADO + $prom1->SERVICIO_MAQUILA));
                $infoTOTCOSV = $TOTALOT + $infoTOTP;
                $TOTALV = $totalProd + $TOTALO;
                $infoTOTALBR = $TOTALV - $infoTOTCOSV;
                array_push($formates2, [
                    $infoACEITES, $infoMarga, $infoSOLID, $infoTOTP, $infoINDU,  $infoOTROS,
                    $infoSERVM, intval(round($TOTALOT)), intval(round($infoTOTCOSV)), intval(round($infoTOTALBR))
                ]);
            }
            $sumaProm = [];
            for ($i = 0; $i < count($formates2[0]); $i++) {
                $suma = 0;
                foreach ($formates2 as $prom) {
                    $suma += $prom[$i];
                }
                array_push($sumaProm, intval(round($suma / count($formates))));
            }
            //array_push($formates,$sumaProm);

            $c++;
        } else {
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
            $infoACEITES = intval(round($info->ACEITES2, 5));
            $porceAc = round($info->ACEITES2 * 100 / $info->ACEITES) . '%';
            $infoMarga = intval(round($info->MARGARINAS2, 5));
            $porceMarga = round($info->MARGARINAS2 * 100 / $info->MARGARINAS, 2) . '%';
            $infoSOLID = intval(round($info->SOLIDOS_CREMOSOS2, 5));
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
            $infoSERVM = intval(round($info->SERVICIO_MAQUILA2, 5));
            $porceSERVM = round($info->SERVICIO_MAQUILA2 * 100 / $info->SERVICIO_MAQUILA, 2) . '%';
            $TOTALOT = round($info->INDUSTRIALES2 + $info->ACIDOS_GRASOS_ACIDULADO2 + $info->SERVICIO_MAQUILA2);
            $infoTOLALO = $infoINDU + $infoOTROS + $infoSERVM;
            $TOTALO = intval(round($info->INDUSTRIALES + $info->ACIDOS_GRASOS_ACIDULADO + $info->SERVICIO_MAQUILA));
            $porceTOTALO = round($infoTOLALO * 100 / $TOTALO, 2) . '%';
            $infoTOTCOSV = $TOTALOT + $infoTOTP;
            $TOTALV = $totalProd + $TOTALO;
            $porceTOTCOSV = round($infoTOTCOSV * 100 / $TOTALV, 2) . '%';
            $infoTOTALBR = $TOTALV - $infoTOTCOSV;
            $porceTOTALBR =  round($infoTOTALBR * 100 / $TOTALV, 2) . '%';
            array_push($formates, [$infoACEITES, $porceAc, $infoMarga, $porceMarga, $infoSOLID, $porceSOLID, $infoTOTP, $porceTOTALP, $infoINDU, $porceINDU, $infoOTROS, $porceOTROS, $infoSERVM, $porceSERVM, intval(round($TOTALOT)), $porceTOTALO, intval(round($infoTOTCOSV)), $porceTOTCOSV, intval(round($infoTOTALBR)), $porceTOTALBR]);
            array_push($acumPorceSinSu, [$infoACEITESP, $infoMARGARINASP, $infoSOLIDOS_CREMOSOSP, $TOTALPP, $infoINDUSTRIALESP, $infoOTROSP, $infoSERVICIO_MAQUILAP, $TOTALOP, $TOTALVP]);
            array_push($acumSinSum, [$infoACEITES, $infoMarga, $infoSOLID, $infoTOTP, $infoINDU, $infoOTROS, $infoSERVM, $TOTALOT, $infoTOTCOSV, $infoTOTALBR]);
            array_push($mes, ['mes' => $dateObject]);
            foreach ($infoSales as $prom1) {
                $infoACEITES = intval(round($prom1->ACEITES2));
                $infoMarga = intval(round($prom1->MARGARINAS2));
                $infoSOLID = intval(round($prom1->SOLIDOS_CREMOSOS2));
                $infoTOTP = round($prom1->SOLIDOS_CREMOSOS2 + $prom1->MARGARINAS2 + $prom1->ACEITES2);
                $TOTALPT = $prom1->ACEITES + $prom1->MARGARINAS + $prom1->SOLIDOS_CREMOSOS;
                $totalProd = intval(round($prom1->ACEITES, 3) + round($prom1->MARGARINAS, 3) + round($prom1->SOLIDOS_CREMOSOS, 3));
                $infoINDU = intval(round($prom1->INDUSTRIALES2));
                $infoOTROS = intval(round($prom1->ACIDOS_GRASOS_ACIDULADO2));
                $infoSERVM = intval(round($prom1->SERVICIO_MAQUILA2));
                $TOTALOT = round($prom1->INDUSTRIALES2 + $prom1->ACIDOS_GRASOS_ACIDULADO2 + $prom1->SERVICIO_MAQUILA2);
                $infoTOLALO = $infoINDU + $infoOTROS + $infoSERVM;
                $TOTALO = intval(round($prom1->INDUSTRIALES + $prom1->ACIDOS_GRASOS_ACIDULADO + $prom1->SERVICIO_MAQUILA));
                $infoTOTCOSV = $TOTALOT + $infoTOTP;
                $TOTALV = $totalProd + $TOTALO;
                $infoTOTALBR = $TOTALV - $infoTOTCOSV;
                array_push($formates2, [
                    $infoACEITES, $infoMarga, $infoSOLID, $infoTOTP, $infoINDU,  $infoOTROS,
                    $infoSERVM, intval(round($TOTALOT)), intval(round($infoTOTCOSV)), intval(round($infoTOTALBR))
                ]);
            }
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
    array_push($acumulados, $acumEnt[9]);
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



    return $formates;
    } 
       

}