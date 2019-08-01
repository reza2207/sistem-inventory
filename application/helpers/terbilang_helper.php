<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

    
    function kekata($x){
        $x=abs($x);
        $angka=array("","satu","dua","tiga","empat","lima",
        "enam","tujuh","delapan","sembilan","sepuluh","sebelas");
        $temp="";
        if($x<12){
            $temp=" ".$angka[$x];
        }elseif($x<20){
            $temp=kekata($x-10)." belas";
        }elseif($x<100){
            $temp=kekata($x/10)." puluh".kekata($x%10);
        }elseif($x<200){
            $temp=" seratus".kekata($x-100);
        }elseif($x<1000){
            $temp=kekata($x/100)." ratus".kekata($x%100);
        }elseif($x<2000){
            $temp=" seribu".kekata($x-1000);
        }elseif($x<1000000){
            $temp=kekata($x/1000)." ribu".kekata($x%1000);
        }elseif($x<1000000000){
            $temp=kekata($x/1000000)." juta".kekata($x%1000000);
        }elseif($x<1000000000000){
            $temp=kekata($x/1000000000)." milyar".kekata(fmod($x,1000000000));
        }elseif($x<1000000000000000){
            $temp=kekata($x/1000000000000)." trilyun".kekata(fmod($x,1000000000000));
        }    
            return $temp;
    }
     
     
    function terbilang($x,$style=1){
        if($x<0){
            $hasil="minus ".trim(kekata($x));
        }else{
            $hasil=trim(kekata($x));
        }    
        switch($style){
            case 1:
                $hasil=strtoupper($hasil);
                break;
            case 2:
                $hasil=strtolower($hasil);
                break;
            case 3:
                $hasil=ucwords($hasil);
                break;
            default:
                $hasil=ucfirst($hasil);
                break;
        }    
        return $hasil;
    }


    function titik($x){
        if($x==0){
            $x = '-';
        }else{
            $x = number_format($x, '0',',','.');
        }
        return $x;
    }


    function current_active($page , $pages){


        if($page == NULL){
            return '';
        }elseif($page == $pages){
            return 'active';
        }else{
            return '';
        }
    }

    function read_only($x){
        if($x != '0000-00-00' or $x != ''){
            $read = 'readonly';
        }else{
            $read = '';
        }

        return $read;
    }