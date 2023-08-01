
<?php 
    $area = '';
    if(!empty($data['area_sq_mtr']))
    {
        $area = $data['area_sq_mtr'].' Sq.Mtr';
    }
    else{
        $area = $data['area_sq_ft'].' Sq.Ft';
    }
    
    $address = $data['society_name_number'].' dt.'.date('m-d-Y', strtotime($data['society_name_date']));
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
?>
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
										<?php for($i=0;$i<count($header_data_print);$i++)
										{
											if($i==0)
											{	?>
												<tr>
												  	<td colspan='3' style='padding-top:5px;color:black;'>
												 		<center><p style='font-weight:600;font-size:16px;'><?= $header_data_print[$i] ?></p></center>
												  	</td>                
												</tr>
										
									    <?php }else
											{ ?>
												<tr>
												  	<td colspan='3' style='padding-top:5px;color:black;font-size: 13px;'>
												 		<center><p><?= $header_data_print[$i] ?></p></center>
												  	</td>                
												</tr>
													
									    <?php }
										 } ?>
										 <tr style='padding-bottom: 25px;'><td colspan='3'>&nbsp;</td></tr>
										 <?php 
										 if(isset($user_data_print) && count($user_data_print)>0)
										 {
										 	if(count($user_data_print)%2!="0")
										 	{
										 		$udc=3; ?>
										 		<tr>
                                                <?php 
										 		if(isset($user_data_heading[0]) && isset($user_data_print[0]))
										 		{ 
                                                     ?>
												  <td style='padding-top:5px;color:black; font-size:13px;width:30%;'>
												 	 <p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'><?= $user_data_heading[0]?> :</b><?= $user_data_print[0] ?></p>
												  </td>
                                                <?php 
												}
												if(isset($user_data_heading[1]) && isset($user_data_print[1]))
												{ 
                                                    ?>
												  <td style='padding-top:5px;color:black; font-size:13px;width:33%;'>
												 	 <p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'><?= $user_data_heading[1] ?>:</b><?= $user_data_print[1] ?></p>
												  </td>
                                                <?php 
												}
												if(isset($user_data_heading[0]) && isset($user_data_print[0]))
												{
                                                    ?>
												  <td style='padding-top:5px;color:black; font-size:13px;width:37%;'>
												 	 <p style='padding-left: 10px;padding-right: 15px'><b style='padding-right: 15px;'><?= $user_data_heading[2]?>:</b><?= $user_data_print[2]?></p>
												  </td>                
												 </tr>
                                                 <?php		
										 		}
										 	}else
										 	{
										 		$udc=0;
										 	}
										 	for ($i=$udc; $i < count($user_data_print); ) 
										 	{ 
                                                 ?>
										 		<tr>
										 		<?php
										 		if(isset($user_data_heading[$i]) && isset($user_data_print[$i])&& isset($user_data_heading[($i+1)]) && isset($user_data_print[($i+1)]))
										 		{
										 			if(isset($user_data_heading[$i]) && isset($user_data_print[$i]))
													{
                                                        ?>
														<td colspan='2' style='padding-top:5px;color:black; font-size:13px;'>
														 	<p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'><?= $user_data_heading[$i] ?>:</b><?= $user_data_print[$i]?></p>
														</td>	
													<?php
													}
													if(isset($user_data_heading[($i+1)]) && isset($user_data_print[($i+1)]))
													{
                                                        ?>
														<td  style='padding-top:5px;color:black; font-size:13px;'>
														 	<p style='padding-left: 10px;padding-right: 15px'><b style='padding-right: 15px;'><?= $user_data_heading[($i+1)] ?>:</b><?= $user_data_print[($i+1)] ?></p>
														</td>	
													<?php
													}
										 		}else
										 		{
										 			if(isset($user_data_heading[$i]) && isset($user_data_print[$i]))
										 			{
                                                         ?>
														<td colspan='3' style='padding-top:5px;color:black; font-size:13px;'>
														 	<p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'><?= $user_data_heading[$i] ?>:</b><?= $user_data_print[$i] ?></p>
														</td>	
												<?php
										 			}
										 		}
                                                 ?>
                                                 </tr>
										 		<?php
										 		$i+=2;
										 	}
										 }
                                         ?>
										 <tr style='padding-bottom: 25px;'><td colspan='3'>&nbsp;</td></tr>
										 <tr style='padding-bottom: 25px;'><th colspan='2' width='75%' style='border:1px solid black;padding: 8px;'>PARTICULARS OF CHARGES</th><th width='25%' style='border:1px solid black;border-left: 0;padding: 8px;'>Amount</th></tr>
                                         <?php
											if(isset($particular_data_print) && isset($particular_data_heading))
											{
												for($i=0;$i<count($particular_data_print);$i++)
												{
													if(isset($particular_data_heading[$i]) && isset($particular_data_print[$i]))
													{
                                                        ?>	
													<tr style='padding-bottom: 25px;font-size: 13px;'><td colspan='2' style='border:1px solid black;padding: 5px;'><?= $particular_data_heading[$i] ?></td><td style='border:1px solid black;border-left: 0;padding: 5px;text-align: right;'>Rs.<?= number_format($particular_data_print[$i],2,".",",") ?></td></tr>	
													<?php
													}
												}
												
											}
												$cnt_arr=count($arrear_data_heading);
												for($i=0;$i<$cnt_arr;$i++)
												{
														if(isset($arrear_data_heading[$i]) && isset($arrear_data_print[$i]))
														{
                                                            ?>
														<tr style='padding-bottom: 25px;font-size: 13px;'><td colspan='2' style='border:1px solid black;padding: 5px;text-align: right;'><?= $arrear_data_heading[$i] ?></td><td style='border:1px solid black;border-left: 0;padding: 5px;text-align: right;'>Rs.<?= number_format($arrear_data_print[$i],2,".",",") ?></td></tr>
                                                        <?php	
														}
												} 
                                                ?>
                                             <tr>
                                                <td colspan='3' style='border:1px solid black;padding: 5px;'>
                                                <div style='font-size: 18px; font-weight: 700;'>Notes & E.&O.E.</div>
                                                    <?php echo $data['billing_notes']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan='3' style='padding-top:5px;color:black; font-size:13px; text-align: right;'>
                                                    <p style='padding-left: 25px;padding-right: 15px'><b style='padding-right: 15px;'>FOR <?= $data['society_name'] ?></b></p>
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
								  <tr>
									<td style="padding-top: 15px">https://jtechnoholic.com/MySocietyAssistant/search-invoice</td>
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
					</div>