<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estado;
use App\Solicitud;
use App\Token;
use App\TokenAcceso;

class API1Controller extends Controller
{
    public function datos_motociclista(Request $request) 
        {
                
                $token_web_form = Token::select('tokens.*')->where('id_token','=','1')->get();

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
                        "ews_licencia" => strip_tags(trim($request->input('ews_licencia'))),
                        "ews_edad" => strip_tags(trim($request->input('ews_edad'))),
                        "ews_lugar_nacimiento" => strip_tags(trim($request->input('ews_lugar_nacimiento'))),
                        "ews_telefono" => strip_tags(trim($request->input('ews_telefono'))),
                        "ews_nombre_avisar" => strip_tags(trim($request->input('ews_nombre_avisar'))),
                        "ews_apellido_paterno_avisar" => strip_tags(trim($request->input('ews_apellido_paterno_avisar'))),
                        "ews_apellido_materno_avisar" => strip_tags(trim($request->input('ews_apellido_materno_avisar'))),
                        "ews_direccion_avisar" => strip_tags(trim($request->input('ews_direccion_avisar'))),
                        "ews_telefono_avisar" => strip_tags(trim($request->input('ews_telefono_avisar'))),
                        "ews_agudeza_visual" => strip_tags(trim($request->input('ews_agudeza_visual'))),
                        "ews_lentes" => strip_tags(trim($request->input('ews_lentes'))),
                        "ews_tipo_sanguineo" => strip_tags(trim($request->input('ews_tipo_sanguineo'))),
                        "ews_estatura" => strip_tags(trim($request->input('ews_estatura'))),
                        "ews_padecimientos" => strip_tags(trim($request->input('ews_padecimientos'))),
                        "ews_donador" => strip_tags(trim($request->input('ews_donador'))),
                        "ews_vigencia" => strip_tags(trim($request->input('ews_vigencia')))
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
                                $saveSolicitud->ews_nombre = $data->ews_nombre;
                                $saveSolicitud->ews_apellido_paterno = $data->ews_apellido_paterno;
                                $saveSolicitud->ews_apellido_materno = $data->ews_apellido_materno;
                                $saveSolicitud->ews_curp = $data->ews_curp;
                                $saveSolicitud->ews_licencia = $data->ews_licencia;
                                $saveSolicitud->ews_edad = $data->ews_edad;
                                $saveSolicitud->ews_lugar_nacimiento = $data->ews_lugar_nacimiento;
                                $saveSolicitud->ews_telefono = $data->ews_telefono;
                                $saveSolicitud->ews_nombre_avisar = $data->ews_nombre_avisar;
                                $saveSolicitud->ews_apellido_paterno_avisar = $data->ews_apellido_paterno_avisar;
                                $saveSolicitud->ews_apellido_materno_avisar = $data->ews_apellido_materno_avisar;
                                $saveSolicitud->ews_direccion_avisar = $data->ews_direccion_avisar;
                                $saveSolicitud->ews_telefono_avisar = $data->ews_telefono_avisar;
                                $saveSolicitud->ews_agudeza_visual = $data->ews_agudeza_visual;
                                $saveSolicitud->ews_lentes = $data->ews_lentes;
                                $saveSolicitud->ews_tipo_sanguineo = $data->ews_tipo_sanguineo;
                                $saveSolicitud->ews_estatura = $data->ews_estatura;
                                $saveSolicitud->ews_padecimientos = $data->ews_padecimientos;
                                $saveSolicitud->ews_donador = $data->ews_donador;
                                $saveSolicitud->ews_vigencia_licencia = $data->ews_vigencia;
                                foreach($token_web_form as $id_token){
                                        $saveSolicitud->id_token = $id_token->id_token;
                                }
                                
                                $saveSolicitud->save();
                                
                                $nombre_accidente = $data->ews_nombre_avisar." ".$data->ews_apellido_paterno_avisar." ".$data->ews_apellido_materno_avisar;
                                $id_save_solicitud = $saveSolicitud->id_solicitud;
                                $no_solicitud_api = Solicitud::find($id_save_solicitud);
                                $no_solicitud_api->no_solicitud_api = date('Y').'-'.str_pad($id_save_solicitud, 4, "0", STR_PAD_LEFT);
                                $no_solicitud_api->save();

                                
                                        $cadena = (object)array(
                                                '0' => (object)array(
                                                        '0' => (object)array(
                                                                '0' => 'Datos del Ciudadano'
                                                        ),
                                                        '1' => (object)array(
                                                                '0' => 'Nombre',
                                                                '1' => $data->ews_nombre
                                                        ),
                                                        '2' =>(object)array(
                                                                '0' => 'Apellido Paterno',
                                                                '1' => $data->ews_apellido_paterno
                                                        ),
                                                        '3' =>(object)array(
                                                                '0' => 'Apellido Materno',
                                                                '1' => $data->ews_apellido_materno
                                                        ),
                                                        '4' =>(object)array(
                                                                '0' => 'Edad',
                                                                '1' => $data->ews_edad
                                                        ),
                                                        '5' =>(object)array(
                                                                '0' => 'CURP',
                                                                '1' => $data->ews_curp
                                                        ),
                                                        '6' =>(object)array(
                                                                '0' => 'Lugar de Nacimiento',
                                                                '1' => $data->ews_lugar_nacimiento
                                                        ),
                                                        '7' =>(object)array(
                                                                '0' => 'Telefono',
                                                                '1' => $data->ews_telefono
                                                        )
                                                ),
                                                '1' => (object)array(
                                                        '0' => (object)array(
                                                                '0' => 'En caso de accidente avisar a'
                                                        ),
                                                        '1' => (object)array(
                                                                '0' => 'Nombre Completo',
                                                                '1' => $nombre_accidente
                                                        ),
                                                        '2' =>(object)array(
                                                                '0' => 'Direccion',
                                                                '1' => $data->ews_direccion_avisar
                                                        ),
                                                        '3' =>(object)array(
                                                                '0' => 'Telefono',
                                                                '1' => $data->ews_telefono_avisar
                                                        )    
                                                ),
                                                '2' => (object)array(
                                                        '0' => (object)array(
                                                                '0' => 'Informacion medica'
                                                        ),
                                                        '1' => (object)array(
                                                                '0' => 'Agudeza Visual',
                                                                '1' => $data->ews_agudeza_visual
                                                        ),
                                                        '2' =>(object)array(
                                                                '0' => 'Usa lentes al conducir',
                                                                '1' => $data->ews_lentes
                                                        ),
                                                        '3' =>(object)array(
                                                                '0' => 'Grupo Sanguineo',
                                                                '1' => $data->ews_tipo_sanguineo
                                                        ),
                                                        '4' =>(object)array(
                                                                '0' => 'Padecimiento o alergias',
                                                                '1' => $data->ews_padecimientos
                                                        ),
                                                        '5' =>(object)array(
                                                                '0' => 'Estatura',
                                                                '1' => $data->ews_estatura
                                                        ),
                                                        '6' =>(object)array(
                                                                '0' => 'Donador de Organos',
                                                                '1' => $data->ews_donador
                                                        ),
                                                        '7' =>(object)array(
                                                                '0' => 'Vigencia',
                                                                '1' => $data->ews_vigencia
                                                        )
                                                )     
                                        );        
                        
                        if($data->ews_vigencia == '2 años'){
                                $vigencia_años = '2';
                        }elseif($data->ews_vigencia == '3 años'){
                                $vigencia_años = '3';
                        }elseif($data->ews_vigencia == '4 años'){
                                $vigencia_años = '4';
                        }elseif($data->ews_vigencia == '5 años'){
                                $vigencia_años = '5';
                        }
                        
                        $saveAcceso = new TokenAcceso;
                        foreach($token_web_form as $id_token){
                                $saveAcceso->id_token = $id_token->id_token;
                        }
                        $saveAcceso->fecha = date('Y-m-d');
                        $saveAcceso->hora = date('H:i:s');
                        $saveAcceso->ip = $request->ip();
                        $saveAcceso->dato_clave = $data->ews_licencia;
                        $saveAcceso->mensaje = 'token utilizado con exito';
                        $saveAcceso->codigo = '200';
                        $saveAcceso->save();
                        return response()->json(['wsp_mensaje'=>'Ciudadano Encontrado',
                                                'wsp_no_Solicitud'=>$data->ews_no_solicitud,
                                                'wsp_no_Solicitud_api'=>$no_solicitud_api->no_solicitud_api,
                                                'wsp_nivel'=>'2',
                                                'wsp_datos'=>$cadena,
                                                'wsp_indice' =>$vigencia_años], 200);    
                              
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
