<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Total_sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'inf_nid',
        'inf_dfecha_registro',
        'inf_dhora_registro',
        'inf_d_anio',
        'inf_d_mes',
        'inf_d_fechas',
        'aceites',
        'margarinas',
        'solidos_cremosos',
        'industriales',
        'industriales',
        'acidos_grasos_acidulado',
        'servicio_maquila',
        'aceites2',
        'margarinas2',
        'solidos_cremosos2',
        'industriales2',
        'acidos_grasos_acidulado2',
        'servicio_maquila2',
        'gastos_administracion',
        'gastos_personal',
        'honorarios',
        'servicios',
        'gastos_ventas',
        'gastos_personal2',
        'poliza_cartera',
        'fletes',
        'servicio_logistico',
        'estrategia_comercial',
        'impuestos',
        'des_pronto_pago',
        'depreciaciones_amortizaciones',
        'financieros',
        'retiro_activos',
        'grava_mov_financiero',
        'otros',
        'ebitda'
    ];
}
