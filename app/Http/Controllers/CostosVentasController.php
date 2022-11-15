<?php

namespace App\Http\Controllers;

use DateTime;
use Doctrine\DBAL\Query\QueryBuilder;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\type;

class CostosVentasController extends Controller
{
    public function total_costs()
    {
        $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
        $headers = ['ACEITES', 'PORCENTAJE ACEITES', 'MARGARINAS', 'PORCENTAJE MARGARINAS', 'SOLIDOS Y CREMOSOS', 'PORCENTAJE SOLIDOS Y CREMOSOS', 'TOTAL PRODUCTO TERMINADO', 'PORCENTAJE TOTAL PRODUCTO TERMINADO', 'INDUSTRIALES', 'PORCENTAJE INDUSTRIALES', 'OTROS PRODUCTOS', 'PORCENTAJE OTROS PRODUCTOS', 'SERVICIO DE MAQUILA', 'PORCENTAJE SERVICIO DE MAQUILA', 'TOTAL OTROS', 'PORCENTAJE TOTAL OTROS', 'TOTAL COSTOS DE VENTAS', 'PORCENTAJE TOTAL COSTOS DE VENTAS', 'UTILIDAD BRUTA', 'PORCENTAJE UTILIDAD BRUTA'];
        $formates = [];
        $mes = [];
        foreach ($infoSales as $info) {
            $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
            $infoACEITES = intval(round($info->ACEITES2));
            $porceAc = round($info->ACEITES2 * 100 / $info->ACEITES) . '%';
            $infoMarga = intval(round($info->MARGARINAS2));
            $porceMarga = round($info->MARGARINAS2 * 100 / $info->MARGARINAS, 2) . '%';
            $infoSOLID = intval(round($info->SOLIDOS_CREMOSOS2));
            $porceSOLID = round($info->SOLIDOS_CREMOSOS2 * 100 / $info->SOLIDOS_CREMOSOS, 2) . '%';
            $infoTOTP = round($info->SOLIDOS_CREMOSOS2 + $info->MARGARINAS2 + $info->ACEITES2);
            $TOTALPT = $info->ACEITES + $info->MARGARINAS + $info->SOLIDOS_CREMOSOS;
            $porceTOTALP = round($infoTOTP * 100 / $TOTALPT, 2) . '%';
            $totalProd = intval(round($info->ACEITES, 3) + round($info->MARGARINAS, 3) + round($info->SOLIDOS_CREMOSOS, 3));
            $porceTOTP = round($infoTOTP * 100 / $totalProd, 2) . '%';
            $infoINDU = intval(round($info->INDUSTRIALES2));
            $porceINDU = round($info->INDUSTRIALES2 * 100 / round($info->INDUSTRIALES, 2), 2) . '%';
            $infoOTROS = intval(round($info->ACIDOS_GRASOS_ACIDULADO2));
            $porceOTROS = round($info->ACIDOS_GRASOS_ACIDULADO2 * 100 / $info->ACIDOS_GRASOS_ACIDULADO, 2) . '%';
            $infoSERVM = intval(round($info->SERVICIO_MAQUILA2));
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
            array_push($mes, ['mes' => $dateObject]);
        }
        array_push($mes, ['mes' => 'ACUMULADO']);
        array_push($mes, ['mes' => 'PROMEDIO']);

        //tabla de ventas sumatorias y porcentajes
        $prmediosOperados=[];
            foreach ($infoSales as $promedio) {
                $infoACEITESP =  round($promedio->ACEITES, 3);
                $infoMARGARINASP =  round($promedio->MARGARINAS, 3);
                $infoSOLIDOS_CREMOSOSP =  round($promedio->SOLIDOS_CREMOSOS, 3);
                $infoINDUSTRIALESP =  round($promedio->INDUSTRIALES, 3);
                $infoOTROSP =  round($promedio->ACIDOS_GRASOS_ACIDULADO, 3);
                $infoSERVICIO_MAQUILAP =  round($promedio->SERVICIO_MAQUILA, 3);
                $TOTALPP = $infoACEITESP + $infoMARGARINASP + $infoSOLIDOS_CREMOSOSP;
                $TOTALOP = $infoINDUSTRIALESP + $infoOTROSP + $infoSERVICIO_MAQUILAP;
                $TOTALVP = $TOTALPP + $TOTALOP;
                array_push($prmediosOperados, [$infoACEITESP, $infoMARGARINASP, $infoSOLIDOS_CREMOSOSP, $TOTALPP, $infoINDUSTRIALESP, $infoOTROSP, $infoSERVICIO_MAQUILAP, $TOTALOP, $TOTALVP]);
            }
            //dd($prmediosOperados);
            $promedios = [];
            $sumatorias = [];
            for ($i = 0; $i < count($prmediosOperados[0]); $i++) {
                $suma = 0;
                foreach ($prmediosOperados as $prom) {
                    $suma += $prom[$i];
                }
                array_push($promedios, [intval(round($suma / count($formates)))]);
                array_push($sumatorias, [intval(round($suma))]);
            }
            //fin tabla de ventas sumatorias y porcentajes







        $promediosC = [];
        foreach ($infoSales as $info) {
            $aceite = round($info->ACEITES2, 5);
            $margarina = round($info->MARGARINAS2, 5);
            $solidCre = round($info->SOLIDOS_CREMOSOS2, 5);
            $totProTer = $aceite+$margarina+$solidCre;
            $industriales = round($info->INDUSTRIALES2,5);
            $otros = round($info->ACIDOS_GRASOS_ACIDULADO2,5);
            $servicMaqu = round($info->SERVICIO_MAQUILA2,5);
            $totOtros= $industriales+$otros+$servicMaqu;
            $totalCosVen= $totProTer+$totOtros;
            $totalProdVen = round($info->ACEITES + $info->MARGARINAS + $info->SOLIDOS_CREMOSOS+$info->INDUSTRIALES + $info->ACIDOS_GRASOS_ACIDULADO + $info->SERVICIO_MAQUILA,5);
            $utilBrut= $totalProdVen-$totalCosVen;
            array_push($promediosC, [$aceite,$margarina,$solidCre,$totProTer,$industriales,$otros,$servicMaqu,$totOtros,$totalCosVen,$utilBrut]);
        }
        //dd($promedios);

        $sumatoriasC = [];
        $proms=[];
        for ($i = 0; $i < count($promediosC[0]); $i++) {
            $suma = 0;
            foreach ($promediosC as $prom) {
                $suma += $prom[$i];
            }
            array_push($sumatoriasC, [intval(round($suma))]);
            array_push($proms, [intval(round($suma / count($promedios)))]);
        }
        $sumsFinals=[];
        for($i=0;$i<count($promediosC[0]);$i++){
            if($i != 9){
                $det= $sumatoriasC[$i][0];
                $porce= $sumatoriasC[$i][0]/$sumatorias[$i][0];
                array_push($sumsFinals,[$det,round($porce,2).'%']);
                var_dump('diferente');
                
            }else{
                $det= $sumatoriasC[$i][0];
                $porce= $sumatoriasC[$i][0]/$sumatorias[8][0];
                array_push($sumsFinals,[$det,round($porce,2).'%']);
                var_dump('igual');
            }
        }
        array_push($formates, $sumsFinals);
        //array_push($formates, $promedios);
        $form = 0;
        foreach ($formates as $form) {
            $form = count($form);
        }
        return view('TotalCosts\list_total_costs', ['dates' => $formates, 'headers' => $headers, 'mes' => $mes, 'contador' => $form]);
    }

    public function unit_sales_costs()
    {

        $infoCosts = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
        $infoUnits = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS', 'asc')->get();
        $headers = [
            'ACEITES', 'PORCENTAJE ACEITES', 'MARGARINAS', 'PORCENTAJE MARGARINAS', 'SOLIDOS Y CREMOSOS', 'PORCENTAJE SOLIDOS Y CREMOSOS', 'INDUSTRIALES', 'PORCENTAJE INDUSTRIALES',
            'OTROS PRODUCTOS', 'PORCENTAJE OTROS PRODUCTOS', 'SERVICIO DE MAQUILA', 'PORCENTAJE SERVICIO DE MAQUILA', 'TOTAL COSTOS DE VENTAS', 'PORCENTAJE TOTAL COSTOS DE VENTAS', 'UTILIDAD BRUTA', 'PORCENTAJE UTILIDAD BRUTA'
        ];
        $data1 = [];
        $mes = [];
        foreach ($infoCosts as $info) {
            $aceites = round($info->ACEITES2, 2);
            $aceiteDiv = round($info->ACEITES);
            $margarinas = round($info->MARGARINAS2, 2);
            $margarinasDiv = round($info->MARGARINAS);
            $solidCrem = round($info->SOLIDOS_CREMOSOS2, 2);
            $solidCreDiv = round($info->SOLIDOS_CREMOSOS);
            $industriales = round($info->INDUSTRIALES2, 2);
            $induastrialesDiv = round($info->INDUSTRIALES);
            $otrosAcGr = round($info->ACIDOS_GRASOS_ACIDULADO2, 2);
            $otrosAcGrDiv = round($info->ACIDOS_GRASOS_ACIDULADO);
            $serviciosMqu = round($info->SERVICIO_MAQUILA2, 2);
            $serviciosMaqDiv = round($info->SERVICIO_MAQUILA);
            $TOTALOT = round($info->INDUSTRIALES2 + $info->ACIDOS_GRASOS_ACIDULADO2 + $info->SERVICIO_MAQUILA2);
            $infoTOTP = round($info->SOLIDOS_CREMOSOS2 + $info->MARGARINAS2 + $info->ACEITES2);
            $infoTOTCOSV = $TOTALOT + $infoTOTP;
            $totPt = $aceites + $margarinas + $solidCrem;
            $totVent = $aceiteDiv + $margarinasDiv + $solidCreDiv + $induastrialesDiv + $otrosAcGrDiv + $serviciosMaqDiv;
            $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
            array_push($data1, [
                $aceites, $aceiteDiv, $margarinas, $margarinasDiv, $solidCrem, $solidCreDiv, $industriales, $induastrialesDiv, $otrosAcGr, $otrosAcGrDiv,
                $serviciosMqu, $serviciosMaqDiv, $infoTOTCOSV, $totPt, $totVent
            ]);
            array_push($mes, ['mes' => $dateObject]);
        }
        //dd($data1); 
        $data2 = [];
        foreach ($infoUnits as $info) {
            $aceites2 = round($info->TON_ACEITES, 2);
            $margarinas2 = round($info->TON_MARGARINAS, 2);
            $solidCrem2 = round($info->TON_SOLIDOS_CREMOSOS, 2);
            $industriales2 = round($info->TON_INDUSTRIALES_OLEO, 2);
            $otrosAcGr2 = round($info->TON_ACIDOS_GRASOS_ACIDULADO, 2);
            $serviciosMaq2 = round($info->TON_SERVICIO_MAQUILA, 2);
            $sumTons = $industriales2 + $otrosAcGr2 + $aceites2 + $margarinas2 + $solidCrem2;
            array_push($data2, [$aceites2, $margarinas2, $solidCrem2, $industriales2, $otrosAcGr2, $serviciosMaq2, $sumTons]);
        }
        //dd($data2);  


        $formates = [];
        $count = count($data2) - 1;
        for ($i = 0; $i <= $count; $i++) {
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
        }
        $form = 0;
        foreach ($formates as $form) {
            $form = count($form);
        }
        //dd($formates,$headers,$mes,$form); 
        return view('TotalCosts\list_total_costs_unit', ['dates' => $formates, 'headers' => $headers, 'mes' => $mes, 'contador' => $form]);
    }
}
