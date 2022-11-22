<?php
namespace App\Http\Traits;

use DateTime;
use Illuminate\Support\Facades\DB;

trait CostosTrait {
    
    public function TablaCostos($fechaIni,$fechaFin){
        if($fechaIni != null){
            $fechaIni = $fechaIni;
            $fechaFin = $fechaFin;
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->whereBetween('INF_D_FECHAS',[$fechaIni,$fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoSales= $infoSales->toArray();
        }else{
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoSales = $infoSales->toArray();
            $fechaIni = null;
            $fechaFin = null;
        }
    $formates = [];
    $formates2 = [];
    $mes = [];
    $acumPorceSinSu = [];
    $c = 1;
    $acumSinSum = [];
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
                    $ventasNetasTabla = $this->TablaVentas($fechaIni, $fechaFin);
                    $ventasNetasTabla = array_slice($ventasNetasTabla, 0, 3);
                    $sumaVentas = [];
                    for ($i = 0; $i < count($ventasNetasTabla[0]); $i++) {
                        $suma = 0;
                        foreach ($ventasNetasTabla as $prom) {
                            $suma += $prom[$i];
                        }
                        array_push($sumaVentas, intval(round($suma / 3)));
                    }

                    $formEdit = $formates;
                    $sumaCostos = [];
                    for ($i = 0; $i < count($formEdit[0]); $i++) {
                        $suma = 0;
                        foreach ($formEdit as $prom) {
                            if ($i % 2 == 0) {
                                $suma += $prom[$i];
                            }
                        }
                        array_push($sumaCostos, intval(round($suma / 3)));
                    }
                    $cuenta = count($sumaCostos);
                    for ($i = 0; $i < $cuenta; $i++) {
                        if ($i % 2 == 0) {
                        } else {
                            unset($sumaCostos[$i]);
                        }
                    }
                    $sumaCostos = array_values($sumaCostos);
                    $sumfinals = [];
                    for ($i = 0; $i < count($sumaVentas); $i++) {
                        array_push($sumfinals, $sumaCostos[$i]);
                        array_push($sumfinals, round($sumaCostos[$i] * 100 / $sumaVentas[$i], 2) . '%');
                    }
                    array_push($sumfinals, $sumaCostos[$i]);
                    array_push($sumfinals, round($sumaCostos[$i] * 100 / $sumaVentas[8], 2) . '%');

                    array_push($formates, $sumfinals);
                    $c++;
                    break;
                case $c > 3 && $c < 8:
                    $formEdit1 = array_slice($formates, 4, 3);
                    $ventasNetasTabla = $this->TablaVentas($fechaIni, $fechaFin);
                    $ventasNetasTabla = array_slice($ventasNetasTabla, 3, 3);
                    $sumaVentas = [];
                    for ($i = 0; $i < count($ventasNetasTabla[0]); $i++) {
                        $suma = 0;
                        foreach ($ventasNetasTabla as $prom) {
                            $suma += $prom[$i];
                        }
                        array_push($sumaVentas, intval(round($suma / 3)));
                    }

                    $formEdit = $formates;
                    $sumaCostos = [];
                    for ($i = 0; $i < count($formEdit1[0]); $i++) {
                        $suma = 0;
                        foreach ($formEdit1 as $prom) {
                            if ($i % 2 == 0) {
                                $suma += $prom[$i];
                            }
                        }
                        array_push($sumaCostos, intval(round($suma / 3)));
                    }
                    $cuenta = count($sumaCostos);
                    for ($i = 0; $i < $cuenta; $i++) {
                        if ($i % 2 == 0) {
                        } else {
                            unset($sumaCostos[$i]);
                        }
                    }
                    $sumaCostos = array_values($sumaCostos);
                    $sumfinals = [];
                    for ($i = 0; $i < count($sumaVentas); $i++) {
                        array_push($sumfinals, $sumaCostos[$i]);
                        array_push($sumfinals, round($sumaCostos[$i] * 100 / $sumaVentas[$i], 2) . '%');
                    }
                    array_push($sumfinals, $sumaCostos[$i]);
                    array_push($sumfinals, round($sumaCostos[$i] * 100 / $sumaVentas[8], 2) . '%');
                    array_push($formates, $sumfinals);
                    $c++;
                    break;
                case $c > 7 && $c <= 11:
                    $formEdit2 = array_slice($formates, 8, 3);
                    $ventasNetasTabla = $this->TablaVentas($fechaIni, $fechaFin);
                    $ventasNetasTabla = array_slice($ventasNetasTabla, 6, 3);
                    $sumaVentas = [];
                    for ($i = 0; $i < count($ventasNetasTabla[0]); $i++) {
                        $suma = 0;
                        foreach ($ventasNetasTabla as $prom) {
                            $suma += $prom[$i];
                        }
                        array_push($sumaVentas, intval(round($suma / 3)));
                    }

                    $formEdit = $formates;
                    $sumaCostos = [];
                    for ($i = 0; $i < count($formEdit2[0]); $i++) {
                        $suma = 0;
                        foreach ($formEdit2 as $prom) {
                            if ($i % 2 == 0) {
                                $suma += $prom[$i];
                            }
                        }
                        array_push($sumaCostos, intval(round($suma / 3)));
                    }
                    $cuenta = count($sumaCostos);
                    for ($i = 0; $i < $cuenta; $i++) {
                        if ($i % 2 == 0) {
                        } else {
                            unset($sumaCostos[$i]);
                        }
                    }
                    $sumaCostos = array_values($sumaCostos);
                    $sumfinals = [];
                    for ($i = 0; $i < count($sumaVentas); $i++) {
                        array_push($sumfinals, $sumaCostos[$i]);
                        array_push($sumfinals, round($sumaCostos[$i] * 100 / $sumaVentas[$i], 2) . '%');
                    }
                    array_push($sumfinals, $sumaCostos[$i]);
                    array_push($sumfinals, round($sumaCostos[$i] * 100 / $sumaVentas[8], 2) . '%');
                    array_push($formates, $sumfinals);
                    $c++;
                    break;
                case $c > 11:
                    $formEdit2 = array_slice($formates, 12, 3);
                    $ventasNetasTabla = $this->TablaVentas($fechaIni, $fechaFin);
                    $ventasNetasTabla = array_slice($ventasNetasTabla, 9, 3);
                    $sumaVentas = [];
                    for ($i = 0; $i < count($ventasNetasTabla[0]); $i++) {
                        $suma = 0;
                        foreach ($ventasNetasTabla as $prom) {
                            $suma += $prom[$i];
                        }
                        array_push($sumaVentas, intval(round($suma / 3)));
                    }

                    $formEdit = $formates;
                    $sumaCostos = [];
                    for ($i = 0; $i < count($formEdit2[0]); $i++) {
                        $suma = 0;
                        foreach ($formEdit2 as $prom) {
                            if ($i % 2 == 0) {
                                $suma += $prom[$i];
                            }
                        }
                        array_push($sumaCostos, intval(round($suma / 3)));
                    }
                    $cuenta = count($sumaCostos);
                    for ($i = 0; $i < $cuenta; $i++) {
                        if ($i % 2 == 0) {
                        } else {
                            unset($sumaCostos[$i]);
                        }
                    }
                    $sumaCostos = array_values($sumaCostos);
                    $sumfinals = [];
                    for ($i = 0; $i < count($sumaVentas); $i++) {
                        array_push($sumfinals, $sumaCostos[$i]);
                        array_push($sumfinals, round($sumaCostos[$i] * 100 / $sumaVentas[$i], 2) . '%');
                    }
                    array_push($sumfinals, $sumaCostos[$i]);
                    array_push($sumfinals, round($sumaCostos[$i] * 100 / $sumaVentas[8], 2) . '%');
                    array_push($formates, $sumfinals);
                    $c++;
                    break;
            }
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