<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class Cart
{
    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart)
    {
        if($oldCart){
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    public function add(Request $request, $id){
        $storedItem = ['qty' => 0, 'size'=>'', 'price' => $request->price, 'item' => $request->name,'id'=>0];
        if($this->items){
            if(array_key_exists($id, $this->items)){
                $storedItem = $this->items[$id];
            }
        }

        $storedItem['qty']+=intval($request->quantity);
        $storedItem['price'] = $request->price*$storedItem['qty'];
        $storedItem['id'] = $id;
        $storedItem['size'] = $request->inventory_size;

        $this->items[$id] = $storedItem;
        $this->totalQty+=intval($request->quantity);
        $this->totalPrice += $request->price*intval($request->quantity);
        $tax = 0.08875;

        session(['inventory_total'=>$this->totalPrice*(1.0+$tax),
            'inventory_total_before_tax'=>$this->totalPrice]);
    }

    public function minus($request, $num, $id){
        $storedItem = ['qty' => 0, 'price' => $request->price, 'item' => $request->name,'id'=>0];
        if($this->items){
            if(array_key_exists($id, $this->items)){
                $storedItem = $this->items[$id];
            }
        }

        $storedItem['qty']-=intval($num);
        $storedItem['price'] = $request->price;
        if($storedItem['qty']==0) {
            $storedItem = null;
            $this->items[$id] = null;
            unset($this->items[$id]);
        }else {
            $this->items[$id] = $storedItem;
        }
        $this->totalQty-=intval(intval($num));
        $this->totalPrice -= $request->price*intval($num);
        $tax = 0.08875;

        session(['inventory_total'=>$this->totalPrice*(1.0+$tax),
            'inventory_total_before_tax'=>$this->totalPrice]);
    }

}