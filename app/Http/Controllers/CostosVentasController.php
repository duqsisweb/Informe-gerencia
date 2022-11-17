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
    public function total_costs(Request $request)
    {
        if($request->filter1 != null){
            $fechaIni = $request->filter1.'-1';
            $fechaFin = $request->filter2.'-1';
            //dd($fechaFin);
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->whereBetween('INF_D_FECHAS',[$fechaIni,$fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoSales= $infoSales->toArray();
        }else{
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoSales->toArray();
            $infoSales = $infoSales->toArray();
        }

        $headers = ['ACEITES', 'PORCENTAJE ACEITES', 'MARGARINAS', 'PORCENTAJE MARGARINAS', 'SOLIDOS Y CREMOSOS', 'PORCENTAJE SOLIDOS Y CREMOSOS', 'TOTAL PRODUCTO TERMINADO', 'PORCENTAJE TOTAL PRODUCTO TERMINADO', 'INDUSTRIALES', 'PORCENTAJE INDUSTRIALES', 'OTROS PRODUCTOS', 'PORCENTAJE OTROS PRODUCTOS', 'SERVICIO DE MAQUILA', 'PORCENTAJE SERVICIO DE MAQUILA', 'TOTAL OTROS', 'PORCENTAJE TOTAL OTROS', 'TOTAL COSTOS DE VENTAS', 'PORCENTAJE TOTAL COSTOS DE VENTAS', 'UTILIDAD BRUTA', 'PORCENTAJE UTILIDAD BRUTA'];
        $formates = [];
        $formates2 = [];
        $mes = [];
        $c=1;
        foreach ($infoSales as $info) {
            if($c == 3 || $c == 6 || $c == 9 || $c == 12 ){

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
                array_push($mes, ['mes' => 'TRIMESTRE']);
                $sumP=[];
                foreach($infoSales as $prom1){
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
                    array_push($formates2, [$infoACEITES, $infoMarga, $infoSOLID, $infoTOTP, $infoINDU,  $infoOTROS,
                     $infoSERVM, intval(round($TOTALOT)), intval(round($infoTOTCOSV)), intval(round($infoTOTALBR))]); 
                }
                dd($formates2);
                    $sumaProm = [];
                        for ($i = 0; $i < count($formates2[0]); $i++) {
                            $suma = 0;
                            foreach ($formates2 as $prom) {
                                $suma += $prom[$i];
                            }
                            array_push($sumaProm, intval(round($suma /count($formates))));
                        }
                        dd($sumaProm,$c);
                        //array_push($formates,$sumaProm);

                $c++;

            }else{
                
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
                foreach($infoSales as $prom1){
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
                    array_push($formates2, [$infoACEITES, $infoMarga, $infoSOLID, $infoTOTP, $infoINDU,  $infoOTROS,
                     $infoSERVM, intval(round($TOTALOT)), intval(round($infoTOTCOSV)), intval(round($infoTOTALBR))]); 
                }
                $c++;
            }
            
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

        $sumatoriasC = [];
        $promC=[];
        for ($i = 0; $i < count($promediosC[0]); $i++) {
            $suma = 0;
            foreach ($promediosC as $prom) {
                $suma += $prom[$i];
            }
            array_push($sumatoriasC, [intval(round($suma))]);
            array_push($promC, [intval(round($suma / count($infoSales)))]);
        }
        //generando arreglo de promedios por producto
        $porceP=[];
        for($i=0;$i<count($promediosC[0]);$i++){
            if($i != 9){
                $unid= $promC[$i][0];
                $calc= $promC[$i][0]/$promedios[$i][0];
                array_push($porceP,[$unid,round($calc,2).'%']);
            }else{
                $det= $promC[$i][0];
                $calc= $promC[$i][0]/$promedios[8][0];
                array_push($porceP,[$det,round($calc,2).'%']);
            }
        }
        //fin del arreglo

        //generando arreglo de acumulados por producto
        $sumsFinals=[];
        for($i=0;$i<count($promediosC[0]);$i++){
            if($i != 9){
                $det= $sumatoriasC[$i][0];
                $porce= $sumatoriasC[$i][0]/$sumatorias[$i][0];
                array_push($sumsFinals,[$det,round($porce,2).'%']);
            }else{
                $det= $sumatoriasC[$i][0];
                $porce= $sumatoriasC[$i][0]/$sumatorias[8][0];
                array_push($sumsFinals,[$det,round($porce,2).'%']);
            }
        }
        //fin de arreglo de acumulados por producto


        //generando arreglo con todos los acumulados
        $acumulados=[];
        foreach($sumsFinals as $ultimSum){
            array_push($acumulados,$ultimSum[0]);
            array_push($acumulados,$ultimSum[1]);
        }
        array_push($formates, $acumulados);
        //fin del arreglo

         //generando arreglo con todos los promedios
         $promediosF=[];
         foreach($porceP as $ultiProm){
             array_push($promediosF,$ultiProm[0]);
             array_push($promediosF,$ultiProm[1]);
         }
         array_push($formates, $promediosF);
         //fin del arreglo
        
        $form = 0;
        foreach ($formates as $form) {
            $form = count($form);
        }
        return view('TotalCosts\list_total_costs', ['dates' => $formates, 'headers' => $headers, 'mes' => $mes, 'contador' => $form]);
    }

    public function unit_sales_costs()
    {

        $infoCosts = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
        $infoCosts= $infoCosts->toArray();
        $infoUnits = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS', 'asc')->get();
        $infoUnits= $infoUnits->toArray();
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
        array_push($mes, ['mes' => 'ACUMULADO']);
        array_push($mes, ['mes' => 'PROMEDIO']);
        $data2 = [];
        foreach ($infoUnits as $info) {
            $aceites2 = round($info->TON_ACEITES, 5);
            $margarinas2 = round($info->TON_MARGARINAS, 5);
            $solidCrem2 = round($info->TON_SOLIDOS_CREMOSOS, 5);
            $industriales2 = round($info->TON_INDUSTRIALES_OLEO, 5);
            $otrosAcGr2 = round($info->TON_ACIDOS_GRASOS_ACIDULADO, 5);
            $serviciosMaq2 = round($info->TON_SERVICIO_MAQUILA, 5);
            $totp= $aceites2+$margarinas2+$solidCrem2;
            $sumTt = $totp + $industriales2 + $otrosAcGr2;
            array_push($data2, [$aceites2, $margarinas2, $solidCrem2, $industriales2, $otrosAcGr2, $serviciosMaq2,$sumTt]);
        }
        //sumatorias de Tons//
        $promTonsTot = [];
        $sumatoriasTons = [];
        for ($i = 0; $i < count($data2[0]); $i++) {
            $suma = 0;
            foreach ($data2 as $ton) {
                $suma += $ton[$i];
            }
            array_push($sumatoriasTons, intval(round($suma)));
            array_push($promTonsTot, intval(round($suma/count($infoCosts))));
        }
        //fin de sumatorias de tons 
        $promVenTot= intval(round($sumatoriasTons[6]/count($infoCosts)));


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
        
        $sumaPrd=[];
        $sumaVn=[];
        $venNe=[];
        foreach($infoCosts as $prod){
            //-----sumatorias de ventas de aceites
            $aceiteDiv = round($prod->ACEITES,5);
            $margarinasDiv = round($prod->MARGARINAS,5);
            $solidCreDiv = round($prod->SOLIDOS_CREMOSOS,5);
            $induastrialesDiv = round($prod->INDUSTRIALES,5);
            $otrosAcGrDiv = round($prod->ACIDOS_GRASOS_ACIDULADO,5);
            $serviciosMaqDiv = round($prod->SERVICIO_MAQUILA,5);
            $totVent = $aceiteDiv + $margarinasDiv + $solidCreDiv + $induastrialesDiv + $otrosAcGrDiv + $serviciosMaqDiv;
            //-----fin de sumatorias
            $aceites = round($prod->ACEITES2, 5);
            $margarinas = round($prod->MARGARINAS2, 5);
            $solidCrem = round($prod->SOLIDOS_CREMOSOS2, 5);
            $industriales = round($prod->INDUSTRIALES2, 5);
            $otrosAcGr = round($prod->ACIDOS_GRASOS_ACIDULADO2, 5);
            $serviciosMqu = round($prod->SERVICIO_MAQUILA2, 5);
            $TOTALOT = round($prod->INDUSTRIALES2 + $prod->ACIDOS_GRASOS_ACIDULADO2 + $prod->SERVICIO_MAQUILA2);
            $infoTOTP = round($prod->SOLIDOS_CREMOSOS2 + $prod->MARGARINAS2 + $prod->ACEITES2);
            $infoTOTCOSV = $TOTALOT + $infoTOTP;
            array_push($sumaVn,[$serviciosMaqDiv,$totVent]);
            array_push($venNe,[$aceiteDiv,$margarinasDiv,$solidCreDiv,$induastrialesDiv,$otrosAcGrDiv,$serviciosMaqDiv]);
            array_push($sumaPrd,[$aceites,$margarinas,$solidCrem,$industriales,$otrosAcGr,$serviciosMqu,$infoTOTCOSV]);
        }
        $promVenNe = [];
        for ($i = 0; $i < count($venNe[0]); $i++) {
            $suma = 0;
            foreach ($venNe as $prod) {
                $suma += $prod[$i]/count($infoCosts);
            }
            array_push($promVenNe, intval(round($suma)));
        }
        $promVenNeUni=[];
        for($i=0;$i<count($promVenNe);$i++){
            array_push($promVenNeUni, intval(round($promVenNe[$i]/$promTonsTot[$i])));
        }
            $suma = 0;
            foreach ($sumaPrd as $prod) {
                $suma += $prod[6];
            }
        $promTotCosVen= intval(round($suma/count($infoCosts)));
        $promTotCosVenUnit= intval(round($promTotCosVen/$promVenTot));

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
        $promSumSerMaq= intval(round($sumatoriasVn[0]/count($infoCosts)));
        $promSumTotV= intval(round($sumatoriasVn[1]/count($infoCosts)));
        $promVenTon= intval(round($sumatoriasTons[6]/count($infoCosts)));
        $promtotVenUni= ($promSumTotV-$promSumSerMaq)/$promVenTon;
        $promUtilBrUnit= intval(round($promtotVenUni/$promTotCosVenUnit));
        
        $divid=[];
        for($i=0;$i<count($infoCosts);$i++){
            $ac = round($sumaPrd[$i][0]/$data2[$i][0]);
            $mg = round($sumaPrd[$i][1]/$data2[$i][1]);
            $sc = round($sumaPrd[$i][2]/$data2[$i][2]);
            $ind= round($sumaPrd[$i][3]/$data2[$i][3]);
            $ol= round($sumaPrd[$i][4]/$data2[$i][4]);
            $mq= round($sumaPrd[$i][5]/$data2[$i][5]);
            array_push($divid,[$ac,$mg, $sc, $ind, $ol, $mq]);
        }
        //sumatorias de porcentajes ventas//
        $promedios = [];
        for ($i = 0; $i < count($divid[0]); $i++) {
            $suma = 0;
            foreach ($divid as $prod) {
                $suma += $prod[$i]/count($infoCosts);
            }
            array_push($promedios, intval(round($suma)));
        }  
        $pronfinal=[];
        for($i=0;$i<count($promVenNeUni);$i++){
            array_push($pronfinal,$promedios[$i]);
            array_push($pronfinal,round($promedios[$i]/$promVenNeUni[$i],2).'%');
        }
        array_push($pronfinal, $promTotCosVenUnit);
        array_push($pronfinal, round($promTotCosVenUnit/$promtotVenUni,2).'%');
        array_push($pronfinal, $promUtilBrUnit);
        array_push($pronfinal, round($promUtilBrUnit/$promtotVenUni,2).'%');

        //sumatorias de ventas//
        $sumatoriasAc = [];
        for ($i = 0; $i < count($sumaPrd[0]); $i++) {
            $suma = 0;
            foreach ($sumaPrd as $prod) {
                $suma += $prod[$i];
            }
            array_push($sumatoriasAc, intval(round($suma)));
        }
        $tonelsP= round($sumatoriasTons[6]/count($infoCosts));
        $totCosVen =round($sumatoriasAc[6]/count($infoCosts));
        $totCosVenP =$totCosVen/$tonelsP;

        //fin de sumatorias de ventas
        
        
        $acumuladF=[];
        for($i=0;$i<count($sumatoriasAc);$i++){
            array_push($acumuladF,intval(round($sumatoriasAc[$i]/$sumatoriasTons[$i])));
            array_push($acumuladF,round($sumatoriasTons[$i]/($sumatoriasAc[$i]/$sumatoriasTons[$i]),2).'%');
        }
        $totVen= round(($sumatoriasVn[1]-$sumatoriasVn[0])/$sumatoriasTons[6]);
        array_push($acumuladF,intval(round($acumuladF[12]/$totVen)));
        array_push($acumuladF,round(($acumuladF[12]/$totVen)/$totVen,2).'%');
        array_push($formates,$acumuladF);
        array_push($formates,$pronfinal);

        /* $promF=[];
        for($i=0;$i<count($acumuladF);$i++)
        array_push($promF,round($acumuladF[$i]/count($infoCosts))); */


        $form = 0;
        foreach ($formates as $form) {
            $form = count($form);
        }
        //dd($formates,$headers,$mes,$form); 
        return view('TotalCosts\list_total_costs_unit', ['dates' => $formates, 'headers' => $headers, 'mes' => $mes, 'contador' => $form]);
    }
}
