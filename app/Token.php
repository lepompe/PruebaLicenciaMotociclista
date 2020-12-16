<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $connection = 'mysql';
    protected $table = "tokens";
    protected $primaryKey = "id_token";
}
