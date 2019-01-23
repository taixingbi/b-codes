<?php
/**
 * Created by PhpStorm.
 * User: xdrm
 * Date: 4/4/17
 * Time: 2:21 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent_tour extends Model{

    protected $fillable = ['title', 'adult','child'];

}