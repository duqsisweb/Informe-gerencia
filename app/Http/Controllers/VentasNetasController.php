<?php

namespace App\Http\Controllers;

use App\Models\Total_sale;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;

class VentasNetasController extends Controller
{
    public function total_sales()
    {
        try {
            $infoSales = DB::connection('sqlsrv2')->table('TBL_RINFORME_JUNTA_DUQ')->select('INF_D_MES','ACEITES','MARGARINAS','SOLIDOS_CREMOSOS','INDUSTRIALES','ACIDOS_GRASOS_ACIDULADO','SERVICIO_MAQUILA')->orderBy('INF_D_FECHAS','asc')->get();
            $formates = [];
            $cabeceras = ['ACEITES','MARGARINAS','SOLIDOS_CREMOSOS','TOTAL PRODUCTO TERMINADO','INDUSTRIALES','OTROS(AGL-ACIDULADO)','SERVICIO DE MAQUILA','TOTAL OTROS','TOTAL VENTAS'];
            $mes=[];
            foreach($infoSales as $info){
                $dateObject = DateTime::createFromFormat('m', $info->INF_D_MES)->format('F');
                $infoACEITES=  round($info->ACEITES,3);
                $infoMARGARINAS=  round($info->MARGARINAS,3);
                $infoSOLIDOS_CREMOSOS=  round($info->SOLIDOS_CREMOSOS,3);
                $infoINDUSTRIALES=  $info->INDUSTRIALES;
                $infoOTROS=  intval($info->ACIDOS_GRASOS_ACIDULADO,0);
                $infoSERVICIO_MAQUILA=  intval($info->SERVICIO_MAQUILA,0);
                $TOTALP = $infoACEITES+$infoMARGARINAS+$infoSOLIDOS_CREMOSOS;
                $TOTALO = $infoINDUSTRIALES+$infoOTROS+$infoSERVICIO_MAQUILA;
                $TOTALV = $TOTALP+$TOTALO;
                array_push($formates,[$infoACEITES,$infoMARGARINAS,$infoSOLIDOS_CREMOSOS,$infoINDUSTRIALES,$infoOTROS,$infoSERVICIO_MAQUILA,$TOTALP,$TOTALO,$TOTALV]);
                array_push($mes,[ 'mes'=>$dateObject]);
            }
            $form = 0;
            foreach($formates as $form){
                $form = count($form);
            }

            return view('SalesTotal/list_sales_total', ['dates'=> $formates, 'headers'=>$cabeceras,'mes'=>$mes,'contador'=>$form]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}


//foreach ($infoSales as $infosale) {
                    //dd($infosale->MARGARINAS);
                    /* Total_sale::create([
                        'inf_nid' => $infosale->INF_NID,
                        'inf_dfecha_registro' => $infosale->INF_DFECHA_REGISTRO,
                        'inf_dhora_registro' => $infosale->INF_DHORA_REGISTRO,
                        'inf_d_anio' => $infosale->INF_D_ANIO,
                        'inf_d_mes' => $infosale->INF_D_MES,
                        'inf_d_fechas' => $infosale->INF_D_FECHAS,
                        'aceites' => $infosale->ACEITES,
                        'margarinas' => $infosale->MARGARINAS,
                        'solidos_cremosos' => $infosale->SOLIDOS_CREMOSOS,
                        'industriales' => $infosale->INDUSTRIALES,
                        'acidos_grasos_acidulado' => $infosale->ACIDOS_GRASOS_ACIDULADO,
                        'servicio_maquila' => $infosale->SERVICIO_MAQUILA,
                        'aceites2' => $infosale->ACEITES2,
                        'margarinas2' => $infosale->MARGARINAS2,
                        'solidos_cremosos2' => $infosale->SOLIDOS_CREMOSOS2,
                        'industriales2' => $infosale->INDUSTRIALES2,
                        'acidos_grasos_acidulado2' => $infosale->ACIDOS_GRASOS_ACIDULADO2,
                        'servicio_maquila2' => $infosale->SERVICIO_MAQUILA2,
                        'gastos_administracion' => $infosale->GASTOS_ADMINISTRACION,
                        'gastos_personal' => $infosale->GASTOS_PERSONAL,
                        'honorarios' => $infosale->HONORARIOS,
                        'servicios' => $infosale->SERVICIOS,
                        'gastos_ventas' => $infosale->GASTOS_VENTAS,
                        'gastos_personal2' => $infosale->GASTOS_PERSONAL2,
                        'poliza_cartera' => $infosale->POLIZA_CARTERA,
                        'fletes' => $infosale->FLETES,
                        'servicio_logistico' => $infosale->SERVICIO_LOGISTICO,
                        'estrategia_comercial' => $infosale->ESTRATEGIA_COMERCIAL,
                        'impuestos' => $infosale->IMPUESTOS,
                        'des_pronto_pago' => $infosale->DES_PRONTO_PAGO,
                        'depreciaciones_amortizaciones' => $infosale->DEPRECIACIONES_AMORTIZACIONES,
                        'financieros' => $infosale->FINANCIEROS,
                        'retiro_activos' => $infosale->RETIRO_ACTIVOS,
                        'grava_mov_financiero' => $infosale->GRAVA_MOV_FINANCIERO,
                        'otros' => $infosale->OTROS,
                        'ebitda' => $infosale->EBITDA
                    ]); */
                //}