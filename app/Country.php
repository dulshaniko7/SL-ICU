<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    public $table = 'countries';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'short_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function countryCustomers()
    {
        return $this->hasMany(Customer::class, 'country_id', 'id');
    }
}
