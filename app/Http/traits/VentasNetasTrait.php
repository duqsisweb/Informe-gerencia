<?php

namespace App\Http\Traits;

use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

trait VentasNetasTrait
{

    public function TablaVentas($fechaIni, $fechaFin)
    {
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
            foreach ($infoSales as $info) {

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
        
    }
}
