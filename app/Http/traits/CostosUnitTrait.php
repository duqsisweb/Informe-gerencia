<?php
namespace App\Http\Traits;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait CostosUnitTrait {
    
    
    public function TablaCostosUnit($fechaIni, $fechaFin)
    {
        if($fechaIni != null){
            $fechaIni = $fechaIni;
            $fechaFin = $fechaFin;
            $infoCosts = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->whereBetween('INF_D_FECHAS',[$fechaIni,$fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoCosts= $infoCosts->toArray();
            $infoUnits = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->whereBetween('INF_D_FECHAS',[$fechaIni,$fechaFin])->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoUnits= $infoUnits->toArray();
        }else{
            $infoCosts = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS', 'asc')->get();
            $infoCosts = $infoCosts->toArray();
            $infoUnits = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS', 'asc')->get();            
            $infoUnits= $infoUnits->toArray();
            $fechaIni = null;
            $fechaFin = null;
        }

        $data1 = [];
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
            array_push($data1, [
                $aceites, $aceiteDiv, $margarinas, $margarinasDiv, $solidCrem, $solidCreDiv, $industriales,
                $induastrialesDiv, $otrosAcGr, $otrosAcGrDiv,
                $serviciosMqu, $serviciosMaqDiv, $infoTOTCOSV, $totPt, $totVent
            ]);
            array_push($sumaVn, [$serviciosMaqDiv, $totVent]);
            array_push($venNe, [$aceiteDiv, $margarinasDiv, $solidCreDiv, $induastrialesDiv, $otrosAcGrDiv, $serviciosMaqDiv]);
            array_push($sumaPrd, [$aceites, $margarinas, $solidCrem, $industriales, $otrosAcGr, $serviciosMqu, $infoTOTCOSV]);
            array_push($ttven,intval(round($totVent)));
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
            $promVenNetT= intval(round($suma/ count($infoCosts),2).'%');
        }
        $resPromVenNe= $promVenNetT-$promVenNe[5];
        $t127= intval(round($promCosVen[6]/$promTonsTot[6]));
        $t113= intval(round($resPromVenNe/$promTonsTot[6]));
        $t129= intval(round($t113-$t127));
        array_push($promedios,$t127);
        array_push($promedios,round($t127/$t113,2).'%');
        array_push($promedios,$t129);
        $porceUtilBruUnit= round($t129/$t113,2);
        array_push($promedios,$porceUtilBruUnit.'%');
        //dd($promedios);
        
        
        
        
        
        array_push($formates,$acumulados);
        array_push($formates,$promedios);
       
        return $formates;
    }
}