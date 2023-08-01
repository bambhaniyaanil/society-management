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
	$data1 = json_decode($data2[0], true);
	// echo '<pre>'; print_r($data1); exit;
	$data=array();
	$data['society_name'] = $data1['society_name'];
    $data['society_name_number'] = $data1['society_name_number'];
    $data['society_name_date'] = $data1['society_name_date'];
    $data['society_address'] = $data1['society_address'];
    $data['unit_no'] = $data1['unit_no'];
    $data['area_sq_mtr'] = $data1['area_sq_mtr'];
    $data['area_sq_ft'] = $data1['area_sq_ft'];
    $data['bill_no'] = $data1['bill_no'];
    $data['name'] = $data1['name'];
    $data['bill_date'] = $data1['bill_date'];
    $data['bill_period'] = $data1['bill_period'];
    $data['due_date'] = $data1['due_date'];
    $data['to_ledger'] = $data1['to_ledger'];
	// $data['to_ledger'][0] = Array('name' => "abc",'amount' => 500);
    // $data['to_ledger'][1] = Array('name' => "test,test1",'amount' => 500);
    // $data['to_ledger'][2] = Array('name' => "Test,pqr",'amount' => 300);


		$data['total_amount'] = $data1['total_amount'];
		$data['arrears'] = $data1['arrears'];
		$data['interest_amount'] = $data1['interest_amount'];
		$data['total_due_amount_payable'] = $data1['total_due_amount_payable'];
		$data['billing_notes'] = $data1['billing_notes'];
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
	echo $pdf_file=generate_pdf($data);
					
}