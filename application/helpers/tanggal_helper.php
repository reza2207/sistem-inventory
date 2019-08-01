<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


	
	function tanggal($x){

		if($x == "-" || $x == '0000-00-00'){
		$y = "";
		return $y;
		
		}elseif(date("d-m-Y", strtotime($x)) == "01-01-1970") {

		return "-";
		
		}else{

		return date("d-m-Y", strtotime($x));
		
		}

	}

	function tanggal1($x){

		if($x == ""){
			return "";
		}else{

		return date("Y-m-d", strtotime($x));
		}
	}

	function tanggal_indo($tanggal){

		if($tanggal == '0000-00-00'){
			return '-';
		}else{
			$bulan = array (1 =>   'Januari',
					'Februari',
					'Maret',
					'April',
					'Mei',
					'Juni',
					'Juli',
					'Agustus',
					'September',
					'Oktober',
					'November',
					'Desember'
				);
			$split = explode('-', $tanggal);
			return (int) $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
		}
	}

	function jumlahhari($bulan, $tahun){
		
		$kalender = CAL_GREGORIAN;

		$hari = cal_days_in_month($kalender, $bulan,  $tahun);

		return $hari;

	}

	function jmldaribulan($x){

		$kalender = CAL_GREGORIAN;
		$pecah = explode("-", $x);
		$hari = cal_days_in_month($kalender, $pecah[0], $pecah[1]);

		return $hari;
	}


	function jmlhrblnini(){

		$bulan = date("m");

		$tahun = date("Y");

		$kalender = CAL_GREGORIAN;

		$hari = cal_days_in_month($kalender, $bulan, $tahun);

		return $hari;
	}

	function bulanindo($bulan){
		switch ($bulan) {
			case '1':
				$hasil =  "Januari";
				break;
			case '2':
				$hasil = "Februari";
				break;
			case '3':
				$hasil = "Maret";
				break;
			case '4':
				$hasil = "April";
				break;
			case '5':
				$hasil = "Mei";
				break;
			case '6':
				$hasil = "Juni";
				break;
			case '7':
				$hasil = "Juli";
				break;
			case '8':
				$hasil = "Agustus";
				break;
			case '9':
				$hasil = "September";
				break;
			case '10':
				$hasil = "Oktober";
				break;
			case '11':
				$hasil = "November";
				break;
			case '12':
				$hasil = "Desember";
				break;
			default:
				$hasil = "------";
				break;
		}
		return $hasil;
	}

	function tglkosong($tgl){
		$tgl = str_pad($tgl,2,0,STR_PAD_LEFT);

		return $tgl;
	}

	function caritanggal($x){
		$pecah = explode("-", $x);

		return $pecah[1]."-".$pecah[0];
	}

	function tampiltanggal($x){
		$pecah = explode("-", $x);

		return bulanindo((int)$pecah[1])." ".$pecah[0];
	}


	function jmlharikerja( $tgl_awal, $tgl_akhir, $tgl_libur){
	    $awal_tgl = strtotime( $tgl_awal );
	    $akhir_tgl = strtotime( $tgl_akhir );
	    
	    if($awal_tgl < $akhir_tgl){

	        foreach ( $tgl_libur as & $harilibur ) {
	            $harilibur = strtotime ( $harilibur );
	        }

	        $waktu_temp = $awal_tgl ;
	        while( $waktu_temp <= $akhir_tgl ) {
	            $hari_temp = date( 'D' , $waktu_temp );
	            if (!( $hari_temp == 'Sun' ) && !( $hari_temp == 'Sat' ) && ! in_array ( $waktu_temp, $tgl_libur )) {

	            $hari_temp = date( 'd', $waktu_temp );
	            $hari[] = $hari_temp;
	            //$hari_temp[] = $hari_temp;
	            
	            }
	            $waktu_temp = strtotime ( '+1 day' , $waktu_temp );
	            //$h[] = $hari_temp;
	        }
	        return count($hari)-1;

	    }else{

	        return "error";

	    }
	}
	function harikerja( $tgl_awal, $tgl_akhir, $tgl_libur){
	   $awal_tgl = strtotime($tgl_awal);
	    $akhir_tgl = strtotime($tgl_akhir);
	    
	    if($awal_tgl > $akhir_tgl){

	        return "error";
	    }else{
	        foreach ( $tgl_libur as & $harilibur ) {
	                $harilibur = strtotime ( $harilibur );
	            }

	        $waktu_temp = $awal_tgl ;
	        while( $waktu_temp <= $akhir_tgl ) {
	            $hari_temp = date( 'D' , $waktu_temp );
	            if (!( $hari_temp == 'Sun' ) && !( $hari_temp == 'Sat' ) && ! in_array ( $waktu_temp, $tgl_libur )) {

	            $hari_temp = date( 'd', $waktu_temp );
	            $hari[] = $hari_temp;
	            //$hari_temp[] = $hari_temp;
	            
	            }
	            $waktu_temp = strtotime ( '+1 day' , $waktu_temp );
	            //$h[] = $hari_temp;
	        }
	        return count($hari)-1;
	    }
	}
