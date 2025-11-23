<?php

namespace App\Models;

use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Lead extends Model
{
    /**
     * @use HasFactory<LeadFactory>
     */
    use HasFactory;

    /**
     * The database connection used by the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The collection associated with the model.
     *
     * @var string
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
