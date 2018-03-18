<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $primaryKey = 'Location_id';
    protected $table = 'Location';
    public $timestamps = false;

    protected $fillable = ['street_address','street_number','city','zip','country','latitude','longitude'];

    public function location()
    {
        return $this->hasMany('App\Person', 'Location_Location_id', 'Location_id');
    }
}
