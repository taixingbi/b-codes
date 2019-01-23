<?php
/**
 * Created by PhpStorm.
 * User: xdrm
 * Date: 4/7/17
 * Time: 4:36 PM
 */
namespace App\Http\Controllers\BigBike\Agent;

use Illuminate\Http\Request;

interface AgentInterface{
    public function getOrder();
    public function calculate(Request $request);
    public function submitForm(Request $request);
    public function postCCCheckout(Request $request);
//    public function printReceipt();
//    public function sendCustomerEmail( $email);
//    public function sendAgentEmail($email);

}
