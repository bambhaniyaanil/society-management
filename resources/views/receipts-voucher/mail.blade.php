<table width='850' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#eff2f3' style='border-top:1px solid #d5d8d9;border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000'>
    <tbody>
        <tr>
            <td style='padding:13px 12px 0px 12px'>
                <table width='100%' border='0' cellspacing='0' cellpadding='0' style='background: #eff2f3;'>
                    <tbody>
                        <tr>
                            <td style='padding:7px'>
                                <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                                    <tbody></tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<table width='850' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#eff2f3' style='border-right:1px solid #d5d8d9;border-left:1px solid #d5d8d9;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000'>
    <tbody>
        <tr>
            <td style='padding:0px 12px 0px 12px'>
                <table width='80%' border='0' cellspacing='0' cellpadding='0' bgcolor='#FFFFFF' style='border-right:1px solid #d5d8d9;border-top: 1px solid #D5D8D9;border-left:1px solid #d5d8d9;margin-left:10%;'>
                    <tbody>
                        <tr>
                            <td style='padding:0px 10px'>
                                <table style="width: 100%;margin-top:10px;" CELLSPACING="0">
                                    <tr>
                                        <td style="width:72%;border:1px solid black;padding-bottom:10px;">
                                            <div style="text-align:center;font-size:20px;font-weight:bold;padding-bottom: 10px;">{{$socity->society_name}}</div>
                                            <div class="font-15" style="text-align:center;padding-bottom: 10px;">{{$socity->society_name_number.' dt.'.date('m-d-Y', strtotime($socity->society_name_date))}}</div>
                                            <div class="font-15" style="text-align:center;padding-bottom: 10px;">{{$socity->address}}</div>
                                        </td>
                                        <td  style="width:28%;border:1px solid black;border-left:0;padding-bottom:10px;">
                                            <div style="text-align:center;font-size:21px;padding-top:12px;font-weight:bold;padding-bottom: 10px;background-color:#f58220;color: white;vertical-align: top;" >RECEIPT</div>
                                            <p class="font-15" style="text-align:left;padding-left: 5px;"><b>No : </b>{{$receipt->serial_number}}</p>
                                            <p class="font-15" style="text-align:left;padding-left: 5px;"><b>Date : </b>{{date('m-d-Y', strtotime($receipt->submit_date))}}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  style="width:72%;border:1px solid black;padding-bottom:10px;border-bottom: 0;">
                                            <div class="font-15" style="text-align:left;padding-bottom: 10px;padding-left:15px; padding-top: 15px;">
                                                <?php 
                                                    $str = $receipt->toLedger->name; 
                                                    $str_len = strlen($receipt->toLedger->name);
                                                ?>
                                                <b>RECEIVED WITH THANKS FROM SHRI / SMT :</b>
                                                @if(strlen($str_len)>64)
                                                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px;">
                                                        <?= substr($str, 0,63) ?>
                                                    </div>
                                                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px;">'
                                                        <?= substr($str, 0,63) ?>
                                                    </div>
                                                @else	
                                                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px;">
                                                        <?= $str ?>		
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="font-15" style="text-align:left;padding-bottom: 10px;padding-left:15px; padding-top: 15px;">
                                                <?php 
                                                    $rs = NumberToWordConvert($receipt->amount); 
                                                    $rs_len = strlen($rs);
                                                ?>
                                                <b>THE SUM OF RUPEES :</b>
                                                @if(strlen($rs_len)>64)
                                                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px; text-transform: capitalize;">
                                                        <?= substr($rs, 0,63) ?>
                                                    </div>
                                                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px; text-transform: capitalize;">
                                                        <?= substr($rs, 0,63) ?>
                                                    </div>
                                                @else	
                                                    <div style="width:96.3%;border-bottom: 1px solid black;padding-top: 7px;padding-top: 7px; text-transform: capitalize;">
                                                        <?= $rs ?>		
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td  style="width:28%;border:1px solid black;border-left:0;padding-bottom:10px;">
                                            <div class="font-17" style="text-align:left;padding-left: 5px;">
                                                <b style="font-size: 25px;padding-top:5px;"> ₹ </b> 
                                                <div style="width:75%;padding:5px 25px;margin-left:7px;border:1px solid black;border-radius: 8px;display:inline;">{{$receipt->amount}}</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  style="width:72%;border:1px solid black;padding-bottom:10px;border-top: 0;">
                                            <table class="font-15" style="text-align:left;padding-bottom: 10px;padding-left:15px;width:100%;" cellspacing="0">
                                                <tr>
                                                    <th style="width: 25%; border: 1px solid black;padding: 7px;">BY</th>
                                                    <th style="width: 68%; border: 1px solid black;padding: 7px;">PARTICULARS</th>
                                                </tr>			
                                                <tr>
                                                    <td style="font-weight: bold; width: 25%; border: 1px solid black;padding: 7px;">
                                                        CHEQUE / UPI / NEFT / RTGS		
                                                    </td>
                                                    <td style="width: 68%; border: 1px solid black;padding: 7px;">
                                                        {{$receipt->narration}}	
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td  style="border-bottom: 1px solid black;">&nbsp;</td>
                                                    <td colspan="2" style="border-bottom: 1px solid black;">&nbsp;</td>
                                                </tr>
                                                <tr style="padding-top: 15px;">
                                                    <th style="width :25%;border: 1px solid black;padding: 7px;">
                                                        UNIT NO.		
                                                    </th>
                                                    <td style="width :71.3%;border: 1px solid black;padding: 7px;border-left: 0;"  colspan="2">
                                                        {{$receipt->toLedger->wing_flat_no}}		
                                                    </td>
                                                </tr>
                                                <tr style="padding-top: 15px;">
                                                    <td class="font-13" style="width :96.3%;padding: 7px;border-left: 0;"  colspan="3">
                                                        <b>Note</b><br>
                                                        1) Subject To Realizations of Cheque / Transactions.<br>
                                                        2) Acknowledgment of This Receipt Being Passed by Allotte / Holders.			
                                                    </td>
                                                </tr>			
                                            </table>
                                        </td>
                                        <td  style="width:28%;border:1px solid black;border-left:0;padding-bottom:10px;">
                                            <div class="font-17" style="text-align:center;padding-bottom: 15px;">
                                                <b >
                                                    FOR {{$socity->society_name}}
                                                </b>
                                            </div>
                                            <div class="font-14" style="text-align:left;padding-left: 5px;"> <div style="width:60%;height:125px;margin-left:17%;border:1px dashed black;border-radius: 8px;"></div></div>
                                            <div class="font-14" style="text-align:center;padding-left: 5px;font-weight: bold;padding-top: 15px;"> AUTHORIZED SIGNATORY</div>
                                        </td>
                                    </tr>
                                </table>	
                                <br>	        
                            </td>
                        </tr>
                        <tr>
                            <td style='padding:10px 10px'>https://jtechnoholic.com/MySocietyAssistant/search-receipts</td>
                        </tr>
                    </tbody>
                </table>
                <br><br><br><br>
            </td>
        </tr>
    </tbody>
</table>
<div class='yj6qo'></div>
<div class='adL'></div>