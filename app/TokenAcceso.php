<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokenAcceso extends Model
{
    protected $connection = 'mysql';
    protected $table = "token_accesos";
    protected $primaryKey = 'id';
    public $timestamps = false;
}
