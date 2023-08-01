<?php 
    ini_set('max_execution_time', '600');
    // require_once dirname(__FILE__).'/vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require_once dirname(__FILE__).'/vendor/phpmailer/phpmailer/src/Exception.php';
    require_once dirname(__FILE__).'/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require_once dirname(__FILE__).'/vendor/phpmailer/phpmailer/src/SMTP.php';
    require_once dirname(__FILE__)."/vendor/autoload.php";

    header("Content-Type:application/json");
    $data2 = json_decode(file_get_contents('php://input'), true);

    if(!empty($data2))
    {
        $data = json_decode($data2[0], true);

        // $msg = "
        // <table width='850' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#eff2f3' style='border-top:1px solid #d5d8d9;border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000'>
        //     <tbody>
        //         <tr>
        //             <td style='padding:13px 12px 0px 12px'>
        //                 <table width='100%' border='0' cellspacing='0' cellpadding='0' style='background: #eff2f3;'>
        //                     <tbody>
        //                         <tr>
        //                             <td style='padding:7px'>
        //                                 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
        //                                     <tbody></tbody>
        //                                 </table>
        //                             </td>
        //                         </tr>
        //                     </tbody>
        //                 </table>
        //             </td>
        //         </tr>
        //     </tbody>
        // </table>
        // <table width='850' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#eff2f3' style='border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000'>
        //     <tbody>
        //         <tr>
        //             <td style='padding:0px 12px 0px 12px'>
        //                 <table width='70%' border='0' cellspacing='0' cellpadding='0' bgcolor='#FFFFFF' style='border-right:1px solid #d5d8d9;border-top: 1px solid #D5D8D9;border-left:1px solid #d5d8d9;margin-left:15%;'>
        //                     <tbody>
        //                         <tr>
        //                             <td style='padding:0px 14px'>
        //                                 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
        //                                     <tbody>
        //                                         <tr>
        //                                             <td>
        //                                                 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
        //                                                     <tbody>
        //                                                         <tr>
        //                                                             <td valign='top'>
        //                                                                 <table width='100%' border='0' cellspacing='0' cellpadding='0'>
        //                                                                     <tbody>
        //                                                                         <tr>
        //                                                                             <td colspan='3' style='padding-top:5px;color:black;'>
        //                                                                                 <center><h3>INVOICE</h3></center>
        //                                                                             </td>                
        //                                                                         </tr><br>
        //                                                                         <tr>
        //                                                                             <td colspan='3' style='padding-top:5px;color:black;'>
        //                                                                                 <center><p style='font-weight:600;font-size:16px;'>".$data['society_name']."</p></center>
        //                                                                             </td>                
        //                                                                         </tr>
        //                                                                         <tr>
        //                                                                             <td colspan='3' style='padding-top:5px;color:black;font-size: 13px;'>
        //                                                                                 <center><p>".$data['society_name_number'].' dt.'.date('d/m/Y', strtotime($data['society_name_date']))."</p></center>
        //                                                                             </td>                
        //                                                                         </tr>
        //                                                                         <tr>
        //                                                                             <td colspan='3' style='padding-top:5px;color:black;font-size: 13px;'>
        //                                                                                 <center><p>".$data['society_address']."</p></center>
        //                                                                             </td>                
        //                                                                         </tr>
        //                                                                         <tr style='padding-bottom: 25px;'><td colspan='3'>&nbsp;</td></tr>
        //                                                                         <tr>
        //                                                                             <td style='padding-top:5px;color:black; font-size:13px;width:30%;'>
        //                                                                                 <p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'>Unit No : </b>".$data['unit_no']."</p>
        //                                                                             </td>
        //                                                                             <td style='padding-top:5px;color:black; font-size:13px;width:33%;'>
        //                                                                                 <p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'>Area : </b>".(!empty($data['area_sq_mtr']) ? $data['area_sq_mtr'].' Sq.Mtr' : $data['area_sq_ft'].' Sq.Ft' )."</p>
        //                                                                             </td>
        //                                                                             <td style='padding-top:5px;color:black; font-size:13px;width:37%;'>
        //                                                                                 <p style='padding-left: 10px;padding-right: 15px'><b style='padding-right: 15px;'>Bill No. : </b>".$data['bill_no']."</p>
        //                                                                             </td>
        //                                                                         </tr>
        //                                                                         <tr>
        //                                                                             <td colspan='2' style='padding-top:5px;color:black; font-size:13px;'>
        //                                                                                 <p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'>Name : </b>".$data['name']."</p>
        //                                                                             </td>
        //                                                                             <td  style='padding-top:5px;color:black; font-size:13px;'>
        //                                                                                 <p style='padding-left: 10px;padding-right: 15px'><b style='padding-right: 15px;'>Bill Date : </b>".date('d-m-Y', strtotime($data['bill_date']))."</p>
        //                                                                             </td>
        //                                                                         </tr>
        //                                                                         <tr>
        //                                                                             <td colspan='2' style='padding-top:5px;color:black; font-size:13px;'>
        //                                                                                 <p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'>Bill Period : </b>".$data['bill_period']."</p>
        //                                                                             </td>
        //                                                                             <td  style='padding-top:5px;color:black; font-size:13px;'>
        //                                                                                 <p style='padding-left: 10px;padding-right: 15px'><b style='padding-right: 15px;'>Due Date : </b>".date('d-m-Y', strtotime($data['due_date']))."</p>
        //                                                                             </td>
        //                                                                         </tr>
        //                                                                         <tr style='padding-bottom: 25px;'>
        //                                                                             <td colspan='3'>&nbsp;</td>
        //                                                                         </tr>
        //                                                                         <tr style='padding-bottom: 25px;'>
        //                                                                             <th colspan='2' width='75%' style='border:1px solid black;padding: 8px;'>PARTICULARS OF CHARGES</th>
        //                                                                             <th width='25%' style='border:1px solid black;border-left: 0;padding: 8px;'>Amount</th>
        //                                                                         </tr>";
        //                                                                         if(!empty($data['to_ledger']))
        //                                                                         {
        //                                                                             foreach($data['to_ledger'] as $k => $toledger)
        //                                                                             {
        //                                                                     $msg .="
        //                                                                                     <tr style='padding-bottom: 25px;font-size: 13px;'>
        //                                                                                         <td colspan='2' style='border:1px solid black;padding: 5px;'>".$toledger['name']."</td>
        //                                                                                         <td style='border:1px solid black;border-left: 0;padding: 5px;text-align: right;'>Rs. ".number_format($toledger['amount'], 2)."</td>
        //                                                                                     </tr>";
        //                                                                             }
        //                                                                         }
        //                                                                     $msg .= "
        //                                                                         <tr style='padding-bottom: 25px;font-size: 13px;'>
        //                                                                             <td colspan='2' style='border:1px solid black;padding: 5px;text-align: right;'>Total</td>
        //                                                                             <td style='border:1px solid black;border-left: 0;padding: 5px;text-align: right;'>Rs.".number_format($data['total_amount'], 2)."</td>
        //                                                                         </tr>
        //                                                                         <tr style='padding-bottom: 25px;font-size: 13px;'>
        //                                                                             <td colspan='2' style='border:1px solid black;padding: 5px;text-align: right;'>Arrears</td>
        //                                                                             <td style='border:1px solid black;border-left: 0;padding: 5px;text-align: right;'>Rs.".number_format($data['arrears'], 2)."</td>
        //                                                                         </tr>
        //                                                                         <tr style='padding-bottom: 25px;font-size: 13px;'>
        //                                                                             <td colspan='2' style='border:1px solid black;padding: 5px;text-align: right;'>Interest Amount</td>
        //                                                                             <td style='border:1px solid black;border-left: 0;padding: 5px;text-align: right;'>Rs.".number_format($data['interest_amount'], 2)."</td>
        //                                                                         </tr>
        //                                                                         <tr style='padding-bottom: 25px;font-size: 13px;'>
        //                                                                             <td colspan='2' style='border:1px solid black;padding: 5px;text-align: right;'>Total Due Amount Payable</td>
        //                                                                             <td style='border:1px solid black;border-left: 0;padding: 5px;text-align: right;'>Rs.".number_format($data['total_due_amount_payable'], 2)."</td>
        //                                                                         </tr>
        //                                                                         <tr>
        //                                                                             <td colspan='3' style='border:1px solid black;padding: 5px;'>
        //                                                                             <div style='font-size: 18px; font-weight: 700;'>Notes & E.&O.E.</div>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>1. Dear Member due to the COVID-19 Pandemic handover process is still pending from the previous committee. Hence the accounting data is not available for calculation of exact arrears and dues.</p>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>2. Full and final arrear /past due will reflect in the upcoming quarter once the handover process is completed. 3. Those who have shared the old transaction details there ledger preparation are in progress.</p>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>4. The amount mentioned in the current invoice is only for the quarter months JAN-MAR-21 with present penalty and parking charges.</p>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>5. In case of any discrepancies of this bill, please inform the society office within 7 days.</p>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>6. In case cheque is returned for any reason, bank charges would be levied on the next bill.</p>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>7. If the amount is not paid within the due date, interest will be charged @ 18% P. A.</p>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>8. Please pay by CTS2010 cheque or online in favour of Greenwood Estate Ph-I CHSL.</p>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>9. Account Name : Greenwood Estate Phase-1 CHSL, Bank: Saraswat Co-Op Bank Ltd, Branch : CBD Belapur, Account No. 397200100001525, IFS Code - SRCB0000397, Account Type : Savings Account,</p>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>10. It mandatory to provide all the types of payment made to submit the following details to official society mail id : invoice.gweph1@gmail.com , suspenseamount.greenwoodestate1@gmail.com only, with details such as a)Transaction No., b) Transaction Date, c) Amount, d) Flat No. & Wing,.</p>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>11. Arrears are added in the bill. If you have made the payment kindly help us with the details from the starting of the maintenance paid to till date so that we can proceed further to cancel the arrears.</p>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>12. Parking charges are included as per parking space occupied by tenants/owners owned vehicles. This cost will be incurred in maintenance Amount.</p>
        //                                                                                 <p style='font-size: 12px; font-weight: 600;'>13. If you have already made the payment for this invoice request you ignore the same.</p>
        //                                                                             </td>
        //                                                                         </tr>
        //                                                                         <tr>
        //                                                                             <td colspan='3' style='padding-top:5px;color:black; font-size:13px; text-align: right;'>
        //                                                                                 <p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'>FOR ".$data['society_name']."</b></p>
        //                                                                             </td>
        //                                                                         </tr>
        //                                                                         <tr style='padding-bottom: 30px;'>
        //                                                                             <td colspan='3'>&nbsp;</td>
        //                                                                         </tr>
        //                                                                         <tr>
        //                                                                             <td colspan='3' style='padding-top:5px;color:black; font-size:13px; text-align: right;'>
        //                                                                                 <p style='padding-left: 25px;padding-right: 15px'>Authorized Signatory</p>
        //                                                                             </td>
        //                                                                         </tr>
        //                                                                     </tbody>
        //                                                                 </table>
        //                                                             </td>
        //                                                         </tr>
        //                                                     </tbody>
        //                                                 </table>
        //                                             </td>
        //                                         </tr>
        //                                     </tbody>
        //                                 </table>
        //                             </td>
        //                         </tr>
        //                     </tbody>
        //                 </table>
        //             </td>
        //         </tr>
        //     </tbody>
        // </table>
        // <table width='850' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#eff2f3' style='border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;border-bottom:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000'>
        //     <tbody>
        //         <tr>
        //             <td style='padding:0px 12px 0px 12px'>
        //                 <table width='70%' style='margin-left:15%;' border='0' cellspacing='0' cellpadding='0' bgcolor='#FFFFFF'>
        //                     <tbody>
        //                         <tr>
        //                             <td style='padding:0px 14px 0px;border-bottom:1px solid #d5d8d9;border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9'>&nbsp;</td>
        //                         </tr>
        //                         <tr>
        //                             <td bgcolor='#eff2f3'>
        //                                 <br>
        //                                 <br>
        //                             </td>
        //                         </tr>
        //                     </tbody>
        //                 </table>
        //             </td>
        //         </tr>
        //     </tbody>
        // </table>
        // ";

        $area = '';
        if(!empty($data['area_sq_mtr']))
        {
            $area = $data['area_sq_mtr'].' Sq.Mtr';
        }
        else{
            $area = $data['area_sq_ft'].' Sq.Ft';
        }
        
        $address = $data['society_name_number'].' dt.'.date('d/m/Y', strtotime($data['society_name_date']));
        $biil_date = date('d-m-Y', strtotime($data['bill_date']));
        $due_date = date('d-m-Y', strtotime($data['due_date']));

        $header_data_print = [$data['society_name'], $address, $data['society_address']];
        $user_data_heading = ['Unit No', 'Area', 'Bill No', 'Name', 'Bill Date', 'Bill Period', 'Due Date'];
        $user_data_print = [$data['unit_no'], $area, $data['bill_no'], $data['name'], $biil_date, $data['bill_period'], $due_date];

        $particular_data_print = [];
        $particular_data_heading = [];

        if(!empty($data['to_ledger']))
        {
            foreach($data['to_ledger'] as $k => $toledger)
            {
                $particular_data_print[$k] = $toledger['amount'];
                $particular_data_heading[$k] = $toledger['name'];
            }
        }
        $arrear_data_heading = ['Total', 'Arrears', 'Interest Amount', 'Total Due Amount Payable'];
        $arrear_data_print = [$data['total_amount'], $data['arrears'], $data['interest_amount'], $data['total_due_amount_payable']];
        $mailmsg = "
			<table width='850' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#eff2f3' style='border-top:1px solid #d5d8d9;border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000'>
				<tbody>
                    <tr>
						<td style='padding:13px 12px 0px 12px'>
						    <table width='100%' border='0' cellspacing='0' cellpadding='0' style='background: #eff2f3;'>
							  <tbody><tr>
								<td style='padding:7px'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
								  <tbody>
									</tbody></table></td>
								  </tr>
								</tbody></table></td>
							  </tr>
							</tbody></table>
							<table width='850' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#eff2f3' style='border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000'>
							<tbody><tr>
							<td style='padding:0px 12px 0px 12px'><table width='70%' border='0' cellspacing='0' cellpadding='0' bgcolor='#FFFFFF' style='border-right:1px solid #d5d8d9;border-top: 1px solid #D5D8D9;border-left:1px solid #d5d8d9;margin-left:15%;'>
							  <tbody><tr>
								<td style='padding:0px 14px'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
								  <tbody><tr>
									<td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
									  <tbody><tr>
										<td valign='top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
										  <tbody>
										  <tr>
										  <td colspan='3' style='padding-top:5px;color:black;'>
										 	 <center><h3>INVOICE</h3></center>
										  </td>                
										 </tr><br>
										 
										 ";
										for($i=0;$i<count($header_data_print);$i++)
										{
											if($i==0)
											{	
												$mailmsg.="
											
												<tr>
												  	<td colspan='3' style='padding-top:5px;color:black;'>
												 		<center><p style='font-weight:600;font-size:16px;'>". $header_data_print[$i]."</p></center>
												  	</td>                
												</tr>";
										
											}else
											{
												$mailmsg.="
												
												<tr>
												  	<td colspan='3' style='padding-top:5px;color:black;font-size: 13px;'>
												 		<center><p>". $header_data_print[$i]."</p></center>
												  	</td>                
												</tr>";	
													
											}
										 }
										 $mailmsg.=" 
										 <tr style='padding-bottom: 25px;'><td colspan='3'>&nbsp;</td></tr>";
										 
										 if(isset($user_data_print) && count($user_data_print)>0)
										 {
										 	if(count($user_data_print)%2!="0")
										 	{
										 		$udc=3;
										 	$mailmsg.="
										 		<tr>";
										 		if(isset($user_data_heading[0]) && isset($user_data_print[0]))
										 		{
										 			$mailmsg.="
												  <td style='padding-top:5px;color:black; font-size:13px;width:30%;'>
												 	 <p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'>". $user_data_heading[0].":</b>". $user_data_print[0]."</p>
												  </td>";
												}
												if(isset($user_data_heading[1]) && isset($user_data_print[1]))
												{
													$mailmsg.="
												  <td style='padding-top:5px;color:black; font-size:13px;width:33%;'>
												 	 <p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'>". $user_data_heading[1].":</b>".$user_data_print[1]."</p>
												  </td>";
												}
												if(isset($user_data_heading[0]) && isset($user_data_print[0]))
												{
													$mailmsg.="
												  <td style='padding-top:5px;color:black; font-size:13px;width:37%;'>
												 	 <p style='padding-left: 10px;padding-right: 15px'><b style='padding-right: 15px;'>". $user_data_heading[2].":</b>". $user_data_print[2]."</p>
												  </td>                
												 </tr>";		
										 		}
										 	}else
										 	{
										 		$udc=0;
										 	}
										 	for ($i=$udc; $i < count($user_data_print); ) 
										 	{ 
										 		$mailmsg.="
										 		<tr>";
										 		
										 		if(isset($user_data_heading[$i]) && isset($user_data_print[$i])&& isset($user_data_heading[($i+1)]) && isset($user_data_print[($i+1)]))
										 		{
										 			if(isset($user_data_heading[$i]) && isset($user_data_print[$i]))
													{
														$mailmsg.="
														<td colspan='2' style='padding-top:5px;color:black; font-size:13px;'>
														 	<p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'>". $user_data_heading[$i].":</b>". $user_data_print[$i]."</p>
														</td>	
														";
													}
													if(isset($user_data_heading[($i+1)]) && isset($user_data_print[($i+1)]))
													{
														$mailmsg.="
														<td  style='padding-top:5px;color:black; font-size:13px;'>
														 	<p style='padding-left: 10px;padding-right: 15px'><b style='padding-right: 15px;'>". $user_data_heading[($i+1)].":</b>". $user_data_print[($i+1)]."</p>
														</td>	
														";
													}
										 		}else
										 		{
										 			if(isset($user_data_heading[$i]) && isset($user_data_print[$i]))
										 			{
										 				$mailmsg.="
														<td colspan='3' style='padding-top:5px;color:black; font-size:13px;'>
														 	<p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'>". $user_data_heading[$i].":</b>". $user_data_print[$i]."</p>
														</td>	
														";
										 			}
										 		}
										 	$mailmsg.="</tr>";
										 		
										 		$i+=2;
										 	}
										 }

										$mailmsg.=" 
										 <tr style='padding-bottom: 25px;'><td colspan='3'>&nbsp;</td></tr>
										 <tr style='padding-bottom: 25px;'><th colspan='2' width='75%' style='border:1px solid black;padding: 8px;'>PARTICULARS OF CHARGES</th><th width='25%' style='border:1px solid black;border-left: 0;padding: 8px;'>Amount</th></tr>
										"; 
											if(isset($particular_data_print) && isset($particular_data_heading))
											{
												for($i=0;$i<count($particular_data_print);$i++)
												{
													if(isset($particular_data_heading[$i]) && isset($particular_data_print[$i]))
													{
													$mailmsg.=" 	
													<tr style='padding-bottom: 25px;font-size: 13px;'><td colspan='2' style='border:1px solid black;padding: 5px;'>".$particular_data_heading[$i]."</td><td style='border:1px solid black;border-left: 0;padding: 5px;text-align: right;'>Rs.". number_format($particular_data_print[$i],2,".",",")."</td></tr>	
													";
													}
												}
												
											}
												$cnt_arr=count($arrear_data_heading);
												for($i=0;$i<$cnt_arr;$i++)
												{
														if(isset($arrear_data_heading[$i]) && isset($arrear_data_print[$i]))
														{
														$mailmsg.=" 
														<tr style='padding-bottom: 25px;font-size: 13px;'><td colspan='2' style='border:1px solid black;padding: 5px;text-align: right;'>".$arrear_data_heading[$i]."</td><td style='border:1px solid black;border-left: 0;padding: 5px;text-align: right;'>Rs.".number_format($arrear_data_print[$i],2,".",",")."</td></tr>";	
														}
												} 
											 $mailmsg.="
                                             <tr>
                                                <td colspan='3' style='border:1px solid black;padding: 5px;'>
                                                <div style='font-size: 18px; font-weight: 700;'>Notes & E.&O.E.</div>
                                                    <p style='font-size: 12px; font-weight: 600;'>1. Dear Member due to the COVID-19 Pandemic handover process is still pending from the previous committee. Hence the accounting data is not available for calculation of exact arrears and dues.</p>
                                                    <p style='font-size: 12px; font-weight: 600;'>2. Full and final arrear /past due will reflect in the upcoming quarter once the handover process is completed. 3. Those who have shared the old transaction details there ledger preparation are in progress.</p>
                                                    <p style='font-size: 12px; font-weight: 600;'>4. The amount mentioned in the current invoice is only for the quarter months JAN-MAR-21 with present penalty and parking charges.</p>
                                                    <p style='font-size: 12px; font-weight: 600;'>5. In case of any discrepancies of this bill, please inform the society office within 7 days.</p>
                                                    <p style='font-size: 12px; font-weight: 600;'>6. In case cheque is returned for any reason, bank charges would be levied on the next bill.</p>
                                                    <p style='font-size: 12px; font-weight: 600;'>7. If the amount is not paid within the due date, interest will be charged @ 18% P. A.</p>
                                                    <p style='font-size: 12px; font-weight: 600;'>8. Please pay by CTS2010 cheque or online in favour of Greenwood Estate Ph-I CHSL.</p>
                                                    <p style='font-size: 12px; font-weight: 600;'>9. Account Name : Greenwood Estate Phase-1 CHSL, Bank: Saraswat Co-Op Bank Ltd, Branch : CBD Belapur, Account No. 397200100001525, IFS Code - SRCB0000397, Account Type : Savings Account,</p>
                                                    <p style='font-size: 12px; font-weight: 600;'>10. It mandatory to provide all the types of payment made to submit the following details to official society mail id : invoice.gweph1@gmail.com , suspenseamount.greenwoodestate1@gmail.com only, with details such as a)Transaction No., b) Transaction Date, c) Amount, d) Flat No. & Wing,.</p>
                                                    <p style='font-size: 12px; font-weight: 600;'>11. Arrears are added in the bill. If you have made the payment kindly help us with the details from the starting of the maintenance paid to till date so that we can proceed further to cancel the arrears.</p>
                                                    <p style='font-size: 12px; font-weight: 600;'>12. Parking charges are included as per parking space occupied by tenants/owners owned vehicles. This cost will be incurred in maintenance Amount.</p>
                                                    <p style='font-size: 12px; font-weight: 600;'>13. If you have already made the payment for this invoice request you ignore the same.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan='3' style='padding-top:5px;color:black; font-size:13px; text-align: right;'>
                                                    <p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'>FOR ".$data['society_name']."</b></p>
                                                </td>
                                            </tr>
                                            <tr style='padding-bottom: 30px;'>
                                                <td colspan='3'>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan='3' style='padding-top:5px;color:black; font-size:13px; text-align: right;'>
                                                    <p style='padding-left: 25px;padding-right: 15px'>Authorized Signatory</p>
                                                </td>
                                            </tr>
										</tbody></table></td>
									  </tr>
									</tbody></table></td>
								  </tr>
								</tbody></table></td>
							  </tr>
							</tbody></table></td>
						  </tr>
						</tbody></table>
						<table width='850' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#eff2f3' style='border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;border-bottom:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000'>
						  <tbody><tr>
							<td style='padding:0px 12px 0px 12px'><table width='70%' style='margin-left:15%;' border='0' cellspacing='0' cellpadding='0' bgcolor='#FFFFFF'>
							  <tbody><tr>
								<td style='padding:0px 14px 0px;border-bottom:1px solid #d5d8d9;border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9'>&nbsp;</td>
							  </tr>
							  <tr>
								<td bgcolor='#eff2f3'>
									<br>
									<br>
									
								</td>
							  </tr>
							</tbody></table></td>
						  </tr>
						</tbody></table>
						<div class='yj6qo'></div>
						<div class='adL'>
						</div>
					</div>";
        $mail = new PHPMailer(true);

        $mail->isSMTP();      
        $mail->SMTPDebug = 3;                                
        // $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'mr.anilbambhaniya@gmail.com';                 // SMTP username
        $mail->Password = 'Anil@1436';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        // $mail->Port = 587;                                    // TCP port to connect to
        $mail->Port = 587;   

        $mail->From = $data['from'];
        // $mail->FromName = 'Test';
        $mail->addAddress($data['to']);     // Optional name
        $mail->isHTML(true);    
                                    // Set email format to HTML
        $mail->Subject = 'Invoice';
        $mail->Body    = $mailmsg;

        if(!$mail->send()) {
            $headers = "Content-Type: text/html; charset=UTF-8\r\n";
            mail ($data['to'],'invoice',$masg, $headers);
        }
        echo 'success';
    }
?>