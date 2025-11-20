<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Lead extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'leads';

    protected $fillable = [
        'full_name',
        'email',
        'consent',
    ];
}
