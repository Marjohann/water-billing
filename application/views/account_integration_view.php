<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from avenxo.kaijuthemes.com/ui-typography.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 06 Jun 2016 12:09:25 GMT -->
<head>
    <meta charset="utf-8">
    <title>JCORE - <?php echo $title; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="description" content="Avenxo Admin Theme">
    <meta name="author" content="">


    <?php echo $_def_css_files; ?>

    <link rel="stylesheet" href="assets/plugins/spinner/dist/ladda-themeless.min.css">

    <link type="text/css" href="assets/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
    <link type="text/css" href="assets/plugins/datatables/dataTables.themify.css" rel="stylesheet">
    <link href="assets/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="assets/plugins/select2/select2.min.css" rel="stylesheet">



    <style>

/*        h4{
            color:white;
        }*/
        .toolbar{
            float: left;
        }

        td.details-control {
            background: url('assets/img/Folder_Closed.png') no-repeat center center;
            cursor: pointer;
        }
        tr.details td.details-control {
            background: url('assets/img/Folder_Opened.png') no-repeat center center;
        }

        .child_table{
            padding: 5px;
            border: 1px #ff0000 solid;
        }

        .glyphicon.spinning {
            animation: spin 1s infinite linear;
            -webkit-animation: spin2 1s infinite linear;
        }

        .select2-container{
            min-width: 100%;
        }

/*        .dropdown-menu > .active > a,.dropdown-menu > .active > a:hover{
            background-color: dodgerblue;
        }*/

        @keyframes spin {
            from { transform: scale(1) rotate(0deg); }
            to { transform: scale(1) rotate(360deg); }
        }

        @-webkit-keyframes spin2 {
            from { -webkit-transform: rotate(0deg); }
            to { -webkit-transform: rotate(360deg); }
        }
/*
.tab-primary.tab-container > .nav-tabs > li.active > a {
background:#1f1f1f!important;color: white !important;border-top: 0.5px solid #ffd65c!important;
}

.tab-primary.tab-container > .nav-tabs > li > a {
background: #616161 !important;color: white !important;border-top: 0.5px solid white
}*/

/*.table-striped > tbody > tr:nth-child(odd) {
    background-color: transparent!important;
    color:white;
}
.table-striped > tbody > tr:nth-child(even) {
 background-color: transparent!important;
 color:white;
}*/

/*div.dataTables_info {
    padding-top: 8px;
    color: white;}

    
.tab-container .tab-content {
    border-radius: 0 2px 2px 2px;
    border: 1px solid #e0e0e0;
    padding: 16px;
    background-color: #212121!important;
}
*/

        /*table{
            min-width: 700px;
        }

        .dataTables_filter{
            min-width: 700px;
        }

        .dataTables_info{
            min-width: 700px;
        }

        .dataTables_paginate{
            float: left;
            width: 100%;
        }*/

    </style>
</head>

<body class="animated-content">

<?php echo $_top_navigation; ?>

<div id="wrapper">
<div id="layout-static">


<?php echo $_side_bar_navigation;?>


<div class="static-content-wrapper white-bg">


<div class="static-content"  >
<div class="page-content"><!-- #page-content -->

<ol class="breadcrumb" style="margin-bottom: 0px;">
    <li><a href="dashboard">Dashboard</a></li>
    <li><a href="Account_integration">Account Integration  <?php //print_r($user_groups); ?></a></li>
</ol>


<div class="container-fluid">
<div data-widget-group="group1">
<div class="row">
    <div class="col-md-12">
        <div class="tab-container tab-top tab-primary">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#accounts_integration_setting_billing" data-toggle="tab" style="font-family: tahoma;"> Billing</a></li>
                <li ><a href="#accounts_integration_setting_supplier" data-toggle="tab" style="font-family: tahoma;"> Supplier</a></li>
                <li><a href="#accounts_integration_setting_customer" data-toggle="tab" style="font-family: tahoma;"> Customer</a></li>
                <li><a href="#accounts_integration_setting_inventory" data-toggle="tab" style="font-family: tahoma;"> Inventory</a></li>
                <li><a href="#accounts_integration_setting" data-toggle="tab" style="font-family: tahoma;"> Other Accounts</a></li>
                <li><a href="#accounts_integration_adjustment" data-toggle="tab" style="font-family: tahoma;">Adjustments</a></li>
                <li><a href="#accounts_integration_item_transfer" data-toggle="tab" style="font-family: tahoma;">Item Transfer</a></li>
                <!-- <li class=""><a href="#sched_expense_setting" data-toggle="tab" style="font-family: tahoma;"><i class="fa fa-gear"></i> Expense Group (Schedule of Expense)</a></li> -->
                <li class=""><a href="#account_year_setting" data-toggle="tab" style="font-family: tahoma;"> Accounting Period</a></li>
                <!-- <li class=""><a href="#invoice_counter_setting" data-toggle="tab" style="font-family: tahoma;"><i class="fa fa-code"></i> Invoice Number</a></li> -->

            </ul>
            <div class="tab-content">
<!-- ITEM TRANSFER START -->
            <div class="tab-pane active" id="accounts_integration_setting_billing" style="min-height: 300px;">
                <form id="frm_account_integration_billing" role="form" class="form-horizontal row-border">
                        <h4><span style="margin-left: 1%"><strong><i class="fa fa-gear"></i> Water Billing Settings </strong></span></h4>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b>Residential Rate :</label>
                            <div class="col-md-7">
                                <select name="default_matrix_residential_id" class="cbo_accounts" data-error-msg="Issuance Supplier is required." required>
                                    <?php foreach($residentials as $residential){ ?>
                                        <option value="<?php echo $residential->matrix_residential_id; ?>" <?php echo ($current_accounts->default_matrix_residential_id==$residential->matrix_residential_id?'selected':''); ?>  ><?php echo $residential->matrix_residential_code; ?> - <?php echo $residential->description; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block m-b-none">Please Choose a default Residential Rate to be used in creating Statement of Accounts. </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b> Commercial Rate :</label>
                            <div class="col-md-7">
                                <select name="default_matrix_commercial_id" class="cbo_accounts" data-error-msg="Issuance Supplier is required." required>
                                    <?php foreach($commercials as $commercial){ ?>
                                        <option value="<?php echo $commercial->matrix_commercial_id; ?>" <?php echo ($current_accounts->default_matrix_commercial_id==$commercial->matrix_commercial_id?'selected':''); ?>  ><?php echo $commercial->matrix_commercial_code; ?> - <?php echo $commercial->description; ?></option>
                                    <?php } ?>
                                </select>
                            <span class="help-block m-b-none">Please Choose a default Commercial Rate to be used in creating Statement of Accounts. </span>
                            </div><br>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b> Meter Income Account :</label>
                            <div class="col-md-7">
                                <select name="billing_meter_account_id" class="cbo_accounts" data-error-msg=" Meter Income Account is required." required>
                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->billing_meter_account_id==$account->account_id?'selected':''); ?> ><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>
                            <span class="help-block m-b-none">Please Choose a Default Income Account for Meter Charges. </span>
                            </div><br>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b> Meter Penalty Receivable Account :</label>
                            <div class="col-md-7">
                                <select name="billing_receivable_penalty_account_id" class="cbo_accounts" data-error-msg="Meter Penalty Receivable Account is required." required>
                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->billing_receivable_penalty_account_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>
                            <span class="help-block m-b-none">Please Choose a Default Income Account for Meter Charges Penalty. </span>
                            </div><br>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b> Meter Penalty Income Account :</label>
                            <div class="col-md-7">
                                <select name="billing_penalty_account_id" class="cbo_accounts" data-error-msg="Meter Penalty Income Account is required." required>
                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->billing_penalty_account_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>
                            <span class="help-block m-b-none">Please Choose a Default Income Account for Meter Charges Penalty. </span>
                            </div><br>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b> Security Deposit Account :</label>
                            <div class="col-md-7">
                                <select name="billing_security_deposit_account_id" class="cbo_accounts" data-error-msg="Security Deposit Account is required." required>
                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->billing_security_deposit_account_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>
                            <span class="help-block m-b-none">Please Choose a Default Income Account for Security Deposits. </span>
                            </div><br>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b> Department :</label>
                            <div class="col-md-7">
                                <select name="billing_department_id" class="cbo_accounts" data-error-msg="Department is required." required>
                                    <?php foreach($departments as $department){ ?>
                                        <option value="<?php echo $department->department_id; ?>" <?php echo ($current_accounts->billing_department_id==$department->department_id?'selected':''); ?>><?php echo $department->department_name; ?></option>
                                    <?php } ?>
                                </select>
                            <span class="help-block m-b-none">Please Choose a default Department for Billing Transactions in Accounting. </span>
                            </div><br>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b> Various Customers :</label>
                            <div class="col-md-7">
                                <select name="billing_customer_id" class="cbo_accounts" data-error-msg="Various Customers is required." required>
                                <?php foreach($customers as $customer){ ?>
                                    <option value="<?php echo $customer->customer_id; ?>" <?php echo ($current_accounts->billing_customer_id==$customer->customer_id?'selected':''); ?>><?php echo $customer->customer_name; ?></option>
                                <?php } ?>
                                </select>
                            <span class="help-block m-b-none">Please Choose a default Customer for Billing Transactions in Accounting.</span>
                            </div><br>
                        </div>
                </form>
                    <div class="col-sm-offset-3">
                        <button id="btn_save_water_billing" type="button" class="btn btn-primary" style="font-family: tahoma;text-transform: none;"><span class=""></span> Save Water Billing Configuration Changes</button>
                    </div>
            </div>

<!-- ITEM TRANSFER END -->
<!-- ITEM TRANSFER START -->
            <div class="tab-pane" id="accounts_integration_item_transfer" style="min-height: 300px;">
                <form id="frm_account_integration_item_transfer" role="form" class="form-horizontal row-border">
                        <h4><span style="margin-left: 1%"><strong><i class="fa fa-gear"></i> Item Transfer </strong></span></h4>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b>Issuance Supplier :</label>
                            <div class="col-md-7">
                                <select name="iss_supplier_id" class="cbo_accounts" data-error-msg="Issuance Supplier is required." required>
                                    <?php foreach($suppliers as $supplier){ ?>
                                        <option value="<?php echo $supplier->supplier_id; ?>" <?php echo ($current_accounts_purchasing->iss_supplier_id==$supplier->supplier_id?'selected':''); ?>  ><?php echo $supplier->supplier_name; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block m-b-none">Please Choose a default Supplier for Inventory Transfer Items </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b> Inventory Account :</label>
                            <div class="col-md-7">
                                <select name="iss_debit_id" class="cbo_accounts" data-error-msg="Debit Account is required." required>
                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts_purchasing->iss_debit_id==$account->account_id?'selected':''); ?>  ><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>
                            <span class="help-block m-b-none">Please Choose Account for Inventory Transfer Items</span>
                            </div><br>
                        </div>
                </form>
                    <div class="col-sm-offset-3">
                        <button id="btn_save_item_transfer_accounts" type="button" class="btn btn-primary" style="font-family: tahoma;text-transform: none;"><span class=""></span> Save Item Transfer Configuration Changes</button>
                    </div>
            </div>

<!-- ITEM TRANSFER END -->
<!-- ADJUSTMENTS START -->
            <div class="tab-pane" id="accounts_integration_adjustment" style="min-height: 300px;">
                <form id="frm_account_integration_adjustment" role="form" class="form-horizontal row-border">
                        <h4><span style="margin-left: 1%"><strong><i class="fa fa-gear"></i> Adjustment Details</strong></span></h4>

                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b> Adjustment Supplier :</label>
                            <div class="col-md-7">
                                <select name="adj_supplier_id" class="cbo_accounts" data-error-msg="Adjustment Supplier is required." required>
                                    <?php foreach($suppliers as $supplier){ ?>
                                        <option value="<?php echo $supplier->supplier_id; ?>" <?php echo ($current_accounts_purchasing->adj_supplier_id==$supplier->supplier_id?'selected':''); ?>  ><?php echo $supplier->supplier_name; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block m-b-none">Please Choose a default Supplier for Adjusting Inventory.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b> Debit Account :</label>
                            <div class="col-md-7">
                                <select name="adj_debit_id" class="cbo_accounts" data-error-msg="Debit Account is required." required>
                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts_purchasing->adj_debit_id==$account->account_id?'selected':''); ?>  ><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block m-b-none">Please Choose Debit Account for the Adjustment OUT of Inventory</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> <b class="required"> * </b> Credit Account :</label>
                            <div class="col-md-7">
                                <select name="adj_credit_id" class="cbo_accounts" data-error-msg="Credit Account is required." required>
                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts_purchasing->adj_credit_id==$account->account_id?'selected':''); ?>  ><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block m-b-none">Please Choose Credit Account for the Adjustment IN of Inventory</span>
                            </div>
                        </div>
                </form>
                    <div class="col-sm-offset-3">
                        <button id="btn_save_adjustment_accounts" type="button" class="btn btn-primary" style="font-family: tahoma;text-transform: none;"><span class=""></span> Save Adjustments Configuration Changes</button>
                    </div>
            </div>

<!-- ADJUSTMENTS END -->

<!-- INVENTORY START -->
            <div class="tab-pane" id="accounts_integration_setting_inventory" style="min-height: 300px;">
                <form id="frm_account_integration_inventory" role="form" class="form-horizontal row-border">
                    <h4><span style="margin-left: 1%"><strong><i class="fa fa-gear"></i> Inventory</strong></span></h4>
                    <div class="form-group">
                        <label class="col-md-3 control-label"> * Sales Invoice Integration :</label>
                        <div class="col-md-7">
                            <select name="sales_invoice_inventory" class="cbo_accounts"   id="cbo_inventory" data-error-msg="Inventory is required." required>
                           

                            <option value="1" <?php echo ($current_accounts->sales_invoice_inventory == 1 ? 'selected' :'')   ?> >Enable</option>
                            <option value="0" <?php echo ($current_accounts->sales_invoice_inventory == 0 ? 'selected' :'')   ?>> Disable</option>
                            </select>

                            <span class="help-block m-b-none">Please select if Sales Invoices will be included in the Inventory computation.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"> * Cash Invoice Integration :</label>
                        <div class="col-md-7">
                            <select name="cash_invoice_inventory" class="cbo_accounts"   id="cbo_inventory" data-error-msg="Inventory is required." required>
                           

                            <option value="1" <?php echo ($current_accounts->cash_invoice_inventory == 1 ? 'selected' :'')   ?> >Enable</option>
                            <option value="0" <?php echo ($current_accounts->cash_invoice_inventory == 0 ? 'selected' :'')   ?>> Disable</option>
                            </select>

                            <span class="help-block m-b-none">Please select if Cash Invoices will be included in the Inventory computation.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"> * Warehouse Dispatching Invoice Integration :</label>
                        <div class="col-md-7">
                            <select name="dispatching_invoice_inventory" class="cbo_accounts"   id="cbo_inventory" data-error-msg="Inventory is required." required>
                           

                            <option value="1" <?php echo ($current_accounts->dispatching_invoice_inventory == 1 ? 'selected' :'')   ?> >Enable</option>
                            <option value="0" <?php echo ($current_accounts->dispatching_invoice_inventory == 0 ? 'selected' :'')   ?>> Disable</option>
                            </select>

                            <span class="help-block m-b-none">Please select if Dispatching Invoices will be included in the Inventory computation.</span>
                        </div>
                    </div>
                </form>
                    <div class="col-sm-offset-3">
                        <button id="btn_save_inventory_accounts" type="button" class="btn btn-primary" style="font-family: tahoma;text-transform: none;"><span class=""></span> Save Inventory Configuration Changes</button>
                    </div>
            </div>

<!-- INVENTORY END -->
<!--  CUSTOMER START -->
             <div class="tab-pane" id="accounts_integration_setting_customer" style="min-height: 300px;">
                <form id="frm_account_integration_customer" role="form" class="form-horizontal row-border">
                    <h4><span style="margin-left: 1%"><strong><i class="fa fa-gear"></i> Customer Integration Account</strong></span></h4>
                    <div class="form-group">
                        <label class="col-md-3 control-label"> * Output Tax Account :</label>
                        <div class="col-md-7">
                            <select name="output_tax_account_id"  class="cbo_accounts" data-error-msg="Output Tax account is required." required>

                                <?php foreach($accounts as $account){ ?>
                                    <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->output_tax_account_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                <?php } ?>
                            </select>

                            <span class="help-block m-b-none">Output tax is the amount charge on your own sales if you are registered as Vatted.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"> * Receivable from Customer :</label>
                        <div class="col-md-7">
                            <select name="receivable_account_id"  class="cbo_accounts" data-error-msg="Receivable account is required." required>

                                <?php foreach($accounts as $account){ ?>
                                    <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->receivable_account_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                <?php } ?>
                            </select>

                            <span class="help-block m-b-none">Account that represents the amount of goods and services credited by customer.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"> * Discount to Customer :</label>
                        <div class="col-md-7">
                            <select name="receivable_discount_account_id"  class="cbo_accounts" data-error-msg="Receivable account is required." required>
                                <?php foreach($accounts as $account){ ?>
                                    <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->receivable_discount_account_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                <?php } ?>
                            </select>

                            <span class="help-block m-b-none">Please select Discount Account.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"> * Collection Account :</label>
                        <div class="col-md-7">
                            <select name="payment_from_customer_id"  class="cbo_accounts" data-error-msg="Discount Account is required." required>

                                <?php foreach($accounts as $account){ ?>
                                    <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->payment_from_customer_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                <?php } ?>
                            </select>

                            <span class="help-block m-b-none">Please select the account where payment of customer will be posted.</span>
                        </div>
                    </div>
                   <div class="col-sm-offset-3">
                        <button id="btn_save_customer_accounts" type="button" class="btn btn-primary" style="font-family: tahoma;text-transform: none;"><span class=""></span> Save Customer Configuration Changes</button>
                    </div>
                </form>
             </div>
<!-- CUSTOMER END -->
<!-- SUPPLIER START -->
                <div class="tab-pane" id="accounts_integration_setting_supplier" style="min-height: 300px;">
                <form id="frm_account_integration_supplier" role="form" class="form-horizontal row-border">
                        <h4><span style="margin-left: 1%"><strong><i class="fa fa-gear"></i> Supplier Integration Account</strong></span></h4>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> * Input Tax Account :</label>
                            <div class="col-md-7">
                                <select name="input_tax_account_id" class="cbo_accounts" data-error-msg="Input Tax Account is required." required>
                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->input_tax_account_id==$account->account_id?'selected':''); ?>  ><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block m-b-none">Input Tax is generally apply to the purchases of goods and services.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> * Payable to Supplier :</label>
                            <div class="col-md-7">
                                <select name="payable_account_id"  class="cbo_accounts" data-error-msg="Payable Account is required." required>
                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->payable_account_id==$account->account_id?'selected':''); ?> ><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block m-b-none">Account that is used to represent the amount owes by the company.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> * Discount from Supplier :</label>
                            <div class="col-md-7">
                                <select name="payable_discount_account_id"  class="cbo_accounts" data-error-msg="Discount Account is required." required>

                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->payable_discount_account_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>

                                <span class="help-block m-b-none">Please select Discount Account.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> * Payment to Supplier :</label>
                            <div class="col-md-7">
                                <select name="payment_to_supplier_id"  class="cbo_accounts" data-error-msg="Discount Account is required." required>

                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->payment_to_supplier_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>

                                <span class="help-block m-b-none">Please select the account where payment to supplier will be credited.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> * Withholding Tax Account :</label>
                            <div class="col-md-7">
                                <select name="supplier_wtax_account_id" class="cbo_accounts" data-error-msg="Withholding Tax Account is required." required>

                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->supplier_wtax_account_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block m-b-none">Please select the account where supplier withholding tax will be credited.</span>
                            </div>
                        </div>
                        <div class="col-sm-offset-3">
                            <button id="btn_save_supplier_accounts" type="button" class="btn btn-primary" style="font-family: tahoma;text-transform: none;"><span class=""></span> Save Supplier Configuration Changes</button>
                        </div>
                    </form>
                </div>
<!-- SUPPLIER END -->

                <div class="tab-pane" id="accounts_integration_setting" style="min-height: 300px;">
                    <form id="frm_account_integration" role="form" class="form-horizontal row-border">
                        <br >
                        <h4><span style="margin-left: 1%"><strong><i class="fa fa-gear"></i> Retained Earnings Account</strong></span></h4>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> * Retained Earnings :</label>
                            <div class="col-md-7">
                                <select name="retained_earnings_id"  class="cbo_accounts" data-error-msg="Retained earnings is required." required>

                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->retained_earnings_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block m-b-none">Please select the account where net income will be forwarded.</span>
                            </div>
                        </div>

                        <br >
                        <h4><span style="margin-left: 1%"><strong><i class="fa fa-gear"></i> Petty Cash Account</strong></span></h4>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> * Petty Cash :</label>
                            <div class="col-md-7">
                                <select name="petty_cash_account_id" class="cbo_accounts" data-error-msg="Petty Cash account is required." required>

                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->petty_cash_account_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>

                                <span class="help-block m-b-none">Please select the account where petty cash will be forwarded.</span>
                            </div>
                        </div>
                        <br >
                        <h4><span style="margin-left: 1%"><strong><i class="fa fa-gear"></i> Depreciation Expense Account</strong></span></h4>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> * Debit Account :</label>
                            <div class="col-md-7">
                                <select name="depreciation_expense_debit_id"  class="cbo_accounts" data-error-msg="Depreciation Debit account is required." required>

                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->depreciation_expense_debit_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>

                                <span class="help-block m-b-none">Account that represents the Debit Entry of the Depreciation Expense</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"> * Credit Account :</label>
                            <div class="col-md-7">
                                <select name="depreciation_expense_credit_id"  class="cbo_accounts" data-error-msg="Depreciation Credit account is required." required>

                                    <?php foreach($accounts as $account){ ?>
                                        <option value="<?php echo $account->account_id; ?>" <?php echo ($current_accounts->depreciation_expense_credit_id==$account->account_id?'selected':''); ?>><?php echo $account->account_title; ?></option>
                                    <?php } ?>
                                </select>

                                <span class="help-block m-b-none">Account that represents the Credit Entry of the Depreciation Expense</span>
                            </div>
                        </div>
                        <hr />
                        <div class=" col-lg-offset-3">
                            <button id="btn_save_other_accounts" type="button" class="btn btn-primary" style="font-family: tahoma;text-transform: none;"><span class=""></span> Save Changes</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="sched_expense_setting" style="min-height: 300px;">
                    <br />
                   <h8 style="color: white;"> Please specify the group of each account :</h8><br />
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="10%">Account #</th>
                                <th width="35%">Account</th>
                                <th width="12%">Type</th>
                                <th width="20%">Classification</th>
                                <th width="17%">Group</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($expenses as $expense){ ?>
                            <tr data-account-id="<?php echo $expense->account_id; ?>">
                                <td><?php echo $expense->account_no; ?></td>
                                <td><?php echo $expense->account_title; ?></td>
                                <td><?php echo $expense->account_type; ?></td>
                                <td><?php echo $expense->account_class; ?></td>
                                <td>
                                    <div class="div_account_group">
                                        <select class="account_group form-control">
                                            <option value="1" <?php echo ($expense->group_id==1?"selected":""); ?>   >General and Administrative</option>
                                            <option value="2" <?php echo ($expense->group_id==2?"selected":""); ?>   >Selling Expense</option>
                                        </select>
                                    </div>
                                    <div class="div_account_spinner" style="display: none;">
                                        <center><img src="assets/img/loader/facebook.gif" /></center>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table><br /><br />
                </div>


                <div class="tab-pane" id="account_year_setting" style="min-height: 300px;">
                        <div id="div_account_year_list">
                            <br />
                            <table id="tbl_account_year" class="table table-striped" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <td width="40%">Accounting Period</td>
                                        <td width="15%">Date/Time Closed</td>
                                        <td width="15%">Closed by</td>
                                        <td width="30%">Remarks</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="div_account_year_fields" style="display: none;">
                            <div class="row">
                                <div class="col-lg-12">
                                    <br >
                                    <h4><span style="margin-left: 1%"><strong><i class="fa fa-calendar"></i> Accounting Period</strong></span></h4>
                                    <hr />
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> * Account Year (Code):</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" data-error-msg="Account year is required." required>

                                            <span class="help-block m-b-none">Please enter the code you want to represent the accounting period. Ex. AY2016</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> * Period Start :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" data-error-msg="Account year is required." required>

                                            <span class="help-block m-b-none">Please enter the code you want to represent the accounting period. Ex. AY2016</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> * Period End :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" data-error-msg="Account year is required." required>
                                            <span class="help-block m-b-none">Please enter the code you want to represent the accounting period. Ex. AY2016</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"> * Description :</label>
                                        <div class="col-md-7">
                                            <textarea class="form-control"></textarea>
                                            <span class="help-block m-b-none">Ex. 2016</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>


                <div class="tab-pane" id="invoice_counter_setting" style="min-height: 300px;">

                        <br >
                        <h4><span style="margin-left: 1%"><strong><i class="fa fa-calendar"></i> Invoice Number Range per User/Cashier</strong></span></h4>
                        <hr />


                        <table id="tbl_invoice_no_range" class="table table-stripe table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr style="border-bottom: 1px solid black;">
                                    <td width="30%"><b>User</b></td>
                                    <td width="30%"><b>Group</b></td>
                                    <td width="10%"><b>Last Invoice #</b></td>
                                    <td width="10%"><b>Start</b></td>
                                    <td width="10%"><b>End</b></td>
                                    <td width="10%" align="center">Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users_counter as $uc){ ?>

                                <tr>
                                    <td><?php echo $uc->user_fullname; ?></td>
                                    <td><?php echo $uc->user_group; ?></td>
                                    <td><?php echo $uc->last_invoice; ?></td>
                                    <td><input name="invoice_start" type="number" value="<?php echo $uc->counter_start; ?>" class="form-control" /></td>
                                    <td><input name="invoice_end" type="number" value="<?php echo $uc->counter_end; ?>" class="form-control" /></td>
                                    <td align="center"><button name="save_counter" data-user-id="<?php echo $uc->user_id; ?>" type="button" class="btn btn-success"><i class="fa fa-save"></button></td>
                                </tr>

                                <?php } ?>

                            </tbody>
                        </table>




                </div>

            </div>


        </div>







    </div>
</div>
</div>
</div> <!-- .container-fluid -->

</div> <!-- #page-content -->
</div>


    <div id="modal_close_accounting_period" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-erp" style="padding: 5px !important;">
                    <h2 style="color:white; padding-left: 10px;">Accounting Period</h2>
                </div>
                <div class="modal-body">
                    <form id="frm_accounting_period">
                       <div class="row">
                           <div class="col-lg-12">
                                <b>Close all open transactions up to Date * :</b><br />
                               <div class="input-group" style="z-index: 999999999;">
                                   <input type="text" name="date" class="date-picker form-control" value="<?php echo date("m/d/Y"); ?>" placeholder="Date Invoice" data-error-msg="Please set the date this items are issued!" required>
                                     <span class="input-group-addon">
                                         <i class="fa fa-calendar"></i>
                                    </span>
                               </div>
                           </div>
                       </div>

                        <br />
                        <div class="row">
                            <div class="col-lg-12">
                                <b>Remarks :</b><br />
                                <textarea name="remarks" class="form-control"></textarea>
                            </div>
                        </div>
                    </form>
                </div>


                <div class="modal-footer">
                    <button id="btn_close_period" type="button" class="btn btn-primary"  style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"><span class=""></span> <i class="fa fa-bars"></i> Close Accounting Period</button>
                    <button id="" type="button" class="btn btn-default" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">Cancel</button>
                </div>

            </div>
        </div>
    </div>



<footer role="contentinfo">
    <div class="clearfix">
        <ul class="list-unstyled list-inline pull-left">
            <li><h6 style="margin: 0;">&copy; 2018 - JDEV OFFICE SOLUTION INC</h6></li>
        </ul>
        <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="ti ti-arrow-up"></i></button>
    </div>
</footer>




</div>
</div>


</div>


<?php echo $_switcher_settings; ?>
<?php echo $_def_js_files; ?>

<script src="assets/plugins/spinner/dist/spin.min.js"></script>
<script src="assets/plugins/spinner/dist/ladda.min.js"></script>


<script type="text/javascript" src="assets/plugins/datatables/jquery.dataTables.js"></script>
<script type="text/javascript" src="assets/plugins/datatables/dataTables.bootstrap.js"></script>




<!-- Date range use moment.js same as full calendar plugin -->
<script src="assets/plugins/fullcalendar/moment.min.js"></script>
<!-- Data picker -->
<script src="assets/plugins/datapicker/bootstrap-datepicker.js"></script>

<!-- Select2 -->
<script src="assets/plugins/select2/select2.full.min.js"></script>





<script>
$(document).ready(function(){
    var dt; var _txnMode; var _selectedID; var _selectRowObj; var _accounts;



    var initializeControls=function(){

        dt=$('#tbl_account_year').DataTable({
            "dom": '<"toolbar">frtip',
            "bLengthChange":false,
            "ajax" : "Account_integration/transaction/get-account-year",
            "columns": [

                { targets:[0],data: "date_covered" },
                { targets:[1],data: "date_time_closed" },
                { targets:[2],data: "user" },
                { targets:[4],data: "remarks" }
            ]
        });

        _accounts=$(".cbo_accounts").select2({
            placeholder: "Please select account.",
            allowClear: false
        });

        var createToolBarButton=function() {
            var _btnNew='<button class="btn btn-primary"  id="btn_close_accounting" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;" data-toggle="modal" data-target="" data-placement="left" title="Close Accounting Period" >'+
                '<i class="fa fa-bars"></i> Close Accounting Period</button>';
                $("div.toolbar").html(_btnNew);
        }();


        $('.date-picker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true

        });


        // $('#cbo_inventory').select2('val', 0);

    }();






    var bindEventHandlers=(function(){
            $('#btn_save_other_accounts').click(function(){
                saveSettings().done(function(response){
                    showNotification(response);
                }).always(function(){
                    showSpinningProgress($('#btn_save_other_accounts'));
                });
            });
            $('#btn_save_supplier_accounts').click(function(){
                saveSettingsSuppliers().done(function(response){
                    showNotification(response);
                }).always(function(){
                    showSpinningProgress($('#btn_save_supplier_accounts'));
                });
            });
            $('#btn_save_customer_accounts').click(function(){
                saveSettingsCustomers().done(function(response){
                    showNotification(response);
                }).always(function(){
                    showSpinningProgress($('#btn_save_customer_accounts'));
                });
            });
            $('#btn_save_inventory_accounts').click(function(){
                saveSettingsInventory().done(function(response){
                    showNotification(response);
                }).always(function(){
                    showSpinningProgress($('#btn_save_inventory_accounts'));
                });
            });
            $('#btn_save_adjustment_accounts').click(function(){
                saveSettingsAdjustment().done(function(response){
                    showNotification(response);
                }).always(function(){
                    showSpinningProgress($('#btn_save_adjustment_accounts'));
                });
            });

            $('#btn_save_item_transfer_accounts').click(function(){
                saveSettingsItemTransfer().done(function(response){
                    showNotification(response);
                }).always(function(){
                    showSpinningProgress($('#btn_save_item_transfer_accounts'));
                });
            });
            $('#btn_save_material_issuance_accounts').click(function(){
                saveSettingsMaterialIssuance().done(function(response){
                    showNotification(response);
                }).always(function(){
                    showSpinningProgress($('#btn_save_material_issuance_accounts'));
                });
            });

            $('#btn_save_water_billing').click(function(){
                saveSettingsWaterBilling().done(function(response){
                    showNotification(response);
                }).always(function(){
                    showSpinningProgress($('#btn_save_water_billing'));
                });
            });

            
            $('#btn_close_period').click(function(){
                var btn=$(this);
                var _data=$('#frm_accounting_period').serializeArray();

                $.ajax({
                    "dataType":"json",
                    "type":"POST",
                    "url":"Account_integration/transaction/close-period",
                    "data":_data,
                    "beforeSend": showSpinningProgress(btn),
                    "success": function(response){
                        if(response.stat=="success"){
                            //closed modal here
                            dt.row.add(response.row_added[0]).draw();
                            $('#modal_close_accounting_period').modal('hide');
                        }

                        showNotification(response);
                    },
                    "complete": function(){
                        showSpinningProgress(btn);
                    }

                });
            });


            $('#btn_new_account_year').click(function(){

                showList(false);
            });

            $('#btn_close_accounting').click(function(){
                $('#modal_close_accounting_period').modal('show');
            });


            $('#tbl_invoice_no_range').on('click','button[name="save_counter"]',function(){
                var row=$(this).closest('tr');

                var _invStart=row.find('input[name="invoice_start"]').val();
                var _invEnd=row.find('input[name="invoice_end"]').val();
                var id=$(this).data('user-id');

                var _data=[];

                _data.push({name:"start",value:_invStart});
                _data.push({name:"end",value:_invEnd});
                _data.push({name:"id",value:id});

                $.ajax({
                    "dataType":"json",
                    "type":"POST",
                    "url":"Account_integration/transaction/save-counter",
                    "data":_data,
                    "success" : function(response){
                        //if(response.stat=="success"){
                            showNotification(response);
                       // }
                    }

                });


            });

    })();


    var validateRequiredFields=function(f){
        var stat=true;

        $('div.form-group').removeClass('has-error');
        $('input[required],textarea[required],select[required]',f).each(function(){

            if($(this).is('select')){
                if($(this).select2('val')==0||$(this).select2('val')==null){
                    showNotification({title:"Error!",stat:"error",msg:$(this).data('error-msg')});
                    $(this).closest('div.form-group').addClass('has-error');
                    $(this).focus();
                    stat=false;
                    return false;
                }
            }else{
                if($(this).val()==""){
                    showNotification({title:"Error!",stat:"error",msg:$(this).data('error-msg')});
                    $(this).closest('div.form-group').addClass('has-error');
                    $(this).focus();
                    stat=false;
                    return false;
                }
            }



        });

        return stat;
    };



    var saveSettings=function(){
        var _data=$('#frm_account_integration').serializeArray();
        console.log(_data);

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Account_integration/transaction/save",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save_other_accounts'))

        });
    };

    var saveSettingsSuppliers=function(){
        var _data=$('#frm_account_integration_supplier').serializeArray();
        console.log(_data);

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Account_integration/transaction/save_supplier",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save_supplier_accounts'))

        });
    };
    var saveSettingsCustomers=function(){
        var _data=$('#frm_account_integration_customer').serializeArray();
        console.log(_data);

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Account_integration/transaction/save_customer",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save_customer_accounts'))

        });
    };

    var saveSettingsInventory=function(){
        var _data=$('#frm_account_integration_inventory').serializeArray();
        console.log(_data);

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Account_integration/transaction/save_inventory",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save_inventory_accounts'))

        });
    };

    var saveSettingsAdjustment=function(){
        var _data=$('#frm_account_integration_adjustment').serializeArray();
        console.log(_data);

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Account_integration/transaction/save_adjustments",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save_adjustment_accounts'))

        });
    };


    var saveSettingsItemTransfer=function(){
        var _data=$('#frm_account_integration_item_transfer').serializeArray();
        console.log(_data);

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Account_integration/transaction/save_item_transfer",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save_item_transfer_accounts'))

        });
    };

    var saveSettingsMaterialIssuance=function(){
        var _data=$('#frm_account_integration_material_issuance').serializeArray();
        console.log(_data);

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Account_integration/transaction/save_material_issuance",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save_material_issuance_accounts'))

        });
    };

    var saveSettingsWaterBilling=function(){
        var _data=$('#frm_account_integration_billing').serializeArray();
        console.log(_data);

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Account_integration/transaction/save_water_billing",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save_water_billing'))

        });
    };



    var showNotification=function(obj){
        PNotify.removeAll(); //remove all notifications
        new PNotify({
            title:  obj.title,
            text:  obj.msg,
            type:  obj.stat
        });
    };



    var showSpinningProgress=function(e){
        $(e).toggleClass('disabled');
        $(e).find('span').toggleClass('glyphicon glyphicon-refresh spinning');
    };

    var clearFields=function(f){
        $('input,textarea',f).val('');
        $(f).find('select').select2('val',null);
        $(f).find('input:first').focus();
    };

    var showList=function(b){
        if(b){
            $('#div_account_year_list').show();
            $('#div_account_year_fields').hide();
        }else{
            $('#div_account_year_list').hide();
            $('#div_account_year_fields').show();
        }
    };











});




</script>


</body>


</html>