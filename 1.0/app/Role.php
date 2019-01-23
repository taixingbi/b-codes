<?php namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    public $timestamps = true;
    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}