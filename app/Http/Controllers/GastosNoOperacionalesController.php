<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GastosNoOperacionalesController extends Controller
{
    public function nonOperatinals(){
        $infoNoOpe = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS','asc')->get();
        $headers=['FINANCIEROS', 'PORCENTAJE FINANCIEROS', 'RETIRO DE ACTIVOS (LEASE BACK - AJUSTE INVENTARIOS)',
            'PORCENTAJE RETIRO DE ACTIVOS', 'GRAVAMEN MOVIMIENTO FINANCIERO (4*1000)', 'PORCENTAJE GRAVAMEN MOVIMIENTO', 'OTROS', 
            'PORCENTAJE OTROS', 'TOTAL NO OPERACIONALES', 'PORCENTAJE TOTAL NO OPERACIONALES','UTILIDAD ANTES DE IMPUESTOS',
            'PORCENTAJE UTILIDAD ANTES DE IMPUESTOS','EBITDA','PORCENTAJE EBITDA'];
        $fomDates= [];
        $mes= [];
        foreach($infoNoOpe as $data){
            $infoACEITES=  round($data->ACEITES,5);
            $infoMARGARINAS=  round($data->MARGARINAS,5);
            $infoSOLIDOS_CREMOSOS=  round($data->SOLIDOS_CREMOSOS,5);
            //informacion general tabla de ventas netas
            $infoINDUSTRIALES=  round($data->INDUSTRIALES,5);
            $infoOTROS=  round($data->ACIDOS_GRASOS_ACIDULADO,5);
            $infoSERVICIO_MAQUILA=  round($data->SERVICIO_MAQUILA,5);
            //consulta alterna
            $TOTALP = intval($infoACEITES+$infoMARGARINAS+$infoSOLIDOS_CREMOSOS);
            $TOTALO = intval($infoINDUSTRIALES+$infoOTROS+$infoSERVICIO_MAQUILA);
            $TOTALV = intval($TOTALP+$TOTALO);
            // fin consulta
            $infoTOTP= intval(round($data->SOLIDOS_CREMOSOS2+$data->MARGARINAS2+$data->ACEITES2));
            $depreAmorti= round($data->DEPRECIACIONES_AMORTIZACIONES);
            $gasVentas= round($data->GASTOS_VENTAS,2);
            $gastAdmin= round($data->GASTOS_ADMINISTRACION,5);
            $TOTSUMOTR=$TOTALO+$infoTOTP;
            $totGasOper= +$gastAdmin+$gasVentas+$depreAmorti;
            $UTLBRUTA= +$TOTALV-$TOTSUMOTR;
            $UtilOper= $UTLBRUTA-$totGasOper;


            $finan= intval(round($data->FINANCIEROS,2));
            $porceFinan= round($finan*100/$TOTALV,2).'%';
            $retActiv= intval(round($data->RETIRO_ACTIVOS));
            $porceActiv= round($retActiv*100/$TOTALV,2).'%';
            $gravFinan= intval(round($data->GRAVA_MOV_FINANCIERO));
            $porceGravFin= round($gravFinan*100/$TOTALV,2).'%';
            $otros= intval(round($data->OTROS));
            $porceOtros= round($otros*100/$TOTALV,2).'%';
            $totlNoOp= $finan+$retActiv+$gravFinan+$otros;
            $porceTotlNoOp= round($totlNoOp*100/$TOTALV,2).'%';
            $utilAntImp=   $UtilOper -$totlNoOp;
            $porceUtilAntImp= round($utilAntImp*100/$TOTALV,2).'%';
            $ebitda= intval(round($data->EBITDA));
            $porceEbtida= round($ebitda*100/$TOTALV,2).'%';
            $dateObject = DateTime::createFromFormat('m', $data->INF_D_MES)->format('F');

           array_push($fomDates,[$finan, $porceFinan, $retActiv, $porceActiv, $gravFinan, $porceGravFin, $otros, $porceOtros,
           $totlNoOp, $porceTotlNoOp, $utilAntImp, $porceUtilAntImp, $ebitda,$porceEbtida]);
           array_push($mes,['mes'=>$dateObject]);
        }
        $form = 0;
            foreach($fomDates as $form){
                $form = count($form);
            }
        return view('NonOperatingExpenses\list_non_operating_expenses',['headers'=>$headers, 'dates'=>$fomDates, 'mes'=>$mes, 'contador'=>$form ]);
    }

    public function unit_nonOperatinals(){
        $infoNoOpe = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->orderBy('INF_D_FECHAS','asc')->get();
        $infoTons = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ2')->orderBy('INF_D_FECHAS','asc')->get();
        $headers=['FINANCIEROS', 'PORCENTAJE FINANCIEROS', 'RETIRO DE ACTIVOS (LEASE BACK - AJUSTE INVENTARIOS)',
            'PORCENTAJE RETIRO DE ACTIVOS', 'GRAVAMEN MOVIMIENTO FINANCIERO (4*1000)', 'PORCENTAJE GRAVAMEN MOVIMIENTO', 'OTROS', 
            'PORCENTAJE OTROS', 'TOTAL NO OPERACIONALES', 'PORCENTAJE TOTAL NO OPERACIONALES','UTILIDAD ANTES DE IMPUESTOS',
            'PORCENTAJE UTILIDAD ANTES DE IMPUESTOS','EBITDA','PORCENTAJE EBITDA'];
        $data1=[];
        $mes=[];
        foreach($infoNoOpe as $info){
            $financiero = $info->FINANCIEROS;
            $totVEN = (round($info->ACEITES) + round($info->MARGARINAS) + round($info->SOLIDOS_CREMOSOS) + round($info->INDUSTRIALES) + round($info->ACIDOS_GRASOS_ACIDULADO) + round($info->SERVICIO_MAQUILA) - round($info->SERVICIO_MAQUILA));
            $retActiv = $info->RETIRO_ACTIVOS;
            $gravMov = $info->GRAVA_MOV_FINANCIERO;
            $otros = $info->OTROS;
            $gasAdmon = $info->GASTOS_ADMINISTRACION;
            $gasVentas = $info->GASTOS_VENTAS;
            $depresiaci = $info->DEPRECIACIONES_AMORTIZACIONES;
            $totGasOper = $gasAdmon+$gasVentas+$depresiaci;
            $infoTOT = round($info->SOLIDOS_CREMOSOS2 + $info->MARGARINAS2 + $info->ACEITES2)+round($info->INDUSTRIALES2 + $info->ACIDOS_GRASOS_ACIDULADO2 + $info->SERVICIO_MAQUILA2);
            $ebtida = $info->EBITDA;
            $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
            array_push($data1,[$financiero,$totVEN,$retActiv,$gravMov,$otros,$totGasOper,$infoTOT,$ebtida]);
            array_push($mes,['mes'=>$dateObject]);
        }
        //dd($data1);
        
        $data2=[];
        foreach($infoTons as $info2){
            $venTON = round($info2->TON_ACEITES + $info2->TON_MARGARINAS + $info2->TON_SOLIDOS_CREMOSOS + $info2->TON_INDUSTRIALES_OLEO + $info2->TON_ACIDOS_GRASOS_ACIDULADO);
            array_push($data2,[$venTON]);
        }
        //dd($data2);
        
        $amount = count($data2) - 1;
        $formOper = [];
        for($i=0;$i<=$amount;$i++){
            $financieroV= round($data1[$i][0],5)/round($data2[$i][0],5);
            $porceFinanc= round($financieroV,2)*100/intval($data1[$i][1]/$data2[$i][0]);
            $retActV= round($data1[$i][2],5)/round($data2[$i][0],5);
            $porceRerAc= round($retActV,2)*100/intval($data1[$i][1]/$data2[$i][0]);
            $gravMoV= round($data1[$i][3],5)/round($data2[$i][0],5);
            $porceGravMov= round($gravMoV,2)*100/intval($data1[$i][1]/$data2[$i][0]);
            $otrosV= round($data1[$i][4],5)/round($data2[$i][0],5);
            $porceOtros= round($otrosV,2)*100/intval($data1[$i][1]/$data2[$i][0]);
            $totNoOp= $financieroV+ $retActV+$gravMoV+$otrosV;
            $porceTotNoOp= $totNoOp*100/intval($data1[$i][1]/$data2[$i][0]);
            $totGasOperR= $data1[$i][5] / $data2[$i][0];
            $TOTVEN= round($data1[$i][1])/round($data2[$i][0]);
            $totCosVen= $data1[$i][6]/$data2[$i][0];
            $utilBrut= intval($TOTVEN-$totCosVen);
            $utilOper= intval(+$utilBrut-intval($totGasOperR));
            $utilAntImp= (+$utilOper-intval(round($totNoOp)));
            $porceUtilAntImp=  $utilAntImp*100/intval($data1[$i][1]/$data2[$i][0]);
            $ebtidaV= $data1[$i][7]/$data2[$i][0];
            $porceEbtida= round($ebtidaV,2)*100/intval($data1[$i][1]/$data2[$i][0]);
            
            //dd($ebtidaV);
            array_push($formOper,[intval(round($financieroV)),round($porceFinanc,2).'%', intval(round($retActV)),round($porceRerAc,2).'%'
                        ,intval(round($gravMoV)),round($porceGravMov,2).'%',intval(round($otrosV)),round($porceOtros,2).'%',intval(round($totNoOp))
                        ,round($porceTotNoOp,2).'%', intval(round($utilAntImp)),round($porceUtilAntImp,2).'%',intval(round($ebtidaV)),
                        round($porceEbtida,2).'%' ]);
            
        }
        //dd($formOper);
        $form = 0;
            foreach($formOper as $form){
                $form = count($form);
            }
        return view('NonOperatingExpenses\list_non_operating_expensesUnit',['headers'=>$headers, 'dates'=>$formOper, 'mes'=>$mes, 'contador'=>$form ]);
    }
}
