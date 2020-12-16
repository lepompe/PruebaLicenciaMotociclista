<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $connection = 'mysql';
    protected $table = "solicitudes";
    protected $primaryKey = 'id_solicitud';
    public $timestamps = false;
}
