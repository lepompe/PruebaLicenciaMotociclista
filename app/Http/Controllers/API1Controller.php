<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class API1Controller extends Controller
{
    public function datos_licencia(Request $request) 
        {
                
                $token_web_form = token::select('tokens.*')->where('id_token','=','1')->get();

                foreach($token_web_form as $value){
                        $token_web = $value->token;
                }
                $data = [
                        "ews_token" => strip_tags(trim($request->input('ews_token'))),
                        "ews_no_solicitud" => strip_tags(trim($request->input('ews_no_solicitud'))),
                        "ews_llave" => strip_tags(trim($request->input('ews_llave'))),
                        "ews_id_tramite" => strip_tags(trim($request->input('ews_id_tramite'))),
                        "ews_fecha_solicitud" => strip_tags(trim($request->input('ews_fecha_solicitud'))),
                        "ews_hora_solicitud" => strip_tags(trim($request->input('ews_hora_solicitud'))),
                        "ews_nombre" => strip_tags(trim($request->input('ews_nombre'))),
                        "ews_apellido_paterno" => strip_tags(trim($request->input('ews_apellido_paterno'))),
                        "ews_apellido_materno" => strip_tags(trim($request->input('ews_apellido_materno'))),
                        "ews_curp" => strip_tags(trim($request->input('ews_curp'))),
                        "ews_licencia" => strip_tags(trim($request->input('ews_licencia')))
                ];
                $data = (object) $data;
                
                if($token_web == $data->ews_token) {

                                if(empty($data->ews_llave) || 
                                empty($data->ews_id_tramite) || 
                                empty($data->ews_no_solicitud) || 
                                empty($data->ews_fecha_solicitud) || 
                                empty($data->ews_nombre) ||
                                empty($data->ews_apellido_paterno) ||
                                empty($data->ews_apellido_materno) ||
                                empty($data->ews_curp) ||
                                empty($data->ews_licencia) ||
                                empty($data->ews_hora_solicitud)
                                ){

                                        $saveAcceso = new TokenAcceso;
                                        foreach($token_web_form as $id_token){
                                                $saveAcceso->id_token = $id_token->id_token;
                                        }
                                        $saveAcceso->fecha = date('Y-m-d');
                                        $saveAcceso->hora = date('H:i:s');
                                        $saveAcceso->ip = $request->ip();
                                        $saveAcceso->dato_clave = $data->ews_licencia;
                                        $saveAcceso->mensaje = 'token utilizado con exito pero con información faltante';
                                        $saveAcceso->codigo = '400';
                                        $saveAcceso->save();
                                        return response()->json(array("wsp_mensaje" => 'falta información' ), 400);
                                        
                                }
                                $completo = datoGral::join('dbo.Lic_Licencias','Lic_Licencias.Dat_Id','=','Dat_DatosGral.Dat_id')
                                                        ->join('dbo.TipLic_TipoLicencia', 'TipLic_TipoLicencia.TipLic_id', '=', 'Lic_Licencias.TipLic_Id')
                                                        ->select('Dat_DatosGral.*','Lic_Licencias.*','TipLic_TipoLicencia.TipLic_Descripcion')
                                                        ->where('Dat_Nombre','=',$data->ews_nombre)
                                                        ->where('Dat_Paterno','=',$data->ews_apellido_paterno)
                                                        ->where('Dat_Materno','=',$data->ews_apellido_materno)
                                                        ->where('Dat_CURP','=',$data->ews_curp)
                                                        ->where('Lic_Expediente','=',$data->ews_licencia)
                                                        ->orderby('Lic_Expedicion','asc')
                                                        ->get();
                                $curp = datoGral::join('dbo.Lic_Licencias','Lic_Licencias.Dat_Id','=','Dat_DatosGral.Dat_id')
                                                        ->join('dbo.TipLic_TipoLicencia', 'TipLic_TipoLicencia.TipLic_id', '=', 'Lic_Licencias.TipLic_Id')
                                                        ->select('Dat_DatosGral.*','Lic_Licencias.*','TipLic_TipoLicencia.TipLic_Descripcion')
                                                        ->where('Dat_Nombre','=',$data->ews_nombre)
                                                        ->where('Dat_Paterno','=',$data->ews_apellido_paterno)
                                                        ->where('Dat_Materno','=',$data->ews_apellido_materno)
                                                        ->where('Dat_CURP','=',$data->ews_curp)
                                                        ->orderby('Lic_Expedicion','asc')
                                                        ->get();
                                $expediente = datoGral::join('dbo.Lic_Licencias','Lic_Licencias.Dat_Id','=','Dat_DatosGral.Dat_id')
                                                        ->join('dbo.TipLic_TipoLicencia', 'TipLic_TipoLicencia.TipLic_id', '=', 'Lic_Licencias.TipLic_Id')
                                                        ->select('Dat_DatosGral.*','Lic_Licencias.*','TipLic_TipoLicencia.TipLic_Descripcion')
                                                        ->where('Dat_Nombre','=',$data->ews_nombre)
                                                        ->where('Dat_Paterno','=',$data->ews_apellido_paterno)
                                                        ->where('Dat_Materno','=',$data->ews_apellido_materno)
                                                        ->where('Lic_Expediente','=',$data->ews_licencia)
                                                        ->orderby('Lic_Expedicion','asc')
                                                        ->get();
                                if($curp == '[]'){
                                        $persona = $expediente;
                                        if($persona == '[]'){
                                                return response()->json(['wsp_mensaje'=>'ciudadano no encontrado'], 404);
                                        }
                                }elseif($expediente == '[]'){
                                        $persona = $curp;
                                        if($persona == '[]'){
                                                return response()->json(['wsp_mensaje'=>'ciudadano no encontrado'], 404);
                                        }
                                }else{
                                        $persona = $completo;
                                        if($persona == '[]'){
                                                return response()->json(['wsp_mensaje'=>'ciudadano no encontrado'], 404);
                                        }
                                }
                                $saveEstado = new Estado;
                                $saveEstado->nombre = 'INICIADO';
                                $saveEstado->save();
                              
                                $saveSolicitud = new Solicitud;
                                $saveSolicitud->llave = $data->ews_llave;
                                $saveSolicitud->id_tramite = $data->ews_id_tramite;
                                $saveSolicitud->no_solicitud = $data->ews_no_solicitud;
                                $saveSolicitud->fecha_solicitud = date('Y-m-d');
                                $saveSolicitud->hora_solicitud = date('H:i:s');
                                $saveSolicitud->no_solicitud_api = '';
                                $saveSolicitud->fecha_solicitud_api = date('Y-m-d');
                                $saveSolicitud->hora_solicitud_api = date('H:i:s');
                                $saveSolicitud->id_estado = $saveEstado->id_estado;
                                $saveSolicitud->id_electronico = '';
                                $saveSolicitud->referencia_pago = '';
                                $saveSolicitud->fecha_pago = date('Y-m-d');
                                $saveSolicitud->hora_pago = date('H:i:s');
                                $saveSolicitud->stripe_orden_id = '';
                                $saveSolicitud->stripe_creado = '';
                                $saveSolicitud->stripe_mensaje = '';
                                $saveSolicitud->stripe_tipo = '';
                                $saveSolicitud->stripe_digitos = '';
                                $saveSolicitud->stripe_red = '';
                                $saveSolicitud->stripe_estado = '';
                                $saveSolicitud->xml_url = '';
                                $saveSolicitud->no_consulta = '0';
                                foreach ($persona as $dato){
                                        $saveSolicitud->ews_nombre = $dato->Dat_Nombre;
                                        $saveSolicitud->ews_apellido_paterno = $dato->Dat_Paterno;
                                        $saveSolicitud->ews_apellido_materno = $dato->Dat_Materno;
                                        $saveSolicitud->ews_curp = $dato->Dat_CURP;
                                        $saveSolicitud->ews_licencia = $dato->Lic_Expediente;
                                }
                                foreach($token_web_form as $id_token){
                                        $saveSolicitud->id_token = $id_token->id_token;
                                }
                                $saveSolicitud->save();
                                
                                $id_save_solicitud = $saveSolicitud->id_solicitud;
                                $no_solicitud_api = Solicitud::find($id_save_solicitud);
                                $no_solicitud_api->no_solicitud_api = date('Y').'-'.str_pad($id_save_solicitud, 4, "0", STR_PAD_LEFT);
                                $no_solicitud_api->save();

                                foreach($persona as $vigencia){
                                        $vigenciadato = $vigencia->Lic_Vigencia; 
                                }
                                 if($vigenciadato == '0'){
                                        $vigencia_años = '0 meses';
                                }elseif($vigenciadato == '1'){
                                        $vigencia_años = '1 mes';
                                }elseif($vigenciadato == '2'){
                                        $vigencia_años = '2 meses';
                                }elseif($vigenciadato == '2'){
                                        $vigencia_años = '2 meses';
                                }elseif($vigenciadato == '3'){
                                        $vigencia_años = '3 meses';
                                }elseif($vigenciadato == '4'){
                                        $vigencia_años = '4 meses';
                                }elseif($vigenciadato == '5'){
                                        $vigencia_años = '5 meses';
                                }elseif($vigenciadato == '6'){
                                        $vigencia_años = '6 meses';
                                }elseif($vigenciadato == '23'
                                ||$vigenciadato == '24'){
                                        $vigencia_años = '2 años';
                                }elseif($vigenciadato == '35'
                                ||$vigenciadato == '36'){
                                        $vigencia_años = '3 años';
                                }elseif($vigenciadato == '47'
                                ||$vigenciadato == '48'){
                                        $vigencia_años = '4 años';
                                }elseif($vigenciadato == '59'
                                ||$vigenciadato == '60'){
                                        $vigencia_años = '5 años';
                                }elseif($vigenciadato == '71'
                                ||$vigenciadato == '72'){
                                        $vigencia_años = '6 años';
                                }elseif($vigenciadato == '83'
                                ||$vigenciadato == '84'){
                                        $vigencia_años = '7 años';
                                }elseif($vigenciadato == '95'
                                ||$vigenciadato == '96'){
                                        $vigencia_años = '8 años';
                                }elseif($vigenciadato == '107'
                                ||$vigenciadato == '108'){
                                        $vigencia_años = '9 años';
                                }elseif($vigenciadato == '117'
                                ||$vigenciadato == '118'
                                ||$vigenciadato == '119'
                                ||$vigenciadato == '120'){
                                        $vigencia_años = '10 años';
                                }
                                foreach ($persona as $value) 
                                {
                                        
                                        $cadena = (object)array(
                                                '0' => (object)array(
                                                        '0' => (object)array(
                                                                '0' => 'Datos del Historial de Licencias'
                                                        ),
                                                        '1' => (object)array(
                                                                '0' => 'Numero de Folio',
                                                                '1' => $value->Dat_Folio
                                                        ),
                                                        '2' =>(object)array(
                                                                '0' => 'Tipo de Licencia',
                                                                '1' => $value->TipLic_Descripcion
                                                        ),
                                                        '3' =>(object)array(
                                                                '0' => 'Numero de Expediente',
                                                                '1' => $value->Lic_Expediente
                                                        ),
                                                        '4' =>(object)array(
                                                                '0' => 'Vigencia',
                                                                '1' => $vigencia_años
                                                        ),
                                                        '5' =>(object)array(
                                                                '0' => 'Fecha de Expedicion',
                                                                '1' => $value->Lic_Expedicion
                                                        ),
                                                        '6' =>(object)array(
                                                                '0' => 'Fecha de Vencimiento',
                                                                '1' => $value->Lic_Vencimiento
                                                        ),
                                                )       
                                        );        
                                }
                        
                        $saveAcceso = new TokenAcceso;
                        foreach($token_web_form as $id_token){
                                $saveAcceso->id_token = $id_token->id_token;
                        }
                        $saveAcceso->fecha = date('Y-m-d');
                        $saveAcceso->hora = date('H:i:s');
                        $saveAcceso->ip = $request->ip();
                        foreach($persona as $value){
                                $saveAcceso->dato_clave = $value->Dat_Folio;
                        }
                        $saveAcceso->mensaje = 'token utilizado con exito';
                        $saveAcceso->codigo = '200';
                        $saveAcceso->save();
                        return response()->json(['wsp_mensaje'=>'Ciudadano Encontrado',
                                                'wsp_no_Solicitud'=>$data->ews_no_solicitud,
                                                'wsp_no_Solicitud_api'=>$no_solicitud_api->no_solicitud_api,
                                                'wsp_nivel'=>'1',
                                                'wsp_datos'=>$cadena], 200);    
                              
                } elseif($token_web != $data->ews_token){

                        $saveAcceso = new TokenAcceso;
                        foreach($token_web_form as $id_token){
                                $saveAcceso->id_token = $id_token->id_token;
                        }
                        $saveAcceso->fecha = date('Y-m-d');
                        $saveAcceso->hora = date('H:i:s');
                        $saveAcceso->ip = $request->ip();
                        $saveAcceso->dato_clave = $data->ews_licencia;
                        $saveAcceso->mensaje = 'token no utilizado';
                        $saveAcceso->codigo = '403';
                        $saveAcceso->save();
                        return response()->json(array("wsp_mensaje" => 'Token Invalido' ), 403);
                }                
        }
}
