<?php 
ini_set('max_execution_time', '600');
session_start();
// require_once('resources/html2pdf/html2pdf.class.php');
require_once dirname(__FILE__).'/resources/html2pdf2/vendor/autoload.php';
include "db_functions.php";
// include "process/config.php";

// require("resources/class.phpmailer.php");
header("Content-Type:application/json");
$data2 = json_decode(file_get_contents('php://input'), true);
if(!empty($data2))
{
	$data1 = $data2[0];
	// echo '<pre>'; print_r($data1); exit;
	$data=array();
	$data['society_name'] = $data1['society_name'];
    $data['society_name_number'] = $data1['society_name_number'];
    $data['society_name_date'] = $data1['society_name_date'];
    $data['society_address'] = $data1['society_address'];
    $data['no'] = $data1['no'];
    $data['date'] = $data1['date'];
    $data['by_ledger_name'] = $data1['by_ledger_name'];
    $data['amount_word'] = $data1['amount_word'];
    $data['narration'] = $data1['narration'];
    $data['unit_no'] = $data1['unit_no'];
    $data['amount'] = $data1['amount'];
    $data['response_type'] = $data1['response_type'];

    $_POST=$data;

}
if(isset($_POST))
{
	$data=$_POST;
	if(!isset($data['page_size']))
	{
		$data['page_size']="A4";
	}
	if(!isset($data['response_type']))
	{
		$data['response_type']="view";
	}
	// echo '<pre>'; print_r($data); exit;
	echo $pdf_file=GeneratePaymentPDF($data);
					
}



?>