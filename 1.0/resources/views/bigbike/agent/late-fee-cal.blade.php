<?php
/**
 * Created by PhpStorm.
 * User: dixu
 * Date: 5/10/17
 * Time: 11:42 AM
 */

                $dteStart = new DateTime(date('Y-m-d H:i:s',time()));
                //                                dd($dteStart);
                $dteEnd = new DateTime($agent_rents_order['end_time']);
                //                            $dteEnd = new DateTime(date('Y-m-d H:i:s'));
                //                            $dteStart = new DateTime($item->end_time);

                $count_down =$dteEnd->diff($dteStart)->format("%H:%I");
                $days = $dteEnd->diff($dteStart)->format('%a');

                if($dteEnd<$dteStart){
                    $dteStart2 = new DateTime(date("Y-m-d H:m:s", strtotime('15 minutes',time())));
                    //                                    dd($dteStart2);
                    $count_down =$dteEnd->diff($dteStart2)->format("%H:%I");
//                                    $count_down =$dteEnd->diff($dteStart2)->format("%H:%I:%S");

                    $count_down = $days." d ".$count_down;
                    $hours = 0;

//                                    explode(' ', $count_down)[2];
                    //                                dd(explode(':',explode(' ', $count_down)[2])[0]);
                    $hours += (intval(explode(':',explode(' ', $count_down)[2])[0])+1);
                    if($days>'0'){
                        $hours += 24*intval($days);
                    }
                    //                                dd($hours);
                    $late_fee = intval($agent_rents_order['total_bikes'])*$hours*10;
                }else{
                    $late_fee = 0;
                }
?>