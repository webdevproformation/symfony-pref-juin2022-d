<?php 

namespace App\Services ;

use DateTime;

class DateService{

    public function now() : DateTime{
        $now = new DateTime("+ 1 hours");
        $h =  $now->format("H") ;
        $now->setTime( $h , 0 , 0);
        return $now;
    }

    public function demain() :DateTime{
        $demain = new DateTime("+ 1 days");
        $h =  $demain->format("H") + 1;
        $demain->setTime( $h , 0 , 0);
        return $demain ;
    }

}