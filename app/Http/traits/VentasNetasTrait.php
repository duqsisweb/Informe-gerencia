<?php

namespace App\Http\Traits;

use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\VentasToneladasTrait;

trait VentasNetasTrait
{
    use VentasToneladasTrait;
    public function TablaVentas($fechaIni, $fechaFin)
    {
        try {

            if ($fechaIni != null) {
                $fechaIni = $fechaIni;
                $fechaFin = $fechaFin;
                $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->whereBetween('INF_D_FECHAS', [$fechaIni, $fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
                $infoSales = $infoSales->toArray();
            } else {
                $fechaIni = null;
                $fechaFin = null;
                $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
                $infoSales->toArray();
                $infoSales = $infoSales->toArray();
            }
            $formates = [];
            $cabeceras = ['ACEITES', 'MARGARINAS', 'SOLIDOS_CREMOSOS', 'TOTAL PRODUCTO TERMINADO', 'INDUSTRIALES', 'OTROS(AGL-ACIDULADO)', 'SERVICIO DE MAQUILA', 'TOTAL OTROS', 'TOTAL VENTAS'];

            $mes = [];
            $c = 1;
            foreach ($infoSales as $info) {

                if ($c == 3 || $c == 6 || $c == 9 || $c == 12) {
                    $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
                    $infoACEITES =  round($info->ACEITES, 5);
                    $infoMARGARINAS =  round($info->MARGARINAS, 5);
                    $infoSOLIDOS_CREMOSOS =  round($info->SOLIDOS_CREMOSOS, 5);
                    $infoINDUSTRIALES =  round($info->INDUSTRIALES, 5);
                    $infoOTROS =  round($info->ACIDOS_GRASOS_ACIDULADO, 5);
                    $infoSERVICIO_MAQUILA =  round($info->SERVICIO_MAQUILA, 5);
                    $TOTALP = $infoACEITES + $infoMARGARINAS + $infoSOLIDOS_CREMOSOS;
                    $TOTALO = $infoINDUSTRIALES + $infoOTROS + $infoSERVICIO_MAQUILA;
                    $TOTALV = $TOTALP + $TOTALO;
                    array_push($formates, [$infoACEITES, $infoMARGARINAS, intval(round($infoSOLIDOS_CREMOSOS)), $TOTALP, intval(round($infoINDUSTRIALES)), intval(round($infoOTROS)), intval(round($infoSERVICIO_MAQUILA)), $TOTALO, $TOTALV]);
                    array_push($mes, ['mes' => $dateObject]);
                    array_push($mes, ['mes' => 'TRIMESTRE']);
                    $formEdit = $formates;
                    switch ($c) {
                        case $c <= 3:
                            $formEdit = $formates;
                            $sumaProm = [];
                            for ($i = 0; $i < count($formEdit[0]); $i++) {
                                $suma = 0;
                                foreach ($formEdit as $prom) {
                                    $suma += $prom[$i];
                                }
                                array_push($sumaProm, intval(round($suma / 3)));
                            }
                            array_push($formates, $sumaProm);
                            $c++;
                            break;
                        case $c > 3 && $c <= 7:
                            $formEdit1 = $formates;
                            unset($formEdit1[0], $formEdit1[1], $formEdit1[2], $formEdit1[3]);
                            $sumaProm = [];
                            for ($i = 0; $i < count($formEdit1[4]); $i++) {
                                $suma = 0;
                                foreach ($formEdit1 as $prom) {
                                    $suma += $prom[$i];
                                }
                                array_push($sumaProm, intval(round($suma / 3)));
                            }
                            array_push($formates, $sumaProm);
                            $c++;
                            break;
                        case $c > 7 && $c <= 11:
                            $formEdit2 = $formates;
                            unset($formEdit2[0], $formEdit2[1], $formEdit2[2], $formEdit2[3], $formEdit2[4], $formEdit2[5], $formEdit2[6], $formEdit2[7]);
                            $sumaProm = [];
                            for ($i = 0; $i < count($formEdit2[8]); $i++) {
                                $suma = 0;
                                foreach ($formEdit2 as $prom) {
                                    $suma += $prom[$i];
                                }
                                array_push($sumaProm, intval(round($suma / 3)));
                            }
                            array_push($formates, $sumaProm);
                            $c++;
                            break;
                        case $c > 11:
                            $formEdit3 = $formates;
                            unset($formEdit3[0], $formEdit3[1], $formEdit3[2], $formEdit3[3], $formEdit3[4], $formEdit3[5], $formEdit3[6], $formEdit3[7], $formEdit3[8], $formEdit3[9], $formEdit3[10]);
                            $sumaProm = [];
                            for ($i = 0; $i < count($formEdit3[11]); $i++) {
                                $suma = 0;
                                foreach ($formEdit3 as $prom) {
                                    $suma += $prom[$i];
                                }
                                array_push($sumaProm, intval(round($suma / 3)));
                            }
                            array_push($formates, $sumaProm);
                            $c++;
                            break;
                    }
                } else {
                    $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
                    $infoACEITES =  round($info->ACEITES, 5);
                    $infoMARGARINAS =  round($info->MARGARINAS, 5);
                    $infoSOLIDOS_CREMOSOS =  round($info->SOLIDOS_CREMOSOS, 5);
                    $infoINDUSTRIALES =  round($info->INDUSTRIALES, 5);
                    $infoOTROS =  round($info->ACIDOS_GRASOS_ACIDULADO, 5);
                    $infoSERVICIO_MAQUILA =  round($info->SERVICIO_MAQUILA, 5);
                    $TOTALP = $infoACEITES + $infoMARGARINAS + $infoSOLIDOS_CREMOSOS;
                    $TOTALO = $infoINDUSTRIALES + $infoOTROS + $infoSERVICIO_MAQUILA;
                    $TOTALV = $TOTALP + $TOTALO;
                    array_push($formates, [$infoACEITES, $infoMARGARINAS, intval(round($infoSOLIDOS_CREMOSOS)), $TOTALP, intval(round($infoINDUSTRIALES)), intval(round($infoOTROS)), intval(round($infoSERVICIO_MAQUILA)), $TOTALO, $TOTALV]);
                    array_push($mes, ['mes' => $dateObject]);
                    $c++;
                }
            }
            array_push($mes, ['mes' => 'ACUMULADO']);
            array_push($mes, ['mes' => 'PROMEDIO']);
            $prmediosOperados = [];
            foreach ($infoSales as $promedio) {
                $infoACEITESP =  round($promedio->ACEITES, 5);
                $infoMARGARINASP =  round($promedio->MARGARINAS, 5);
                $infoSOLIDOS_CREMOSOSP =  round($promedio->SOLIDOS_CREMOSOS, 5);
                $infoINDUSTRIALESP =  round($promedio->INDUSTRIALES, 5);
                $infoOTROSP =  round($promedio->ACIDOS_GRASOS_ACIDULADO, 5);
                $infoSERVICIO_MAQUILAP =  round($promedio->SERVICIO_MAQUILA, 5);
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
                array_push($sumatorias, [intval(round($suma))]);
                array_push($promedios, [intval(round($suma / count($infoSales)))]);
            }
            array_push($formates, $sumatorias);
            array_push($formates, $promedios);

            return $formates;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
