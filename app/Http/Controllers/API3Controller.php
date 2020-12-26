<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Solicitud;
use App\Token;
use App\datoGral;
use App\Estado;


class API3Controller extends Controller
{
    public function verpdf(Request $request){
        $token_web_form = token::select('tokens.*')->where('id_token','=','1')->get();

        foreach($token_web_form as $value){
                $token_web = $value->token;
        }
        $data = [
                "ews_token" => strip_tags(trim($request->input('ews_token'))),
                "ews_no_solicitud" => strip_tags(trim($request->input('ews_no_solicitud'))),
                "ews_llave" => strip_tags(trim($request->input('ews_llave'))),
                "ews_id_electronico" => strip_tags(trim($request->input('ews_id_electronico'))),
        ];
        $data = (object) $data;
        if($token_web == $data->ews_token){
                if(empty($data->ews_no_solicitud) ||
                empty($data->ews_llave) || 
                empty($data->ews_id_electronico) 
                ){
                        return response()->json(array("wsp_mensaje" => 'falta información' ), 400);
                }

                $solicitud = Solicitud::select('solicitudes.*')->where('no_solicitud','=',$data->ews_no_solicitud)->get();
                
                foreach($solicitud as $value){
                        $id_solicitud = $value->id_solicitud;
                        $id_estado = $value->id_estado;
                        $id_electronico = $value->id_electronico;
                        $nombre_persona = $value->ews_nombre;
                        $materno_persona = $value->ews_apellido_materno;
                        $paterno_persona = $value->ews_apellido_paterno;
                        $curp_persona = $value->ews_curp;
                        $datos_licencia = $value->ews_licencia;
                        $no_consulta = $value->no_consulta;
                        $vigencia = $value->ews_vigencia_licencia;
                        $nombre_avisar = $value->ews_nombre_avisar;
                        $paterno_avisar = $value->ews_apellido_paterno_avisar;
                        $materno_avisar = $value->ews_apellido_materno_avisar;
                }

                $persona_solicitud = $solicitud->first();
                $nombre_accidente = $nombre_avisar." ".$paterno_avisar." ".$materno_avisar;

                if($vigencia=='2 años'){
                    $vigencia_años = '2';
                }elseif($vigencia=='3 años'){
                    $vigencia_años = '3';
                }elseif($vigencia=='4 años'){
                    $vigencia_años = '4';
                }elseif($vigencia=='5 años'){
                    $vigencia_años = '5';
                }
                
                $con_curp = datoGral::join('dbo.Lic_Licencias','Lic_Licencias.Dat_Id','=','Dat_DatosGral.Dat_id')
                ->join('dbo.TipLic_TipoLicencia', 'TipLic_TipoLicencia.TipLic_id', '=', 'Lic_Licencias.TipLic_Id')
                ->select('Dat_DatosGral.*','Lic_Licencias.*','TipLic_TipoLicencia.TipLic_Descripcion')
                ->where('Dat_Nombre','=',$nombre_persona)
                ->where('Dat_Paterno','=',$paterno_persona)
                ->where('Dat_Materno','=',$materno_persona)
                ->where('Dat_CURP','=',$curp_persona)
                ->where('Lic_Licencias.TipLic_Id','=','3')
                ->get();
                $sin_curp = datoGral::join('dbo.Lic_Licencias','Lic_Licencias.Dat_Id','=','Dat_DatosGral.Dat_id')
                ->join('dbo.TipLic_TipoLicencia', 'TipLic_TipoLicencia.TipLic_id', '=', 'Lic_Licencias.TipLic_Id')
                ->select('Dat_DatosGral.*','Lic_Licencias.*','TipLic_TipoLicencia.TipLic_Descripcion')
                ->where('Dat_Nombre','=',$nombre_persona)
                ->where('Dat_Paterno','=',$materno_persona)
                ->where('Dat_Materno','=',$paterno_persona)
                ->where('Lic_Licencias.TipLic_Id','=','3')
                ->get();
                
                if($con_curp == '[]'){
                    foreach($sin_curp as $genero){
                        $genero_persona = $genero->Sex_id;
                    }
                    $persona = $sin_curp->first();
                }else{
                    foreach($con_curp as $genero){
                        $genero_persona = $genero->Sex_id;
                    }
                    $persona = $con_curp->first();
                }

                if($genero_persona=='1'){
                    $sexo_persona = 'MASCULINO';
                }elseif($genero_persona=='2'){
                    $sexo_persona = 'FEMENINO';
                }elseif($genero_persona=='0'){
                    $sexo_persona = 'SIN INFORMACIÓN';
                }



                $saveConsulta = Solicitud::find($id_solicitud);
                $saveConsulta->no_consulta = ++$no_consulta;
                $saveConsulta->save();

                $fecha = mktime(0, 0, 0, date("m"), date("d"), date("Y") + $vigencia_años);
                $nuevafecha = date('d/m/Y', $fecha);

                $nombre_archivo = md5(date('Y-m-d H:i:s').rand()).".png";
                $qr = \QrCode::format('png')->size('200')->generate('https://potys.gob.mx/validatramite/?id='.$id_electronico);
                \Storage::disk('qrcodes')->put($nombre_archivo,$qr);

                $nombrepdf = $datos_licencia.md5(date('Y-m-d H:i:s').rand()).".pdf";
                $pdf = \PDF::loadView('layouts/pdf_invalido', compact('sexo_persona','nombre_accidente','persona','persona_solicitud','nuevafecha','nombre_archivo','nombrepdf'));
                $pdf->setPaper(array(0, -50, 330, 900), 'portrait');

                $updateEstado = Estado::find($id_estado);
                $updateEstado->nombre = '6';
                $updateEstado->save();
                return $pdf->stream($nombrepdf);
        }else{
                return response()->json(array("wsp_mensaje" => 'Token Inválido' ), 403);
        }

    }
}
