<!DOCTYPE html>
<html lang="es">
<head>
    <!-- estilos -->
    <link href="storage/css/pdf.css" rel="stylesheet">
    
    <!-- fuentes -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $nombrepdf }}</title>
</head>
<body>
    <div class="plantilla">
        <img src="storage/img/invalida.png" alt="">
    </div>
    <div class="nombre">
        <p>{{$persona->Dat_Nombre}}</p>
        <p>{{$persona->Dat_Materno}}</p>
        <p>{{$persona->Dat_Paterno}}</p>
    </div>
    <div class="tipo_licencia">
        <p class="descripcion">{{$persona->TipLic_Descripcion}}</p>
        <p>{{$persona->Lic_Expediente}}</p>
    </div>
    <div class="curp">
        <p>{{$persona->Dat_CURP}}</p>
    </div>
    <div class="nacionalidad">
        <p>{{$persona->Nac_id}}</p>
    </div>
    <div class="fecha_nacimiento">
        <p>{{strftime("%d/"."%m"."/%Y",strtotime($persona->Dat_fecnac))}}</p>
    </div>
    <div class="expedicion">
        <p>{{strftime("%d/"."%m"."/%Y",strtotime($persona_solicitud->fecha_solicitud))}}</p>
    </div>
    <div class="vencimiento">
        <p>{{$nuevafecha}}</p>
    </div>
    <div class="direccion">
        <p>{{$persona_solicitud->ews_lugar_nacimiento}}</p>
    </div>
    <div class="genero">
        <p>{{$sexo_persona}}</p>
    </div>
    <div class="telefono">
        <p>{{$persona_solicitud->ews_telefono}}</p>
    </div>
    <div class="lentes">
        <p>{{$persona_solicitud->ews_lentes}}</p>
    </div>
    <div class="alergias_enfermedades">
        <p>{{$persona_solicitud->ews_padecimientos}}</p>
    </div>
    <div class="donador">
        <p>{{$persona_solicitud->ews_donador}}</p>
    </div>
    <div class="tipo_sangre">
        <p>{{$persona_solicitud->ews_tipo_sanguineo}}</p>
    </div>
    <div class="avisar">
        <p>{{$nombre_accidente}}</p>
        <p>{{$persona_solicitud->ews_telefono_avisar}}</p>
    </div>
    <div class="qr">
        <img src="storage/qrcodes/{{$nombre_archivo}}" alt="">
    </div>
</body>
</html>