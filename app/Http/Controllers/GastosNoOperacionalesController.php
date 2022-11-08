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
}
