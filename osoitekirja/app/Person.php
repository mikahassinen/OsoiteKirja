<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $primaryKey = 'Person_id';
    protected $table = 'Person';
    public $timestamps = false;

    protected $fillable = ['first_name','last_name'];


    public function location()
    {
        return $this->hasOne('App\Location', 'Location_id', 'Location_Location_id');
    }
}
