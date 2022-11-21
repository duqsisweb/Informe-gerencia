<?php

namespace App\Http\Traits;

use DateTime;
use Illuminate\Support\Facades\DB;

trait GastosNoOperTrait
{

    public function tablaGastosNoOperacionales($fechaIni, $fechaFin)
    {

        if($fechaIni != null){
            $fechaIni = $fechaIni.'-1';
            $fechaFin = $fechaFin.'-1';
            $infoGastos = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->whereBetween('INF_D_FECHAS', [$fechaIni, $fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoGastos = $infoGastos->toArray();
        } else {
            $infoNoOpe = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoNoOpe = $infoNoOpe->toArray();
        }

        $fomDates = [];
        foreach ($infoNoOpe as $data) {
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
            // fin consulta
            $infoTOTP = intval(round($data->SOLIDOS_CREMOSOS2 + $data->MARGARINAS2 + $data->ACEITES2));
            $depreAmorti = round($data->DEPRECIACIONES_AMORTIZACIONES);
            $gasVentas = round($data->GASTOS_VENTAS, 2);
            $gastAdmin = round($data->GASTOS_ADMINISTRACION, 5);
            $TOTSUMOTR = $TOTALO + $infoTOTP;
            $totGasOper = +$gastAdmin + $gasVentas + $depreAmorti;
            $UTLBRUTA = +$TOTALV - $TOTSUMOTR;
            $UtilOper = $UTLBRUTA - $totGasOper;


            $finan = intval(round($data->FINANCIEROS, 2));
            $porceFinan = round($finan * 100 / $TOTALV, 2) . '%';
            $retActiv = intval(round($data->RETIRO_ACTIVOS));
            $porceActiv = round($retActiv * 100 / $TOTALV, 2) . '%';
            $gravFinan = intval(round($data->GRAVA_MOV_FINANCIERO));
            $porceGravFin = round($gravFinan * 100 / $TOTALV, 2) . '%';
            $otros = intval(round($data->OTROS));
            $porceOtros = round($otros * 100 / $TOTALV, 2) . '%';
            $totlNoOp = $finan + $retActiv + $gravFinan + $otros;
            $porceTotlNoOp = round($totlNoOp * 100 / $TOTALV, 2) . '%';
            $utilAntImp =   $UtilOper - $totlNoOp;
            $porceUtilAntImp = round($utilAntImp * 100 / $TOTALV, 2) . '%';
            $ebitda = intval(round($data->EBITDA));
            $porceEbtida = round($ebitda * 100 / $TOTALV, 2) . '%';
            $dateObject = DateTime::createFromFormat('m', $data->INF_D_MES)->format('F');

            array_push($fomDates, [
                $finan, $porceFinan, $retActiv, $porceActiv, $gravFinan, $porceGravFin, $otros, $porceOtros,
                $totlNoOp, $porceTotlNoOp, $utilAntImp, $porceUtilAntImp, $ebitda, $porceEbtida
            ]);
        }


        $sumados = [];
        $ventTotales = [];
        foreach ($infoNoOpe as $infoOperations) {
            //----
            $infoACEITES =  round($infoOperations->ACEITES, 5);
            $infoMARGARINAS =  round($infoOperations->MARGARINAS, 5);
            $infoSOLIDOS_CREMOSOS =  round($infoOperations->SOLIDOS_CREMOSOS, 5);
            //informacion general tabla de ventas netas
            $infoINDUSTRIALES =  round($infoOperations->INDUSTRIALES, 5);
            $infoOTROS =  round($infoOperations->ACIDOS_GRASOS_ACIDULADO, 5);
            $infoSERVICIO_MAQUILA =  round($infoOperations->SERVICIO_MAQUILA, 5);
            //consulta alterna
            $TOTALP = intval($infoACEITES + $infoMARGARINAS + $infoSOLIDOS_CREMOSOS);
            $TOTALO = intval($infoINDUSTRIALES + $infoOTROS + $infoSERVICIO_MAQUILA);
            $TOTALV = intval($TOTALP + $TOTALO);
            // fin consulta
            $infoTOTP = intval(round($infoOperations->SOLIDOS_CREMOSOS2 + $infoOperations->MARGARINAS2 + $infoOperations->ACEITES2));
            $depreAmorti = round($infoOperations->DEPRECIACIONES_AMORTIZACIONES);
            $gasVentas = round($infoOperations->GASTOS_VENTAS, 2);
            $gastAdmin = round($infoOperations->GASTOS_ADMINISTRACION, 5);
            $TOTSUMOTR = $TOTALO + $infoTOTP;
            $totGasOper = +$gastAdmin + $gasVentas + $depreAmorti;
            $UTLBRUTA = +$TOTALV - $TOTSUMOTR;
            $UtilOper = $UTLBRUTA - $totGasOper;
            //----

            $finanN = round($infoOperations->FINANCIEROS, 5);
            $retActivN = round($infoOperations->RETIRO_ACTIVOS, 5);
            $gravFinanN = round($infoOperations->GRAVA_MOV_FINANCIERO, 5);
            $otrosN = round($infoOperations->OTROS, 5);
            $totlNoOpN = $finanN + $retActivN + $gravFinanN + $otrosN;
            $utilAntImpN =   $UtilOper - $totlNoOpN;
            $ebitdaN = round($infoOperations->EBITDA, 5);
            array_push($sumados, [$finanN, $retActivN, $gravFinanN, $otrosN, $totlNoOpN, $utilAntImpN, $ebitdaN]);
            array_push($ventTotales, $TOTALV);
        }

        //dd(count($sumados[0]));
        $sumatorias = [];
        $promedios = [];
        for ($i = 0; $i < count($sumados[0]); $i++) {
            $suma = 0;
            foreach ($sumados as $sum) {
                $suma += $sum[$i];
            }
            array_push($sumatorias, intval(round($suma)));
            array_push($promedios, intval(round($suma / count($infoNoOpe))));
        }

        for ($i = 0; $i < count($infoNoOpe); $i++) {
            $suma = 0;
            foreach ($ventTotales as $tot) {
                $suma += $tot;
            }
        }
        $sumtot = $suma;

        $acumulados = [];
        for ($i = 0; $i < count($sumados[0]); $i++) {
            $sumNo = $sumatorias[$i];
            $porceNo = $sumNo / $sumtot;
            array_push($acumulados, $sumNo);
            array_push($acumulados, round($porceNo, 2) . '%');
        }

        array_push($fomDates, $acumulados);


        $promediosNo = [];
        for ($i = 0; $i < count($sumados[0]); $i++) {
            $promNo = $promedios[$i];
            $porceNo = $promNo / ($sumtot / count($infoNoOpe));
            array_push($promediosNo, $promNo);
            array_push($promediosNo, round($porceNo, 2) . '%');
        }
        array_push($fomDates, $promediosNo);


        return $fomDates;
    }
}
