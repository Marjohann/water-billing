<!DOCTYPE html>
<html>
<head>
	<title>Customer Sales Report (Detailed)</title>
	<style>
		body {
			font-family: 'Segoe UI',sans-serif;
			font-size: 12px;
		}
		table, th, td { border-color: white; }
		tr { border-bottom: none !important; }

		.report-header {
			font-size: 22px;
		}
		@media print {
      @page { margin: 0; }
      body { margin: 1.0cm; }
}
	</style>
	<script>
		(function(){
			window.print();
		})();
	</script>
</head>
<body>
	<table width="100%">
        <tr>
            <td width="10%"><img src="<?php echo base_url($company_info->logo_path); ?>" style="height: 90px; width: 120px; text-align: left;"></td>
            <td width="90%" class="align-center">
                <span class="report-header"><strong><?php echo $company_info->company_name; ?></strong></span><br>
                <span><?php echo $company_info->company_address; ?></span><br>
                <span><?php echo $company_info->landline.'/'.$company_info->mobile_no; ?></span>
            </td>
        </tr>
    </table><hr>
    <div class="">
        <h3><strong>SALES REPORT PER CUSTOMER (DETAILED)</strong></h3>
    </div>

	<?php foreach($customers as $customer) { ?>
		<table width="100%" border="1" cellspacing="0" cellpadding="2">
			<tr>
				<td colspan="7">
					<span style="font-size: 14px;"><strong><?php echo $customer->customer_name; ?></strong></span>
				</td>
			</tr>
			<tr>
				<td width="15%"><strong>Invoice #</strong></td>
				<td width="10%"><strong>Date</strong></td>
				<td width="10%"><strong>Product Code</strong></td>
				<td width="35%"><strong>Description</strong></td>
				<td width="10%" align="right"><strong>Unit Amount</strong></td>
				<td width="7%" align="right"><strong>Qty</strong></td>
				<td width="10%" align="right"><strong>Total Amount</strong></td>
			</tr>
			<?php $sum=0; ?>
			<?php foreach($sales_details as $sales_detail) { ?>
				<?php if ($customer->customer_id == $sales_detail->customer_id) { ?>
					<tr>
						<td><?php echo $sales_detail->sales_inv_no; ?></td>
						<td><?php echo date('Y-m-d', strtotime($sales_detail->date_invoice)); ?></td>
						<td><?php echo $sales_detail->product_code; ?></td>
						<td><?php echo $sales_detail->product_desc; ?></td>
						<td align="right"><?php echo number_format($sales_detail->inv_price,2); ?></td>
						<td align="right"><?php echo $sales_detail->inv_qty; ?></td>
						<td align="right"><?php echo number_format($sales_detail->total_amount,2); ?></td>
					</tr>
					<?php $sum += $sales_detail->total_amount;  ?>
				<?php } ?>
			<?php } ?>
			<tr>
				<td align="right" colspan="6"><strong>Total:</strong></td>
				<td align="right"><?php echo number_format($sum,2); ?></td>
			</tr>
		</table>
		<br>
	<?php } ?>
</body>
</html>