<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    /**
     * The database connection used by the model.
     */
    protected $connection = 'mongodb';

    /**
     * The collection associated with the model.
     */
    protected $collection = 'leads';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'full_name',
        'email',
        'consent',
    ];
}
