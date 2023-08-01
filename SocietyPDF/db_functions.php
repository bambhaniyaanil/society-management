<?php
//include "db_config_function.php";
//echo "<script>console.log(".json_encode($conn).")</script>";
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


error_reporting(0);

// Report runtime errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Report all errors
error_reporting(E_ALL);

// Same as error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);

function getIndianCurrency(float $number)
{
	$decimal = round($number - ($no = floor($number)), 2) * 100;
	$hundred = null;
	$digits_length = strlen($no);
	$i = 0;
	$str = array();
	$words = array(
		0 => '', 1 => 'one', 2 => 'two',
		3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
		7 => 'seven', 8 => 'eight', 9 => 'nine',
		10 => 'ten', 11 => 'eleven', 12 => 'twelve',
		13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
		16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
		19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
		40 => 'forty', 50 => 'fifty', 60 => 'sixty',
		70 => 'seventy', 80 => 'eighty', 90 => 'ninety'
	);
	$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
	while ($i < $digits_length) {
		$divider = ($i == 2) ? 10 : 100;
		$number = floor($no % $divider);
		$no = floor($no / $divider);
		$i += $divider == 10 ? 1 : 2;
		if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
		} else $str[] = null;
	}
	$Rupees = implode('', array_reverse($str));
	$paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
	//	return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ." Paise";
	return $Rupees . ' Only';
}



function generate_pdf($data)
{
	//heading values
	if (isset($data['page_size'])) {
		if ($data['page_size'] == 'legal') {
			$page_size = array(216, 356);
		} else {
			$page_size = $data['page_size'];
		}
	} else {
		$page_size = "A4";
	}

	ob_start();

?>
<style type="text/css">
.table {
    border-collapse: collapse;
}

.table,
.th,
.td {
    border-bottom: 2px solid #B3B9BC;
}


.text_head {
    color: #B3B9BC;
}

page {
    font-family: FreeSerif;
    color: #3C3B3B;
}
</style>
<?php
	if (isset($page_size) && $page_size == 'legal') {
	?>
<style type="text/css">
.font-11 {
    font-size: 11px;
}

.font-12 {
    font-size: 16px;
}

.font-13 {
    font-size: 13px;
}

.font-14 {
    font-size: 14px;
}

.font-15 {
    font-size: 15px;
}

.font-16 {
    font-size: 16px;
}

.font-17 {
    font-size: 17px;
}

.font-18 {
    font-size: 18px;
}

.font-19 {
    font-size: 19px;
}

.font-20 {
    font-size: 20px;
}

.font-21 {
    font-size: 21px;
}

.font-22 {
    font-size: 22px;
}

.font-23 {
    font-size: 23px;
}

.font-24 {
    font-size: 24px;
}

.font-25 {
    font-size: 25px;
}
</style>
<?php
	} else {
	?>
<style type="text/css">
.font-11 {
    font-size: 11px;
}

.font-12 {
    font-size: 12px;
}

.font-13 {
    font-size: 13px;
}

.font-14 {
    font-size: 14px;
}

.font-15 {
    font-size: 15px;
}

.font-16 {
    font-size: 16px;
}

.font-17 {
    font-size: 17px;
}

.font-18 {
    font-size: 18px;
}

.font-19 {
    font-size: 19px;
}

.font-20 {
    font-size: 20px;
}

.font-21 {
    font-size: 21px;
}

.font-22 {
    font-size: 22px;
}

.font-23 {
    font-size: 23px;
}

.font-24 {
    font-size: 24px;
}

.font-25 {
    font-size: 25px;
}

/* row{
    margin-top: 10px;
    margin-bottom: 10px;
    padding-top: 10px;
    padding-bottom: 10px;
} */
</style>
<?php
	}
	?>
<page>
    <?php //echo json_encode($user_data_print);
		?>



    <?php
		$header_data_print = array();
		$header_data_print[0] = $data['society_name'];
		$header_data_print[1] = $data['society_name_number'] . " " . $data['society_name_date'];
		$header_data_print[2] = $data['society_address'];
		if (isset($header_data_print))
		//print_r($getdata);
		{ ?>
    <table align="center"
        style="width:90%;margin-top:25px;padding-bottom:10px;/*border-top-left-radius:8px;border-top-right-radius:8px;*/">
        <?php
				for ($i = 0; $i < count($header_data_print); $i++) {
					if ($i == 0) { ?>
        <tr>
            <td style="text-align:center;font-size:30px;font-weight:bold;padding-bottom:10px;">
                <?php echo $header_data_print[$i]; ?></td>
        </tr>
        <?php
					} elseif ($i == 2) { ?>
        <tr>
            <td class="font-17"
                style="width:100%;text-align:center;border-top: 1px solid black;border-bottom: 1px solid black;">
                <?php echo $header_data_print[$i]; ?></td>
        </tr>
        <?php
					} else { ?>
        <tr>
            <td class="font-17" style="width:100%;text-align:center;"><?php echo $header_data_print[$i]; ?></td>
        </tr>
        <?php
					}
				}
				?>
    </table>
    <br>
    <?php
		} ?>




    <h2 style="text-align: center;font-weight: 550;">MAINTENANCE INVOICE</h2>
    <?php /*if(isset($user_data_print))
		
	{*/
		?>
    <table style="width:90%;margin-left:38px;border:1px solid black;padding: 15px;" class="font-15">
        <tr>
            <th style="width:15%;">
                Unit No:
            </th>
            <td style="width:15%;">
                <?php if (isset($data['unit_no'])) echo $data['unit_no']; ?>
            </td>
            <th style="width:10%;">
                Area:
            </th>
            <td style="width:20%;">
                <?php if (isset($data['area_sq_mtr']) && !empty($data['area_sq_mtr'])) echo $data['area_sq_mtr'] . ' Sq.Mtr'; ?>
                <?php if (isset($data['area_sq_ft']) && !empty($data['area_sq_ft'])) echo $data['area_sq_ft'] . ' Sq.Ft'; ?>
            </td>
            <th style="width:15%;">
                Bill No:
            </th>
            <td style="width:15%;">
                <?php if (isset($data['bill_no'])) echo $data['bill_no']; ?>
            </td>
        </tr>
        <?php
			// for($i=3;$i<count($user_data_print);)
			// {
			echo "<tr>";
			echo '<th style="width:15%;">Name:</th>';
			echo '<td style="width:55%;" colspan="3">' . $data['name'] . '</td>';
			echo '<th style="width:15%;">Bill Date:</th>';
			echo '<td style="width:15%;">' . $data['bill_date'] . '</td>';
			echo "</tr>";

			echo "<tr>";
			echo '<th style="width:15%;">Bill Period:</th>';
			echo '<td style="width:55%;" colspan="3">' . $data['bill_period'] . '</td>';
			echo '<th style="width:15%;">Due Date:</th>';
			echo '<td style="width:15%;">' . $data['due_date'] . '</td>';
			echo "</tr>";


			// $i+=2;
			// }
			?>

    </table>
    <br>
    <?php
		// }
		?>

    <table style="width:90%;margin-left:38px;margin-top:10px;border:1px solid black;border-collapse:collapse;">
        <tr>
            <th class="font-15" style="width:10%;border:1px solid black;padding:5px;text-align: center">Sr No.</th>
            <th class="font-15" style="width:70%;border:1px solid black;padding:5px;">PARTICULARS OF CHARGES</th>
            <th class="font-15" style="width:20%;border:1px solid black;padding:5px;text-align: center;">Amount</th>
        </tr>
        <?php
			for ($i = 0; $i < count($data['to_ledger']); $i++) {
				$perticular_data = $data['to_ledger'][$i];
			?>
        <tr>
            <td class="font-13"
                style="width:10%;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;padding:5px;padding-left:10px;text-align: center">
                <?php echo $i + 1; ?></td>
            <td class="font-13"
                style="width:70%;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;padding:5px;padding-left:10px;"><?php if (isset($perticular_data['name'])) {
																																													echo $perticular_data['name'];
																																												} ?></td>
            <td class="font-13"
                style="width:20%;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;padding:5px;padding-left: 15px; text-align:left;padding-right:30px;">Rs.<?php if (isset($perticular_data['amount'])) {
																																																							echo number_format($perticular_data['amount'], 2, ".", ",");
																																																						} ?></td>
        </tr>
        <?php
			}

			?>
    </table>
    <table style="width:90%;margin-left:38px;border:1px solid black;border-collapse:collapse;">
        <?php
			// $cnt_arr=count($arrear_data_heading);
			// for($i=0;$i<$cnt_arr;$i++)
			// 	{

			// 		if($i!=($cnt_arr-1))
			// 		{
			?>
        <tr>
            <td class="font-13"
                style="width:45%;border-right:1px solid black;border-left:1px solid black;padding:5px;padding-left:10px;">
            </td>

            <td class="font-13"
                style="width:35%;border-bottom:1px solid black;border-right:1px solid black;padding:5px;text-align:left;padding-right:30px;">
                Total Amount </td>
            <td class="font-13"
                style="width:20%;padding: 5px;padding-left: 15px;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;text-align:left;padding-right:30px;">
                Rs. <?php if (isset($data['total_amount'])) {
																																																							echo number_format($data['total_amount'], 2, ".", ",");
																																																						} ?> </td>
        </tr>
        <tr>
            <td class="font-13"
                style="width:45%;border-right:1px solid black;border-left:1px solid black;padding:5px;padding-left:10px;">
            </td>

            <td class="font-13"
                style="width:35%;border-bottom:1px solid black;border-right:1px solid black;padding:5px;text-align:left;padding-right:30px;">
                Arrears </td>
            <td class="font-13"
                style="width:20%;padding: 5px;padding-left: 15px;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;text-align:left;padding-right:30px;">
                Rs. <?php if (isset($data['arrears'])) {
																																																							echo number_format($data['arrears'], 2, ".", ",");
																																																						} ?> </td>
        </tr>
        <tr>
            <td class="font-13"
                style="width:45%;border-right:1px solid black;border-left:1px solid black;padding:5px;padding-left:10px;">
            </td>

            <td class="font-13"
                style="width:35%;border-bottom:1px solid black;border-right:1px solid black;padding:5px;text-align:left;padding-right:30px;">
                Intrest </td>
            <td class="font-13"
                style="width:20%;padding: 5px;padding-left: 15px;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;text-align:left;padding-right:30px;">
                Rs. <?php if (isset($data['interest_amount'])) {
																																																							echo number_format($data['interest_amount'], 2, ".", ",");
																																																						} ?> </td>
        </tr>
        <tr>
            <td class="font-13"
                style="width:45%;border-right:1px solid black;border-left:1px solid black;padding:5px;padding-left:10px;border-bottom:1px solid black;">
            </td>

            <td class="font-13"
                style="width:35%;border-bottom:1px solid black;border-right:1px solid black;padding:5px;text-align:left;padding-right:30px;">
                Total Payable Amount </td>
            <td class="font-13"
                style="width:20%;padding: 5px;padding-left: 15px;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;text-align:left;padding-right:30px;">
                Rs. <?php if (isset($data['total_due_amount_payable'])) {
																																																							echo number_format($data['total_due_amount_payable'], 2, ".", ",");
																																																						} ?> </td>
        </tr>
        <?php
			// 	}

			// }
			?>

    </table>
    <?php
		if (isset($data['billing_notes']) && $data['billing_notes'] != "") {

			echo '<div class="font-12" style="width:85.4%;margin-left:38px;margin-right:38px;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;padding:15px;"><b style="padding-bottom:10px;">Notes & E.&O.E.</b><br>' . $data['billing_notes'] . '</div>';
		}
		/*
if(isset($receipt_data_heading) && isset($receipt_data_print[0]) && $receipt_data_print[0]!="")
{?>


    <table
        style="width:83.25%;margin-left:38px;margin-right:20px;margin-top:25px;padding-bottom:30px;border:1px solid black;">
        <tr>
            <td class="font-11" style="text-align:left;padding-bottom:2px;width:23.33%;"></td>
            <th class="font-17" style="text-align:left;padding-bottom:2px;padding-left:145px;width:61.66%;">
                <u>RECEIPT</u></th>
            <td class="font-11" style="text-align:right;padding-right:15px;padding-bottom:2px;width:15%;"></td>
        </tr>
        <tr>
            <td class="font-11" style="text-align:left;padding-bottom:2px;width:23.33%;">
                <?php if(isset($receipt_data_heading[0])){echo $receipt_data_heading[0];}?>:<b
                    style="padding-left:10px;"><?php if(isset($receipt_data_print[0])){echo $receipt_data_print[0];}?></b>
            </td>
            <th class="font-17" style="text-align:left;padding-bottom:2px;padding-left:145px;width:61.66%;"><u></u></th>
            <td class="font-11" style="text-align:right;padding-right:15px;padding-bottom:2px;width:15%;">
                <?php if(isset($receipt_data_heading[1]) && isset($receipt_data_print[1])){echo $receipt_data_heading[1].":".$receipt_data_print[1];}?>
            </td>
        </tr>
        <tr>
            <th class="font-14" colspan=3 style="text-align:right;padding-top:-7px;width:50%;text-align:center;">
                <?php if(isset($receipt_data_heading[7]))echo $receipt_data_heading[7];?>
                <?php if(isset($receipt_data_print[7]))echo " ".$receipt_data_print[7];?></th>
        </tr>
        <tr>
            <td class="font-11" style="text-align:left;padding-bottom:2px;width:23.33%;padding-top:-12px;">Received with
                thanks from</td>
            <th class="font-11" colspan=2 style="text-align:left;padding-bottom:2px;padding-right:80px;width:51.66%;">
                <?php if(isset($user_data_print[3]))echo $user_data_print[3];?></th>
        </tr>
        <tr>
            <td class="font-11" style="text-align:left;width:23.33%;"></td>
            <th class="font-11" colspan=2 style="text-align:left;padding-right:80px;width:51.66%;"></th>
        </tr>
        <tr>
            <td class="font-11" colspan=2 style="text-align:left;padding-bottom:2px;width:73.33%;">
                <?php echo ucwords("Rs. ".getIndianCurrency($receipt_data_print[3])."Only.");?></td>
            <td class="font-11"
                style="text-align:center;padding-top:10px;padding-bottom:10px;padding-right:15px;padding-left:15px;width:20%;border:1px solid black;border-radius: 10px;">
                <b><?php if(isset($receipt_data_print[3])){echo number_format($receipt_data_print[3],2,".",",");}?></b>
            </td>
        </tr>
        <tr>
            <td class="font-11" style="width:23.33%;padding-top:-7px;">
                <?php if(isset($receipt_data_heading[2])){echo $receipt_data_heading[2];}?></td>
            <td class="font-11" style="width:43.33%;padding-top:-7px;">
                <?php if(isset($receipt_data_print[2]) && isset($receipt_data_print[4])){echo $receipt_data_print[2]." ".$receipt_data_print[4];}?>
            </td>
            <td class="font-11" style="width:13.33%;text-align:left;padding-left:-30px;padding-top:-45px;">Rs. </td>
        </tr>
        <tr>
            <td class="font-11" style="width:13.33%;"></td>
            <td class="font-11" style="width:33.33%;text-align:left;">Towards Maintenance</td>
            <td class="font-11" style="width:23.33%;padding-top:-7px;padding-left:-25px;">
                <b><?php if(isset($receipt_data_print[5])){echo $receipt_data_print[5];}?></b></td>
        </tr>
        <br>
    </table>

    <?php	
}*/

		?>
    <table style="width:90%;margin-left:38px;border-collapse:collapse;">
        <tr>
            <td style="width:40%;"></td>
            <td class="font-15" style="width:60%;text-align:right;padding-top: 10px;"><b>FOR <?php if (isset($data['society_name'])) {
																										echo $data['society_name'];
																									} ?></b></td>
        </tr>

        <tr>
            <td style="width:40%;"></td>
            <td class="font-11" style="width:60%;text-align:center;"> <br><br><br></td>
        </tr>
        <tr>
            <td style="width:40%;"></td>
            <td class="font-13" style="width:60%;text-align:right;">Authorized Signatory</td>
        </tr>
    </table>

</page>
<?php

	$pdf_content = ob_get_clean();
	$dt = date('Y-m-d');



	if (isset($data['response_type']) && $data['response_type'] == "mail") {
		$html2pdf = new HTML2PDF('P', $page_size, 'en', true, 'UTF-8', array(0, 0, 0, 0));
		$html2pdf->WriteHTML($pdf_content);
		return $html2pdf->Output();
	} elseif (isset($data['response_type']) && $data['response_type'] == "view") {
		$html2pdf = new HTML2PDF('P', $page_size, 'en', true, 'UTF-8', array(0, 0, 0, 0));
		$html2pdf->WriteHTML($pdf_content);
		if (isset($data['unit_no']) && isset($data['bill_no']) && isset($data['bill_date'])) {
			$name = str_replace(" ", "_", $data['society_name']) . "-" . $data['unit_no'] . "_BLN-" . $data['bill_no'] . "_DT-" . date('d.m.Y', strtotime($data['bill_date'])) . ".pdf";
		} else {
			$name = $data['society_name'] . "-Bill.pdf";
		}
		$name = str_replace('/', '-', $name);
		$html2pdf->Output(getcwd() . "/mail_pdfs/$name", 'F');
		return $name;
	} else {
		$html2pdf = new HTML2PDF('P', $page_size, 'en', true, 'UTF-8', array(0, 0, 0, 0));
		$html2pdf->WriteHTML($pdf_content);
		if (isset($data['unit_no']) && isset($data['bill_no']) && isset($data['bill_date'])) {
			$name = str_replace(" ", "_", $data['society_name']) . "-" . $data['unit_no'] . "_BLN-" . $data['bill_no'] . "_DT-" . date('d.m.Y', strtotime($data['bill_date'])) . ".pdf";
		} else {
			$name = $data['society_name'] . "-Bill.pdf";
		}
		$name = str_replace('/', '-', $name);

		// if(isset($getdata[0]) && $getdata[0]=='all')
		// {
		// 	$html2pdf->Output(getcwd()."/mail_pdfs/$name",'F');
		// //return getcwd()."/assets/pdfs/$name";
		// 	return $name;
		// }else
		// {
		return $html2pdf->Output($name, 'D');
		// }
	}
}

function GenerateReceiptPDF($data)
{

	ob_start();

	if (isset($data['page_size'])) {
		if ($data['page_size'] == 'legal') {
			$page_size = array(216, 356);
		} else {
			$page_size = $data['page_size'];
		}
	} else {
		$page_size = "A4";
	}
	?>
<style type="text/css">
.table,
table {
    border-collapse: collapse;
}

.table,
.th,
.td {
    border-bottom: 2px solid #B3B9BC;
}


.text_head {
    color: #B3B9BC;
}

page {
    font-family: FreeSerif;
    color: #3C3B3B;
}
</style>
<?php
	if (isset($page_size) && $page_size == 'legal') {
	?>
<style type="text/css">
.font-11 {
    font-size: 11px;
}

.font-12 {
    font-size: 16px;
}

.font-13 {
    font-size: 13px;
}

.font-14 {
    font-size: 14px;
}

.font-15 {
    font-size: 15px;
}

.font-16 {
    font-size: 16px;
}

.font-17 {
    font-size: 17px;
}

.font-18 {
    font-size: 18px;
}

.font-19 {
    font-size: 19px;
}

.font-20 {
    font-size: 20px;
}

.font-21 {
    font-size: 21px;
}

.font-22 {
    font-size: 22px;
}

.font-23 {
    font-size: 23px;
}

.font-24 {
    font-size: 24px;
}

.font-25 {
    font-size: 25px;
}
</style>
<?php
	} else {
	?>
<style type="text/css">
.font-11 {
    font-size: 11px;
}

.font-12 {
    font-size: 12px;
}

.font-13 {
    font-size: 13px;
}

.font-14 {
    font-size: 14px;
}

.font-15 {
    font-size: 15px;
}

.font-16 {
    font-size: 16px;
}

.font-17 {
    font-size: 17px;
}

.font-18 {
    font-size: 18px;
}

.font-19 {
    font-size: 19px;
}

.font-20 {
    font-size: 20px;
}

.font-21 {
    font-size: 21px;
}

.font-22 {
    font-size: 22px;
}

.font-23 {
    font-size: 23px;
}

.font-24 {
    font-size: 24px;
}

.font-25 {
    font-size: 25px;
}
</style>
<?php
	}
	?>
<page>
    <?php //echo json_encode($user_data_print);
		?>



    <?php
		$header_data_print = array();
		$header_data_print[0] = $data['society_name'];
		$header_data_print[1] = $data['society_name_number'] . " " . $data['society_name_date'];
		$header_data_print[2] = $data['society_address'];

		$particular_data_heading = array();
		$particular_data_heading[0] = "BY";
		// $particular_data_heading[1]="NUMBER";
		$particular_data_heading[1] = "PARTICULARS";
		$particular_data_heading[2] = "UNIT NO.";

		$particular_data_print = array();
		$particular_data_print[0] = "CHEQUE / UPI / NEFT / RTGS";
		$particular_data_print[1] = $data['narration'];
		$particular_data_print[2] = "-";
		// $particular_data_print[3]="-";

		$data['amount_words'] = getIndianCurrency($data['amount']);
		// $particular_data_print[1]=$data['society_name_number']." ".$data['society_name_date'];
		// $particular_data_print[2]=$data['society_address'];

		if (isset($header_data_print))
		//print_r($getdata);
		{ ?>
    <table style="width: 90%;margin-left:5%;margin-top:25px;" CELLSPACING="0">
        <tr>
            <td style="width:72%;border:1px solid black;padding-bottom:10px;">
                <?php
						for ($i = 0; $i < count($header_data_print); $i++) {
							if ($i == 0) { ?>
                <div style="text-align:center;font-size:25px;font-weight:bold;padding-bottom: 10px;">
                    <?php echo $header_data_print[$i]; ?></div>
                <?php
							} else { ?>
                <div class="font-17" style="text-align:center;padding-bottom: 10px;">
                    <?php echo $header_data_print[$i]; ?></div>
                <?php
							}
						}
						?>

            </td>
            <td style="width:28%;border:1px solid black;border-left:0;padding-bottom:10px;">
                <div
                    style="text-align:center;font-size:23px;font-weight:bold;padding-bottom: 10px;background-color:#f58220;color: white;vertical-align: top;">
                    RECEIPT</div>
                <!--<p class="font-17" style="text-align:center;"><br><br><br><br></p>-->
                <?php
						/*for($i=0;$i<2;$i++)
				{
					if(isset($receipt_data_heading[$i]) && isset($receipt_data_print[$i]))
					{*/
						?>
                <p class="font-17" style="text-align:left;padding-left: 5px;"><?php echo "<b>NO:</b>  " . $data['no'] ?>
                </p>
                <p class="font-17" style="text-align:left;padding-left: 5px;">
                    <?php echo "<b>DATE:</b>  " . $data['date'] ?></p>
                <?php
						/*}
				}*/
						?>



            </td>
        </tr>

        <tr>
            <td style="width:72%;border:1px solid black;padding-bottom:10px;border-bottom: 0;">
                <?php
						// for($i=0;$i<count($user_data_heading);$i++)
						// {
						if (isset($data['by_ledger_name'])) {
						?>
                <div class="font-15"
                    style="text-align:left;padding-bottom: 10px;padding-left:15px;<?php if ($i == 0) { ?>padding-top: 15px;<?php } ?>">
                    <?php echo "<b>RECEIVED WITH THANKS FROM SHRI / SMT :</b> "; ?>
                    <?php if (strlen($data['by_ledger_name']) > 64) { ?>
                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px;">
                        <?php echo substr($data['by_ledger_name'], 0, 63); ?>
                    </div>
                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px;">
                        <?php echo substr($data['by_ledger_name'], 63); ?>
                    </div>
                    <?php } else { ?>
                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px;">
                        <?php echo $data['by_ledger_name']; ?>
                    </div>
                    <!--<div style="width:90%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px;">&nbsp;
								</div>-->
                    <?php } ?>
                </div>

                <div class="font-15"
                    style="text-align:left;padding-bottom: 10px;padding-left:15px;<?php if ($i == 0) { ?>padding-top: 15px;<?php } ?>">
                    <?php echo "<b>THE SUM OF RUPEES</b> "; ?>
                    <?php if (strlen($data['amount_words']) > 64) { ?>
                    <div
                        style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px; text-transform: capitalize;">
                        <?php echo substr($data['amount_words'], 0, 63); ?>
                    </div>
                    <div
                        style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px; text-transform: capitalize;">
                        <?php echo substr($data['amount_words'], 63); ?>
                    </div>
                    <?php } else { ?>
                    <div
                        style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px; text-transform: capitalize;">
                        <?php echo $data['amount_words']; ?>
                    </div>

                    <?php } ?>
                </div>
                <?php
						}
						// }	
						?>

            </td>
            <td style="width:28%;border:1px solid black;border-left:0;padding-bottom:10px;">

                <!--<p class="font-17" style="text-align:center;"><br><br><br><br></p>-->
                <?php
						// for($i=2;$i<3;$i++)
						// {
						if (isset($data['amount'])) {
						?>
                <div class="font-17" style="text-align:left;padding-left: 5px;"><b style="font-size: 27px;"> ₹ </b>
                    <div style='width:80%;padding:5px;margin-left:7px;border:1px solid black;border-radius: 8px;'>
                        <?php echo "  " . $data['amount'];  ?></div>
                </div>
                <?php
						}
						// }
						?>



            </td>
        </tr>
        <tr>
            <td style="width:72%;border:1px solid black;padding-bottom:10px;border-top: 0;">
                <table class="font-15" style="text-align:left;padding-bottom: 10px;padding-left:15px;width:100%;"
                    cellspacing="0">
                    <tr>
                        <?php
								for ($i = 0; $i < count($particular_data_heading) - 1; $i++) {
									if (isset($particular_data_heading[$i])) { ?>
                        <th style="<?php if ($i != count($particular_data_heading) - 2) {
														echo "width :20%;";
													} else {
														echo "width :40%;";
													} ?>border: 1px solid black;padding: 7px;">
                            <?php echo $particular_data_heading[$i]; ?>
                        </th>
                        <?php
									}
								}
								?>
                    </tr>
                    <tr>
                        <?php
								for ($i = 0; $i < count($particular_data_print) - 1; $i++) {
									if (isset($particular_data_print[$i]) && isset($particular_data_print[$i])) { ?>
                        <td
                            style="<?php if ($i == 0) {
														echo "font-weight: bold;width :20%;";
													} else {
														echo "width :40%;";
													} /*if($i!=count($particular_data_print)-2){ echo "width :25%;"; }else{ echo "width :40%;"; }*/ ?>border: 1px solid black;padding: 7px;">
                            <?php echo $particular_data_print[$i]; ?>
                        </td>
                        <?php
									}
								}
								?>
                    </tr>
                    <!--<tr>
					<th style="width :25%;border: 1px solid black;padding: 7px;border-right: 0;">
						<?php echo $particular_data_heading[(count($particular_data_heading) - 1)]; ?>			
					</th>
					<td style="width :71.3%;border: 1px solid black;padding: 7px;border-left: 0;" colspan="2">
						<?php echo $particular_data_print[(count($particular_data_print) - 1)]; ?>			
					</td>
				</tr>-->
                    <tr>
                        <td style="border-bottom: 1px solid black;">&nbsp;</td>
                        <td colspan="2" style="border-bottom: 1px solid black;">&nbsp;</td>
                    </tr>
                    <tr style="padding-top: 15px;">
                        <th style="width :15%;border: 1px solid black;padding: 7px;">
                            UNIT NO.
                        </th>
                        <td style="width :50%;border: 1px solid black;padding: 7px;border-left: 0;" colspan="2">
                            <?php echo $data['unit_no']; ?>
                        </td>
                    </tr>
                    <tr style="padding-top: 15px;">

                        <td class="font-13" style="width :96.3%;padding: 7px;border-left: 0;" colspan="3">
                            <b>Notes</b><br>
                            <?php echo nl2br($data['billing_notes']); ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:28%;border:1px solid black;border-left:0;padding-bottom:10px;">

                <!--<p class="font-17" style="text-align:center;"><br><br><br><br></p>-->
                <?php
						// for($i=3;$i<4;$i++)
						// {
						if (isset($data['society_name'])) {
						?>
                <div class="font-17" style="text-align:center;padding-bottom: 15px;">
                    <b>
                        <?php echo 'For ' . $data['society_name']; ?>
                    </b>
                </div>
                <?php
						}
						// }
						?>
                <div class="font-14" style="text-align:left;padding-left: 5px;">
                    <div style='width:60%;height:125px;margin-left:17%;border:1px dashed black;border-radius: 8px;'>
                    </div>
                </div>
                <div class="font-14" style="text-align:center;padding-left: 5px;font-weight: bold;padding-top: 15px;">
                    AUTHORIZED SIGNATORY</div>


            </td>
        </tr>
    </table>
    <br>
    <?php
		} ?>








</page>
<?php

	$pdf_content = ob_get_clean();
	$dt = date('Y-m-d');



	if (isset($data['response_type']) && $data['response_type'] == "mail") {

		$html2pdf = new Html2Pdf('P', $page_size, 'en', true, 'UTF-8', array(0, 0, 0, 0));
		$html2pdf->writeHTML($pdf_content);
		return $html2pdf->output();
	} elseif (isset($data['response_type']) && $data['response_type'] == "view") {
		if (isset($data['society_name']) && isset($data['unit_no']) && isset($data['date'])) {
			$name = str_replace(" ", "_", $data['society_name']) . "_" . $data['unit_no'] . "_DT-" . date('d.m.Y', strtotime($data['date'])) . ".pdf";
		} else {
			$name = "GreenwoodSociety-Receipt.pdf";
		}


		$html2pdf = new Html2Pdf('P', $page_size, 'en', true, 'UTF-8', array(0, 0, 0, 0));
		$html2pdf->writeHTML($pdf_content);
		$name = str_replace('/', '-', $name);
		$html2pdf->output(getcwd() . "/mail_pdfs/$name", 'F');
		return $name;
	} else {
		if (isset($receipt_data_print[0]) && isset($receipt_data_print[1])) {
			$name = "Greenwood_Receipt_" . $receipt_data_print[0] . "_DT-" . date('d.m.Y', strtotime($receipt_data_print[1])) . ".pdf";
		} else {
			$name = "GreenwoodSociety-Receipt.pdf";
		}
		$name = str_replace('/', '-', $name);


		$html2pdf = new Html2Pdf('P', $page_size, 'en', true, 'UTF-8', array(0, 0, 0, 0));
		$html2pdf->writeHTML($pdf_content);

		if (isset($getdata[0]) && $getdata[0] == 'all') {
			$html2pdf->output(getcwd() . "/mail_pdfs/$name", 'F');
			//return getcwd()."/assets/pdfs/$name";
			return $name;
		} else {
			return $html2pdf->output($name, 'D');
		}
	}
}








function GeneratePaymentPDF($data)
{

	ob_start();

	if (isset($data['page_size'])) {
		if ($data['page_size'] == 'legal') {
			$page_size = array(216, 356);
		} else {
			$page_size = $data['page_size'];
		}
	} else {
		$page_size = "A4";
	}
	?>
<style type="text/css">
.table,
table {
    border-collapse: collapse;
}

.table,
.th,
.td {
    border-bottom: 2px solid #B3B9BC;
}


.text_head {
    color: #B3B9BC;
}

page {
    font-family: FreeSerif;
    color: #3C3B3B;
}
</style>
<?php
	if (isset($page_size) && $page_size == 'legal') {
	?>
<style type="text/css">
.font-11 {
    font-size: 11px;
}

.font-12 {
    font-size: 16px;
}

.font-13 {
    font-size: 13px;
}

.font-14 {
    font-size: 14px;
}

.font-15 {
    font-size: 15px;
}

.font-16 {
    font-size: 16px;
}

.font-17 {
    font-size: 17px;
}

.font-18 {
    font-size: 18px;
}

.font-19 {
    font-size: 19px;
}

.font-20 {
    font-size: 20px;
}

.font-21 {
    font-size: 21px;
}

.font-22 {
    font-size: 22px;
}

.font-23 {
    font-size: 23px;
}

.font-24 {
    font-size: 24px;
}

.font-25 {
    font-size: 25px;
}
</style>
<?php
	} else {
	?>
<style type="text/css">
.font-11 {
    font-size: 11px;
}

.font-12 {
    font-size: 12px;
}

.font-13 {
    font-size: 13px;
}

.font-14 {
    font-size: 14px;
}

.font-15 {
    font-size: 15px;
}

.font-16 {
    font-size: 16px;
}

.font-17 {
    font-size: 17px;
}

.font-18 {
    font-size: 18px;
}

.font-19 {
    font-size: 19px;
}

.font-20 {
    font-size: 20px;
}

.font-21 {
    font-size: 21px;
}

.font-22 {
    font-size: 22px;
}

.font-23 {
    font-size: 23px;
}

.font-24 {
    font-size: 24px;
}

.font-25 {
    font-size: 25px;
}
</style>
<?php
	}
	?>
<page>
    <?php //echo json_encode($user_data_print);
		?>



    <?php
		$header_data_print = array();
		$header_data_print[0] = $data['society_name'];
		$header_data_print[1] = $data['society_name_number'] . " " . $data['society_name_date'];
		$header_data_print[2] = $data['society_address'];

		$particular_data_heading = array();
		$particular_data_heading[0] = "BY";
		// $particular_data_heading[1]="NUMBER";
		$particular_data_heading[1] = "PARTICULARS";
		$particular_data_heading[2] = "UNIT NO.";

		$particular_data_print = array();
		$particular_data_print[0] = "CHEQUE / UPI / NEFT / RTGS";
		$particular_data_print[1] = $data['narration'];
		$particular_data_print[2] = "-";
		// $particular_data_print[3]="-";

		$data['amount_words'] = getIndianCurrency($data['amount']);
		// $particular_data_print[1]=$data['society_name_number']." ".$data['society_name_date'];
		// $particular_data_print[2]=$data['society_address'];

		if (isset($header_data_print))
		//print_r($getdata);
		{ ?>
    <table style="width: 90%;margin-left:5%;margin-top:25px;" CELLSPACING="0">
        <tr>
            <td style="width:72%;border:1px solid black;padding-bottom:10px;">
                <?php
						for ($i = 0; $i < count($header_data_print); $i++) {
							if ($i == 0) { ?>
                <div style="text-align:center;font-size:25px;font-weight:bold;padding-bottom: 10px;">
                    <?php echo $header_data_print[$i]; ?></div>
                <?php
							} else { ?>
                <div class="font-17" style="text-align:center;padding-bottom: 10px;">
                    <?php echo $header_data_print[$i]; ?></div>
                <?php
							}
						}
						?>

            </td>
            <td style="width:28%;border:1px solid black;border-left:0;padding-bottom:10px;">
                <div
                    style="text-align:center;font-size:23px;font-weight:bold;padding-bottom: 10px;background-color:#f58220;color: white;vertical-align: top;">
                    PAYMENT</div>
                <!--<p class="font-17" style="text-align:center;"><br><br><br><br></p>-->
                <?php
						/*for($i=0;$i<2;$i++)
				{
					if(isset($receipt_data_heading[$i]) && isset($receipt_data_print[$i]))
					{*/
						?>
                <p class="font-17" style="text-align:left;padding-left: 5px;">
                    <?php echo "<b>VOUCHER No.:</b>  " . $data['no'] ?></p>
                <p class="font-17" style="text-align:left;padding-left: 5px;">
                    <?php echo "<b>DATE:</b>  " . $data['date'] ?></p>
                <?php
						/*}
				}*/
						?>



            </td>
        </tr>

        <tr>
            <td style="width:72%;border:1px solid black;padding-bottom:10px;border-bottom: 0;">
                <?php
						// for($i=0;$i<count($user_data_heading);$i++)
						// {
						if (isset($data['by_ledger_name'])) {
						?>
                <div class="font-15"
                    style="text-align:left;padding-bottom: 10px;padding-left:15px;<?php if ($i == 0) { ?>padding-top: 15px;<?php } ?>">
                    <?php echo "<b>PAID TO SHRI / SMT / MS:</b> "; ?>
                    <?php if (strlen($data['by_ledger_name']) > 64) { ?>
                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px;">
                        <?php echo substr($data['by_ledger_name'], 0, 63); ?>
                    </div>
                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px;">
                        <?php echo substr($data['by_ledger_name'], 63); ?>
                    </div>
                    <?php } else { ?>
                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px;">
                        <?php echo $data['by_ledger_name']; ?>
                    </div>
                    <!--<div style="width:90%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px;">&nbsp;
								</div>-->
                    <?php } ?>
                </div>

                <div class="font-15"
                    style="text-align:left;padding-bottom: 10px;padding-left:15px;<?php if ($i == 0) { ?>padding-top: 15px;<?php } ?>">
                    <?php echo "<b>THE SUM OF RUPEES</b> "; ?>
                    <?php if (strlen($data['amount_words']) > 64) { ?>
                    <div
                        style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px; text-transform: capitalize;">
                        <?php echo substr($data['amount_words'], 0, 63); ?>
                    </div>
                    <div
                        style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px; text-transform: capitalize;">
                        <?php echo substr($data['amount_words'], 63); ?>
                    </div>
                    <?php } else { ?>
                    <div
                        style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px; text-transform: capitalize;">
                        <?php echo $data['amount_words']; ?>
                    </div>

                    <?php } ?>
                </div>
                <?php
						}
						// }	
						?>

            </td>
            <td style="width:28%;border:1px solid black;border-left:0;padding-bottom:10px;">

                <!--<p class="font-17" style="text-align:center;"><br><br><br><br></p>-->
                <?php
						// for($i=2;$i<3;$i++)
						// {
						if (isset($data['amount'])) {
						?>
                <div class="font-17" style="text-align:left;padding-left: 5px;"><b style="font-size: 27px;"> ₹ </b>
                    <div style='width:80%;padding:5px;margin-left:7px;border:1px solid black;border-radius: 8px;'>
                        <?php echo "  " . $data['amount'];  ?></div>
                </div>
                <?php
						}
						// }
						?>



            </td>
        </tr>
        <tr>
            <td style="width:72%;border:1px solid black;padding-bottom:10px;border-top: 0;">
                <table class="font-15" style="text-align:left;padding-bottom: 10px;padding-left:15px;width:100%;"
                    cellspacing="0">
                    <tr>
                        <?php
								for ($i = 0; $i < count($particular_data_heading) - 1; $i++) {
									if (isset($particular_data_heading[$i])) { ?>
                        <th style="<?php if ($i != count($particular_data_heading) - 2) {
														echo "width :25%;";
													} else {
														echo "width :43%;";
													} ?>border: 1px solid black;padding: 7px;">
                            <?php echo $particular_data_heading[$i]; ?>
                        </th>
                        <?php
									}
								}
								?>
                    </tr>
                    <tr>
                        <?php
								for ($i = 0; $i < count($particular_data_print) - 1; $i++) {
									if (isset($particular_data_print[$i]) && isset($particular_data_print[$i])) { ?>
                        <td
                            style="<?php if ($i == 0) {
														echo "font-weight: bold;width :30%;";
													} else {
														echo "width :30%;";
													} /*if($i!=count($particular_data_print)-2){ echo "width :25%;"; }else{ echo "width :40%;"; }*/ ?>border: 1px solid black;padding: 7px;">
                            <?php echo $particular_data_print[$i]; ?>
                        </td>
                        <?php
									}
								}
								?>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="2" style="width :65%;">&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td style="width:28%;border:1px solid black;border-left:0;padding-bottom:10px;">
                <div class="font-17" style="text-align:center;padding-bottom: 15px;">
                </div>
                <div class="font-14" style="text-align:left;padding-left: 5px;">
                    <div style='width:60%;height:125px;margin-left:17%;border:1px dashed black;border-radius: 8px;'>
                    </div>
                </div>
                <div class="font-14" style="text-align:center;padding-left: 5px;font-weight: bold;padding-top: 15px;">
                    SIGNATORY</div>


            </td>
        </tr>
    </table>
    <br>
    <?php
		} ?>








</page>
<?php

	$pdf_content = ob_get_clean();
	$dt = date('Y-m-d');



	if (isset($data['response_type']) && $data['response_type'] == "mail") {

		$html2pdf = new Html2Pdf('P', $page_size, 'en', true, 'UTF-8', array(0, 0, 0, 0));
		$html2pdf->writeHTML($pdf_content);
		return $html2pdf->output();
	} elseif (isset($data['response_type']) && $data['response_type'] == "view") {
		if (isset($data['society_name']) && isset($data['date'])) {
			$name = str_replace(" ", "_", $data['society_name']) . "_DT-" . date('d.m.Y', strtotime($data['date'])) . ".pdf";
		} else {
			$name = "GreenwoodSociety-Payment.pdf";
		}


		$html2pdf = new Html2Pdf('P', $page_size, 'en', true, 'UTF-8', array(0, 0, 0, 0));
		$html2pdf->writeHTML($pdf_content);
		$name = str_replace('/', '-', $name);
		$html2pdf->output(getcwd() . "/mail_pdfs/$name", 'F');
		return $name;
	} else {
		if (isset($receipt_data_print[0]) && isset($receipt_data_print[1])) {
			$name = "Greenwood_Receipt_" . $receipt_data_print[0] . "_DT-" . date('d.m.Y', strtotime($receipt_data_print[1])) . ".pdf";
		} else {
			$name = "GreenwoodSociety-Payment.pdf";
		}
		$name = str_replace('/', '-', $name);


		$html2pdf = new Html2Pdf('P', $page_size, 'en', true, 'UTF-8', array(0, 0, 0, 0));
		$html2pdf->writeHTML($pdf_content);

		if (isset($getdata[0]) && $getdata[0] == 'all') {
			$html2pdf->output(getcwd() . "/mail_pdfs/$name", 'F');
			//return getcwd()."/assets/pdfs/$name";
			return $name;
		} else {
			return $html2pdf->output($name, 'D');
		}
	}
}


?>