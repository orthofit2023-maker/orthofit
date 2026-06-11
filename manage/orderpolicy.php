<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$pdfpath=$_SERVER['DOCUMENT_ROOT']."/media/";
if($_GET['orderid']!=""){
	$orderid=trim($_GET['orderid']);
	include("orderpg.php"); 
	//echo $ordpg;
	$invoiceno = getordno($orderid, $resord['orddate']);
	$orddate = trim($resord['orddate']);
	$orderip = dbval($resord['orderip']);

	$pdftext=getpagedata('31');

	$pdftext=str_replace("##customername##",$billing_username.' '.$billing_lastname,$pdftext);
	$pdftext=str_replace("##shoppingcart##",$ordpg,$pdftext);
	$pdftext=str_replace("##invoiceno##",$invoiceno,$pdftext);
	$pdftext=str_replace("##orddate##",$orddate,$pdftext);
	$pdftext=str_replace("##orderip##",$orderip,$pdftext);
	$pdftext=str_replace("##invoiceno##",$invoiceno,$pdftext);


	//echo $pdftext;
	//exit();

	//if(!file_exists($imgpath.'reports/'.$misid.'.pdf')){
		

		require_once('tcpdf/tcpdf_include.php');
		//define ("MISCODE", getmiscode($misid));

		// create new PDF document
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

		//$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetHeaderData('','','Payal Singhal Design House LLP', PDF_HEADER_STRING);
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', '10'));
		//$pdf->SetFooterData('','',$invoiceno, PDF_FOOTER_STRING);
		$pdf->Footer();
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setCellHeightRatio(1.2);


		$pdf->SetFont('helvetica', '', 10);
		$pdf->AddPage();

		//$bankrepo=htmlentities($bankrepo);

		$pdf->writeHTML($pdftext, true, false, true, false, '');
		$pdf->lastPage();

		$pdf->Output($pdfpath.$orderid.'.pdf', 'F');
		//dispatch will not change for reactivation
		//mysql_query("update wfr_misstages set status='1', loginid='".$_SESSION['loginid']."' where stageid='41' and misid='$misid'") or die(mysql_error());
		//mysql_query("update wfr_mis set dispatch=CURDATE() where misid='$misid' and (dispatch='0000-00-00' or dispatch is null)") or die(mysql_error());
		//mysql_query("update wfr_misstages set status='1' where stageid='38' and misid='$misid' and status='0'") or die(mysql_error());
	//}


header('Content-type: application/pdf');
header("Content-disposition:inline;filename=".$pdfpath.$orderid.".pdf"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
readfile($pdfpath.$orderid.'.pdf');

}



?>