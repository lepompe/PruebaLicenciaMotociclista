<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $connection = 'mysql';
    protected $table = "estados";
    protected $primaryKey = 'id_estado';
    public $timestamps = false;
}
