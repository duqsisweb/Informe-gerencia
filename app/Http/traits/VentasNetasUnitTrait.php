<?php

namespace App\Http\Traits;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait VentasNetasUnitTrait
{


    public function TablaVentasUnit($fechaIni, $fechaFin)
    {
        if ($fechaIni != null) {
            $fechaIni = $fechaIni;
            $fechaFin = $fechaFin;
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->whereBetween('INF_D_FECHAS', [$fechaIni, $fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoSales = $infoSales->toArray();
            $infoTons = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->whereBetween('INF_D_FECHAS', [$fechaIni, $fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoTons = $infoTons->toArray();
        } else {
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoSales = $infoSales->toArray();
            $infoTons = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoTons = $infoTons->toArray();
            $fechaIni = null;
            $fechaFin = null;
        }
        $infos = [];
        $infoTs = [];
        $dates = [];
        //informacion de vista ventas netas
        foreach ($infoSales as $infoD) {
            $aceiteUnit = round($infoD->ACEITES, 5);
            $margaUnit = round($infoD->MARGARINAS, 5);
            $solCrUnit = round($infoD->SOLIDOS_CREMOSOS, 5);
            $totlPUnit = $aceiteUnit + $margaUnit + $solCrUnit;
            $indusUnit = round($infoD->INDUSTRIALES, 5);
            $aglAcid = round($infoD->ACIDOS_GRASOS_ACIDULADO, 5);
            $servMaq = round($infoD->SERVICIO_MAQUILA, 5);
            $totTBL = round(($totlPUnit + $indusUnit + $aglAcid + $servMaq) - $servMaq);
            $dateObject = DateTime::createFromFormat('m', $infoD->INF_D_MES)->format('F');
            array_push($infos, [$aceiteUnit, $margaUnit, $solCrUnit, $totlPUnit, $indusUnit, $aglAcid, $servMaq, $totTBL, $dateObject]);
        }
        //fin informacion
        //informacion de vista ventas toneladas
        foreach ($infoTons as $infoT) {
            $aceiUnit2 = round($infoT->TON_ACEITES, 5);
            $margaUnit2 = round($infoT->TON_MARGARINAS, 5);
            $solCrUnit2 = round($infoT->TON_SOLIDOS_CREMOSOS, 5);
            $totlPTUnit2 = $aceiUnit2 + $margaUnit2 + $solCrUnit2;
            $indusUnit2 = round($infoT->TON_INDUSTRIALES_OLEO, 5);
            $agAcid2 = round($infoT->TON_ACIDOS_GRASOS_ACIDULADO, 5);
            $servMaq2 = round($infoT->TON_SERVICIO_MAQUILA, 5);
            $totlVen2 = intval($totlPTUnit2) + intval($infoT->TON_INDUSTRIALES_OLEO) + intval(round($infoT->TON_ACIDOS_GRASOS_ACIDULADO));
            array_push($infoTs, [$aceiUnit2, $margaUnit2, $solCrUnit2, $totlPTUnit2, $indusUnit2, $agAcid2, $servMaq2, $totlVen2]);
        }
        //fin de informacion
        //contador de posiciones en arreglo de registros
        $amount = count($infoTs);
        //fin contador
        $c = 1;
        if ($c == 3 || $c == 6 || $c == 9 || $c == 12) {
            for ($i = 0; $i < $amount; $i++) {
                $divAceit = $infos[$i][0] / $infoTs[$i][0];
                $divMarga = $infos[$i][1] / $infoTs[$i][1];
                $divSolCr = $infos[$i][2] / $infoTs[$i][2];
                $divTotlPt = $infos[$i][3] / $infoTs[$i][3];
                $divIndus = $infos[$i][4] / $infoTs[$i][4];
                $divAgAc = $infos[$i][5] / $infoTs[$i][5];
                $divServMaq = $infos[$i][6] / $infoTs[$i][6];
                $divTotlVen = $infos[$i][7] / $infoTs[$i][7];
                $mes = $infos[$i][8];

                array_push($dates, [
                    intval($divAceit), intval(round($divMarga)), intval(round($divSolCr)), intval(round($divTotlPt)),
                    intval(round($divIndus)), intval(round($divAgAc)), intval(round($divServMaq)), intval(round($divTotlVen))
                ]);
            }
            $c++;
        } else {
            for ($i = 0; $i < $amount; $i++) {
                $divAceit = $infos[$i][0] / $infoTs[$i][0];
                $divMarga = $infos[$i][1] / $infoTs[$i][1];
                $divSolCr = $infos[$i][2] / $infoTs[$i][2];
                $divTotlPt = $infos[$i][3] / $infoTs[$i][3];
                $divIndus = $infos[$i][4] / $infoTs[$i][4];
                $divAgAc = $infos[$i][5] / $infoTs[$i][5];
                $divServMaq = $infos[$i][6] / $infoTs[$i][6];
                $divTotlVen = $infos[$i][7] / $infoTs[$i][7];
                $mes = $infos[$i][8];

                array_push($dates, [
                    intval($divAceit), intval(round($divMarga)), intval(round($divSolCr)), intval(round($divTotlPt)),
                    intval(round($divIndus)), intval(round($divAgAc)), intval(round($divServMaq)), intval(round($divTotlVen))
                ]);
            }
            $c++;
        }
        $sumatoriasV = [];
        foreach ($infoSales as $sums) {
            $aceites =  round($sums->ACEITES, 5);
            $margarinas =  round($sums->MARGARINAS, 5);
            $solidCre =  round($sums->SOLIDOS_CREMOSOS, 5);
            $totPrTer = $aceites + $margarinas + $solidCre;
            $industriales =  round($sums->INDUSTRIALES, 5);
            $otros =  round($sums->ACIDOS_GRASOS_ACIDULADO, 5);
            $serviMaqui =  round($sums->SERVICIO_MAQUILA, 5);
            $totVen = $totPrTer + $industriales + $otros + $serviMaqui;
            array_push($sumatoriasV, [$aceites, $margarinas, $solidCre, $totPrTer, $industriales, $otros, $serviMaqui, $totVen]);
        }
        $sumatoriasT = [];
        foreach ($infoTons as $sumst) {
            $aceites =  round($sumst->TON_ACEITES, 5);
            $margarinas =  round($sumst->TON_MARGARINAS, 5);
            $solidCre =  round($sumst->TON_SOLIDOS_CREMOSOS, 5);
            $totPt = $aceites + $margarinas + $solidCre;
            $industriales =  round($sumst->TON_INDUSTRIALES_OLEO, 5);
            $otros =  round($sumst->TON_ACIDOS_GRASOS_ACIDULADO, 5);
            $serviMaqui =  round($sumst->TON_SERVICIO_MAQUILA, 5);
            $venTon = $totPt + $industriales + $otros;
            array_push($sumatoriasT, [$aceites, $margarinas, $solidCre, $totPt, $industriales, $otros, $serviMaqui, $venTon]);
        }
        $sumsV = [];
        $promV = [];
        for ($i = 0; $i < (count($sumatoriasV[0])); $i++) {
            $suma = 0;
            foreach ($sumatoriasV as $sum) {
                $suma += $sum[$i];
            }
            $sumaV = $suma;
            array_push($sumsV, intval(round($sumaV)));
            array_push($promV, intval(round($sumaV)) / count($infoSales));
        };

        $sumsT = [];
        $promT = [];
        for ($i = 0; $i < count($sumatoriasT[0]); $i++) {
            $suma = 0;
            foreach ($sumatoriasT as $sum) {
                $suma += $sum[$i];
            }
            $sumaT = $suma;
            array_push($sumsT, intval(round($sumaT)));
            array_push($promT, intval(round($sumaT)) / count($infoSales));
        };


        $acumulado = [];
        for ($i = 0; $i < (count($sumsT) - 1); $i++) {
            array_push($acumulado, $sumsV[$i] / $sumsT[$i]);
        }
        array_push($acumulado, (intval(round($sumsV[7] - $sumsV[6]) / $sumsT[7])));
        array_push($dates, $acumulado);

        $promedio = [];
        for ($i = 0; $i < (count($promV) - 1); $i++) {
            array_push($promedio, intval(round($promV[$i] / $promT[$i])));
        }
        array_push($promedio, (intval(round($promV[7] - $promV[6]) / $promT[7])));
        array_push($dates, $promedio);

        $form = 0;
        foreach ($dates as $form) {
            $form = count($form);
        }
        return $dates;
    }
}
