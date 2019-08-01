<?php
class Pdf {
 
    function __construct() {
        include_once APPPATH . 'third_party/fpdf.php';
    }

    function Footer()
	{
	    // Go to 1.5 cm from bottom
	    $this->SetY(-15);
	    // Select Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Print centered page number
	    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
	}
	function SetCol($col)
	{
	    // Move position to a column
	    $this->col = $col;
	    $x = 10+$col*65;
	    $this->SetLeftMargin($x);
	    $this->SetX($x);
	}

	function AcceptPageBreak()
	{
	    if($this->col<2)
	    {
	        // Go to next column
	        $this->SetCol($this->col+1);
	        $this->SetY(10);
	        return false;
	    }
	    else
	    {
	        // Go back to first column and issue page break
	        $this->SetCol(0);
	        return true;
	    }
	}
}
?>