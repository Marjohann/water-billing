<head><title>Acknowledgement Receipt</title></head>
<body>
<style>
    table {
        font-size: 12px;
    }
    .bottom-only{
        border:none!important;
    }
    .align-right {
        text-align: right;
    }
    .align-left {
        text-align: left;
    }
    .align-center {
        text-align: center;
    }
    .report-header {
        font-weight: bolder;
    }
    table{
        border:none!important;
    }
    table tbody tr td {
            line-height: 1.4em;
    }
    .center{text-align: center;}
</style>
<div>
</div>
<div style="page-break-after:inherit;">
    <?php for($x = 1; $x <= 3; $x++){?>

<div class="center" style="text-transform: uppercase;">
    <b style="font-size: 15px;"><?php echo $company_info->company_name; ?></b><br/>
    <?php echo $company_info->company_address; ?><br><br>
    Acknowledgement Receipt
</div>
<table style="width:100%;">
    <tbody>
        <tr>
            <td width="15%">AR No:</td>
            <td><?php echo $info->receipt_no; ?> </td>
            <td width="15%">Date:</td>
            <td width="10%"><?php echo $info->date_paid; ?></td>
        </tr>
        <tr>
            <td>Customer:</td>
            <td><?php echo $info->receipt_name; ?> </td>
            <td>Meter No:</td>
            <td><?php echo $info->serial_no; ?></td>
        </tr>
        <tr>
            <td>Account No:</td>
            <td><?php echo $info->account_no; ?></td>
            <td><?php if($info->payment_method_id == 2) { ?> Check No: <?php } ?></td>
            <td><?php if($info->payment_method_id == 2) { echo $info->check_no; } ?> </td>
        </tr>
        <tr>
            <td>Amount Paid:</td>
            <td><?php echo number_format($info->total_payment_amount,2); ?> </td>
            <td><?php if($info->payment_method_id == 2) { ?> Check Date: <?php } ?></td>
            <td><?php if($info->payment_method_id == 2) { echo $info->check_date; } ?></td>
        </tr>
        <tr>
            <td>Amount in Words:</td>
            <td colspan="3" style="text-transform: capitalize;"><?php if($info->total_payment_amount == '0.00'){ echo 'Zero ';} ?><?php echo $num_words; ?></td>
        </tr>
        <?php if($info->total_deposit_amount != '0.00') { ?>
        <tr>
            <td>Payment from Deposit:</td>
            <td><?php  echo number_format($info->total_deposit_amount,2); ?> </td>
            <td></td>
            <td></td>
        </tr>
        <?php } ?>
        <tr>
            <td>Remarks:</td>
            <td colspan="3"><?php echo $info->remarks; ?> <br></td>
        </tr>
        <tr>
        <td colspan="4"> &nbsp;</td>
        </tr>
        <tr>
            <td>Noted By:</td>
            <td>___________________</td>
            <td>Received By:</td>
            <td>___________________</td>
        </tr>
    </tbody>
</table>
<table width="100%" style="margin-bottom: 10px;">
    <tr>
        <td width="85%" style="font-size: 10pt!important;"></b></td>  
        <td width="15%" style="text-align: right;font-size: ">  
            <?php if($x == 1){ echo 'File Copy'; }else if ($x == 2){ echo 'Customer Copy'; }else{ echo 'Accounting Copy'; }?>
        </td>    
    </tr>
</table>
    <center>
        <div style="border-top: 1px dashed black;height: 1px;width: 100%;text-align: center!important;margin-left: auto;margin-right: auto;display: block;margin-top: 5px;margin-bottom: 5px;"></div>
    </center>
    <br/>

</div>
<?php }?>
<script>
    window.print();
</script>

















