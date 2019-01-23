<?php
/**
 * Created by PhpStorm.
 * User: xdrm
 * Date: 4/3/17
 * Time: 6:34 PM
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Agentrent extends Model
{
    protected $fillable = ['title', 'adult','child', 'tandem', 'road', 'mountain', 'trailer', 'seat', 'dropoff', 'insurance'];

}