<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expence extends Model
{
    protected $fillable = [
        'amount', 'discription', 'date', 'path',
    ];
}
