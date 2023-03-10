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
    <!--/twitter typehead-->
    <link href="assets/plugins/twittertypehead/twitter.typehead.css" rel="stylesheet">
    <style>
        #tbl_items td,#tbl_items tr,#tbl_items th{
            table-layout: fixed;
            border: 1px solid gray;
            border-collapse: collapse;
        }
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
        .dropdown-menu > .active > a,.dropdown-menu > .active > a:hover{
            background-color: dodgerblue;
        }
        @keyframes spin {
            from { transform: scale(1) rotate(0deg); }
            to { transform: scale(1) rotate(360deg); }
        }
        @-webkit-keyframes spin2 {
            from { -webkit-transform: rotate(0deg); }
            to { -webkit-transform: rotate(360deg); }
        }
        .custom_frame{
            border: 1px solid lightgray;
            margin: 1% 1% 1% 1%;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }
        @media screen and (max-width: 480px) {
            table{
                min-width: 800px;
            }
            .dataTables_filter{
                min-width: 800px;
            }
            .dataTables_info{
                min-width: 800px;
            }
            .dataTables_paginate{
                float: left;
                width: 100%;
            }
        }
        .numeric{
            text-align: right;
            width: 60%;
        }
        #btn_new {
            text-transform: capitalize !important;
        }
        .modal-body {
            text-transform: bold;
        }
        .boldlabel {
            font-weight: bold;
        }
        .inlinecustomlabel {
            font-weight: bold;
        }
        .form-group {
            padding-bottom: 3px;
        }
        #is_tax_exempt {
            width:23px;
            height:23px;
        }
        /*.modal-body {
            padding-left:0px !important;
        }*/
        #label {
            text-align:left;
        }
        .select2-dropdown{
            z-index: 999999;
        }
        .form-group {
            padding:0;
            margin:5px;
        }
        .input-group {
            padding:0;
            margin:0;
        }
        textarea {
            resize: none;
        }
        .modal-body p {
            margin-left: 20px !important;
        }
        #img_user {
            padding-bottom: 15px;
        }
    </style>
</head>
<body class="animated-content"  style="font-family: tahoma;">
<?php echo $_top_navigation; ?>
<div id="wrapper">
<div id="layout-static">
<?php echo $_side_bar_navigation;
?>
<div class="static-content-wrapper white-bg">
<div class="static-content"  >
<div class="page-content"><!-- #page-content -->
<ol class="breadcrumb"  style="margin-bottom: 10px;">
    <li><a href="Dashboard">Dashboard</a></li>
    <li><a href="Sales_order">Sales Order</a></li>
</ol>
<div class="container-fluid"">
<div data-widget-group="group1">
<div class="row">
<div class="col-md-12">
<div id="div_user_list">
    <div class="panel panel-default" style="border: 4px solid #2980b9;">
<!--         <a data-toggle="collapse" data-parent="#accordionA" href="#collapseTwo"><div class="panel-heading" style="background: #2ecc71;border-bottom: 1px solid lightgrey;"><b style="color: white; font-size: 12pt;"><i class="fa fa-bars"></i> Sales Order</b></div></a> -->
        <div class="panel-body table-responsive" >
        <h2 class="h2-panel-heading">Sales Order</h2><hr>
            <table id="tbl_sales_order"  class="table table-striped" cellspacing="0" width="100%">
                <thead class="">
                <tr>
                    <th>&nbsp;&nbsp;</th>
                    <th>SO #</th>
                    <th>Order Date</th>
                    <th>Customer</th>
                    <th style="width: 25%;">Remarks</th>
                    <th>Status</th>
                    <th><center>Action</center></th>
                    <th>id</th>                    
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="div_user_fields" style="display: none;">
    <div class="panel panel-default" style="border: 4px solid #2980b9;border-radius: 8px;">
<!-- <div class="panel-heading">
    <h2>Sales Order</h2>
    <div class="panel-ctrls" data-actions-container=""></div>
</div> -->
<div class="panel-body">
    <h2 class="h2-panel-heading">SO # : <span id="span_so_no">SO-XXXX</span></h2><hr>
    <div class="row" style="padding: 1%;margin-top: 0%;font-family: "Source Sans Pro", "Segoe UI", "Droid Sans", Tahoma, Arial, sans-serif">
        <form id="frm_sales_order" role="form" class="form-horizontal">
            <div>
                <div class="row">
                    <div class="col-sm-5">
                       <b>* </b>  Department :<br />
                        <select name="department" id="cbo_departments" data-error-msg="Department is required." required>
                            <option value="0">[ Create New Department ]</option>
                            <?php foreach($departments as $department){ ?>
                                <option value="<?php echo $department->department_id; ?>"><?php echo $department->department_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        SalesPerson :<br/>
                        <select name="salesperson_id" id="cbo_salesperson">
                            <option value="0">[ Create New Salesperson ]</option>
                            <?php foreach($salespersons as $salesperson){ ?>
                                <option value="<?php echo $salesperson->salesperson_id; ?>"><?php echo $salesperson->acr_name.' - '.$salesperson->fullname; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-sm-3 ">
                        SO # :<br />
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-code"></i>
                            </span>
                            <input type="text" name="slip_no" class="form-control" placeholder="SO-YYYYMMDD-XXX" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                       <b>* </b>  Customer : <br />
                        <select name="customer" id="cbo_customers" data-error-msg="Customer is required." required>
                            <option value="0">[ Create New Customer ]</option>
                            <?php $customers = $this->db->where('is_deleted',FALSE); ?>
                            <?php $customers = $this->db->where('is_active',TRUE);?>
                            <?php $customers = $this->db->get('customers');?>
                            <?php foreach($customers->result() as $customer){ ?>
                            <option value="<?php echo $customer->customer_id; ?>" data-customer_type="<?php echo $customer->customer_type_id; ?>"><?php echo $customer->customer_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                        <div class="col-sm-4">
                            Customer Type :<br>
                            <select name="customer_type_id" id="cbo_customer_type">
                                <option value="0">None</option>
                                <?php foreach($customer_type as $customer_type){ ?>
                                    <option value="<?php echo $customer_type->customer_type_id; ?>"><?php echo $customer_type->customer_type_name?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <div class="col-sm-3">
                        Order Date : <br />
                        <div class="input-group">
                            <input type="text" id="order_default" name="date_order" class="date-picker form-control" value="<?php echo date("m/d/Y"); ?>" placeholder="Date Order" data-error-msg="Please set the date this items are ordered!" required>
                                <span class="input-group-addon">
                                     <i class="fa fa-calendar"></i>
                                </span>
                        </div>
                    </div>
            </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="row" style="padding: 1%;margin-top: 0px;padding-top:0px;">
        <label class="control-label" style="font-family: Tahoma;"><strong>Enter PLU or Search Item :</strong></label>
        <button id="refreshproducts" class="btn-primary btn pull-right" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"><span class=""></span>  Refresh</button>
        <div id="custom-templates">
            <input class="typeahead" id="typeaheadsearch" type="text" placeholder="Enter PLU or Search Item">
        </div><br />
            <form id="frm_items">
                <div class="table-responsive">
                        <table id="tbl_items" class="table table-striped"  cellspacing="0" width="100%" style="font-font:tahoma;">
                        <thead class="">
                        <tr>
                            <!-- DISPLAY -->
                            <th width="10%">Qty </th>
                            <th width="10%">UM </th>
                            <th width="25%">Item </th>
                            <th width="15%" style="text-align: right;">Unit Price </th>
                            <th width="10%" style="text-align: right;">Discount % </th>
                            <!-- display:none; -->
                            <th style="display:none;" width="10%">Total line Discount </th> <!-- total discount -->
                            <th style="display:none;" width="10%">Tax % </th>
                            <!-- DISPLAY -->
                            <th width="15%" style="text-align: right">Gross</th>
                            <th width="15%" style="text-align: right">Total</th>
                            <!-- display:none;  -->
                            <th style="display:none;"  width="10%">Vat Input(Total Line Tax) </th> <!-- vat input -->
                            <th style="display:none;"  width="10%">Net of Vat (Price w/out Tax) </th> <!-- net of vat -->
                            <td style="display:none;" width="10%">Item ID </td><!-- product id -->
                            <th width="5%"><center>Action</center></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8" style="height: 50px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align: right;">Discount %</td>
                                <td align="right" colspan="1" id="" color="red">
                                <input id="txt_overall_discount" name="total_overall_discount" type="text" class="numeric form-control" value="0.00" />
                                <input id="txt_overall_discount_amount" name="total_overall_discount_amount" type="hidden" class="numeric form-control" value="0.00" />
                                </td>

                                <td>Total After Discount:</td>
                                <td id="td_total_after_discount" style="text-align: right">0.00</td>

                                <td style="text-align: right;" colspan="2">Total Before Tax:</td>
                                <td id="td_total_before_tax" style="text-align: right">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong><i class="glyph-icon icon-star"></i> Tax :</strong></td>
                                <td align="right" colspan="1" id="td_tax" color="red">0.00</td>
                                <td colspan="2"  style="text-align: right;"><strong><i class="glyph-icon icon-star"></i> Total After Tax :</strong></td>
                                <td align="right" colspan="1" id="td_after_tax" color="red">0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </form>
                <div class="row">
                        <div class="col-lg-12">
                            <label><strong>Remarks :</strong></label><br />
                            <textarea name="remarks" id="remarks" class="form-control" placeholder="Remarks"></textarea>
                        </div>
                </div>
            <br />
            <div class="row" style="display:none;">
                <div class="col-lg-4 col-lg-offset-8">
                    <div class="table-responsive">
                        <table id="tbl_sales_order_summary" class="table invoice-total" style="font-family: tahoma;">
                            <tbody>
                            <tr>
                                <td>Discount :</td>
                                <td align="right">0.00</td>
                            </tr>
                            <tr>
                                <td>Total before Tax :</td>
                                <td align="right">0.00</td>
                            </tr>
                            <tr>
                                <td>Tax :</td>
                                <td align="right">0.00</td>
                            </tr>
                            <tr>
                                <td><strong>Total After Tax :</strong></td>
                                <td align="right"><b>0.00</b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>
<div class="panel-footer">
    <div class="row">
        <div class="col-sm-12">
            <button id="btn_save" class="btn-primary btn" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"><span class=""></span>  Save Changes</button>
            <button id="btn_cancel" class="btn-default btn" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"">Cancel</button>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div> <!-- .container-fluid -->
</div> <!-- #page-content -->
</div>
<div id="modal_confirmation" class="modal fade" tabindex="-1" role="dialog"><!--modal-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content"><!---content--->
            <div class="modal-header">
                <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" style="color:white;"><span id="modal_mode"> </span>Confirm Deletion</h4>
            </div>
            <div class="modal-body">
                <p id="modal-body-message">Are you sure ?</p>
            </div>
            <div class="modal-footer">
                <button id="btn_yes" type="button" class="btn btn-danger" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">Yes</button>
                <button id="btn_close" type="button" class="btn btn-default" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">No</button>
            </div>
        </div><!---content---->
    </div>
</div><!---modal-->
<div id="modal_new_customer" class="modal fade" tabindex="-1" role="dialog"><!--modal-->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#2ecc71;">
                <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" style="color:#ecf0f1;"><span id="modal_mode"> </span>New Customer</h4>
            </div>
            <div class="modal-body">
                <form id="frm_customer">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="col-md-12">
                                <div class="col-md-4" id="label">
                                     <label class="control-label boldlabel" style="text-align:right;"><font color="red"><b>*</b></font> Customer Name :</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-users"></i>
                                        </span>
                                        <input type="text" name="customer_name" class="form-control" placeholder="Customer Name" data-error-msg="Customer Name is required!" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4" id="label">
                                     <label class="control-label boldlabel" style="text-align:right;"><font color="red"><b>*</b></font> Contact Person :</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-users"></i>
                                        </span>
                                        <input type="text" name="contact_name" class="form-control" placeholder="Contact Person" data-error-msg="Contact Person is required!" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4" id="label">
                                     <label class="control-label boldlabel" style="text-align:right;"><font color="red"><b>*</b></font> Address :</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-home"></i>
                                         </span>
                                         <textarea name="address" class="form-control" data-error-msg="Supplier address is required!" placeholder="Address" required ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4" id="label">
                                     <label class="control-label boldlabel" style="text-align:right;">Email Address :</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-envelope-o"></i>
                                        </span>
                                        <input type="text" name="email_address" class="form-control" placeholder="Email Address">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4" id="label">
                                     <label class="control-label boldlabel" style="text-align:right;">Landline :</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-phone"></i>
                                        </span>
                                        <input type="text" name="landline" id="landline" class="form-control" placeholder="Landline">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4" id="label">
                                     <label class="control-label boldlabel" style="text-align:right;">Contact No :</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-mobile"></i>
                                        </span>
                                        <input type="text" name="contact_no" id="mobile_no" class="form-control" placeholder="Mobile No">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4" id="label">
                                     <label class="control-label boldlabel" style="text-align:right;">TIN :</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-file-code-o"></i>
                                        </span>
                                        <input type="text" name="tin_no" id="tin_no" class="form-control" placeholder="TIN">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-4" id="label">
                                     <label class="control-label boldlabel" style="text-align:right;">Customer Type :</label>
                                </div>
                                <div class="col-md-8" style="padding: 0px;">
                                <select name="customer_type_id_create" id="cbo_customer_type_create" style="width: 100%">
                                    <option value="0">None</option>
                                    <?php foreach($customer_type_create as $customer_type){ ?>
                                        <option value="<?php echo $customer_type->customer_type_id; ?>"><?php echo $customer_type->customer_type_name?></option>
                                    <?php } ?>
                                </select>
                                </div>
                            </div>



                        </div>
                        <div class="col-md-4">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <label class="control-label boldlabel" style="text-align:left;padding-top:10px;"><i class="fa fa-user" aria-hidden="true" style="padding-right:10px;"></i>Customer's Photo</label>
                                    <hr style="margin-top:0px !important;height:1px;background-color:black;">
                                </div>
                                <div style="width:100%;height:350px;border:2px solid #34495e;border-radius:5px;">
                                    <center>
                                        <img name="img_user" id="img_user" src="assets/img/anonymous-icon.png" height="140px;" width="140px;"></img>
                                    </center>
                                    <hr style="margin-top:0px !important;height:1px;background-color:black;">
                                    <center>
                                         <button type="button" id="btn_browse" style="width:150px;margin-bottom:5px;" class="btn btn-primary">Browse Photo</button>
                                         <button type="button" id="btn_remove_photo" style="width:150px;" class="btn btn-danger">Remove</button>
                                         <input type="file" name="file_upload[]" class="hidden">
                                    </center> 
                                </div>
                            </div>   
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn_create_customer" type="button" class="btn btn-primary"  style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"><span class=""></span> Create</button>
                <button id="btn_close_customer" type="button" class="btn btn-default" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">Cancel</button>
            </div>
        </div><!---content---->
    </div>
</div><!---modal-->
<div id="modal_new_salesperson" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#2ecc71;">
                <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                <h4 id="salesperson_title" class="modal-title" style="color: #ecf0f1;"><span id="modal_mode"></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="frm_salesperson" role="form">
                        <div class="">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-xs-12 col-md-4 control-label "><strong><b>* </b>  Salesperson Code :</strong></label>
                                    <div class="col-xs-12 col-md-8">
                                        <input type="text" name="salesperson_code" class="form-control" placeholder="Salesperson Code" data-error-msg="Salesperson Code is required!" required>
                                    </div>
                                </div>
                            </div><br><br>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-xs-12 col-md-4 control-label "><strong><b>* </b>  First name :</strong></label>
                                    <div class="col-xs-12 col-md-8">
                                        <input type="text" name="firstname" class="form-control" placeholder="Firstname" data-error-msg="Firstname is required!" required>
                                    </div>
                                </div>
                            </div><br><br>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-xs-12 col-md-4 control-label "><strong>&nbsp;&nbsp;Middle name :</strong></label>
                                    <div class="col-xs-12 col-md-8">
                                        <input type="text" name="middlename" class="form-control" placeholder="Middlename">
                                    </div>
                                </div>
                            </div><br><br>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-xs-12 col-md-4 control-label "><strong><b>* </b>  Last name :</strong></label>
                                    <div class="col-xs-12 col-md-8">
                                        <input type="text" name="lastname" class="form-control" placeholder="Lastname" data-error-msg="Lastname is required!" required>
                                    </div>
                                </div>
                            </div><br><br>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-xs-12 col-md-4 control-label "><strong>Contact Number :</strong></label>
                                    <div class="col-xs-12 col-md-8">
                                        <input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="Contact Number">
                                    </div>
                                </div>
                            </div><br><br>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-xs-12 col-md-4 control-label "><strong><b>* </b>  Department :</strong></label>
                                    <div class="col-xs-12 col-md-8">
                                        <select name="department_id" id="cbo_department" class="form-control" data-error-msg="Department is required!" required>
                                            <option value="0">[ Create New Department ]</option>
                                            <?php foreach($departments as $department) { ?>
                                                <option value="<?php echo $department->department_id; ?>"><?php echo $department->department_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div><br><br>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-xs-12 col-md-4 control-label "><strong>TIN Number:</strong></label>
                                    <div class="col-xs-12 col-md-8">
                                        <input type="text" name="tin_no" id="tin_no" class="form-control" placeholder="TIN Number">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn_create_salesperson" class="btn btn-primary" name="btn_save">Save</button>
                <button id="btn_close_salesperson" class="btn btn-default">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div id="modal_new_department" class="modal fade" tabindex="-1" role="dialog"><!--modal-->
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header ">
                <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" style="color: white;"><span id="modal_mode"> </span>New Department</h4>
            </div>
            <div class="modal-body">
                <form id="frm_department_new">
                    <div class="form-group">
                        <label><b>* </b> Department :</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-users"></i>
                            </span>
                            <input type="text" name="department_name" class="form-control" placeholder="Department" data-error-msg="Department name is required." required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Department Description :</label>
                        <textarea name="department_desc" class="form-control"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn_create_department" type="button" class="btn btn-primary"  style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"><span class=""></span> Create</button>
                <button id="btn_close_department" type="button" class="btn btn-default" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">Cancel</button>
            </div>
        </div>
    </div>
</div><!---modal-->
<div id="modal_new_department_sp" class="modal fade" tabindex="-1" role="dialog"><!--modal-->
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header ">
                <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" style="color: white;"><span id="modal_mode"> </span>New Department</h4>
            </div>
            <div class="modal-body">
                <form id="frm_department_new_sp">
                    <div class="form-group">
                        <label><font color="red">*</font> Department :</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-users"></i>
                            </span>
                            <input type="text" name="department_name" class="form-control" placeholder="Department" data-error-msg="Department name is required." required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Department Description :</label>
                        <textarea name="department_desc" class="form-control"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn_create_department_sp" type="button" class="btn btn-primary"  style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;"><span class=""></span> Create</button>
                <button id="btn_close_department_sp" type="button" class="btn btn-default" data-dismiss="modal" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;">Cancel</button>
            </div>
        </div>
    </div>
</div><!---modal-->
<footer role="contentinfo">
    <div class="clearfix">
        <ul class="list-unstyled list-inline pull-left">
            <li><h6 style="margin: 0;">&copy; 2016 JDEV OFFICE SOLUTION - </h6></li>
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
<script type="text/javascript" src="assets/plugins/datatables/ellipsis.js"></script>
<!-- a range use moment.js same as full calendar plugin -->
<script src="assets/plugins/fullcalendar/moment.min.js"></script>
<!-- Data picker -->
<script src="assets/plugins/datapicker/bootstrap-datepicker.js"></script>
<!-- Select2 -->
<script src="assets/plugins/select2/select2.full.min.js"></script>
<!-- twitter typehead -->
<script src="assets/plugins/twittertypehead/handlebars.js"></script>
<script src="assets/plugins/twittertypehead/bloodhound.min.js"></script>
<script src="assets/plugins/twittertypehead/typeahead.bundle.min.js"></script>
<script src="assets/plugins/twittertypehead/typeahead.jquery.min.js"></script>
<!-- touchspin -->
<script type="text/javascript" src="assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js"></script>
<!-- numeric formatter -->
<script src="assets/plugins/formatter/autoNumeric.js" type="text/javascript"></script>
<script src="assets/plugins/formatter/accounting.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
    var dt; var _txnMode; var _selectedID; var _selectRowObj;
    var _cboDepartments; var _cboDepartment; var _cboSalesperson; var _cboCustomers; var _lookUpPrice; var products;
    var _line_unit; var _cboCustomerType;
    var _cboCustomerTypeCreate;
 
    /*var oTableItems={
        qty : 'td:eq(0)',
        unit_price : 'td:eq(3)',
        discount : 'td:eq(4)',
        total_line_discount : 'td:eq(5)',
        tax : 'td:eq(6)',
        total : 'td:eq(7)', //Total
        vat_input : 'td:eq(8)',
        net_vat : 'td:eq(9)' //Before Tax
    };
    var oTableDetails={
        discount : 'tr:eq(0) > td:eq(1)',
        before_tax : 'tr:eq(1) > td:eq(1)',
        so_tax_amount : 'tr:eq(2) > td:eq(1)',
        after_tax : 'tr:eq(3) > td:eq(1)'
    };*/
    var oTableItems={
        qty : 'td:eq(0)',
        unit_value: 'td:eq(1)',
        unit_identifier : 'td:eq(2)',
        unit_price : 'td:eq(3)',
        discount : 'td:eq(4)',
        total_line_discount : 'td:eq(5)',
        tax : 'td:eq(6)',
        gross : 'td:eq(7)',
        total : 'td:eq(8)',
        vat_input : 'td:eq(9)',
        net_vat : 'td:eq(10)',
        item_id : 'td:eq(11)',
        bulk_price : 'td:eq(13)',
        retail_price : 'td:eq(14)'
    };
    var oTableDetails={
        discount : 'tr:eq(0) > td:eq(1)',
        before_tax : 'tr:eq(1) > td:eq(1)',
        so_tax_amount : 'tr:eq(2) > td:eq(1)',
        after_tax : 'tr:eq(3) > td:eq(1)'
    };
    var initializeControls=function(){
        dt=$('#tbl_sales_order').DataTable({
            "dom": '<"toolbar">frtip',
            "bLengthChange":false,
            "pageLength":15,
            "order": [[ 7, "desc" ]],
            "ajax" : "Sales_order/transaction/list",
            "columns": [
                {
                    "targets": [0],
                    "class":          "details-control",
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ""
                },
                { targets:[1],data: "so_no" },
                { targets:[2],data: "date_order" },
                { targets:[3],data: "customer_name" },
                { targets:[4],data: "remarks" ,render: $.fn.dataTable.render.ellipsis(80) },
                { targets:[5],data: "order_status" },
                {
                    targets:[6],
                    render: function (data, type, full, meta){
                        var btn_edit='<button class="btn btn-primary btn-sm" name="edit_info"  style="margin-left:0px;" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i> </button>';
                        var btn_trash='<button class="btn btn-red btn-sm" name="remove_info" style="margin-right:0px;" data-toggle="tooltip" data-placement="top" title="Move to trash"><i class="fa fa-trash-o"></i> </button>';
                        return '<center>'+btn_edit+"&nbsp;"+btn_trash+'</center>';
                    }
                },
                { targets:[7],data: "sales_order_id", visible:false}
            ]
        }); 
        var createToolBarButton=function(){
            var _btnNew='<button class="btn btn-primary"  id="btn_new" style="text-transform: none;font-family: Tahoma, Georgia, Serif;" data-toggle="modal" data-target="" data-placement="left" title="New Sales Order" >'+
                '<i class="fa fa-plus"></i> New Sales Order</button>';
                $("div.toolbar").html(_btnNew);
        }();
        _cboCustomers=$("#cbo_customers").select2({
            placeholder: "Please select customer.",
            allowClear: true
        });
        /*_lookUpPrice = $('#cboLookupPrice').select2({
            allowClear: false
        });
        _lookUpPrice.select2('val',1);*/
        _cboDepartments=$("#cbo_departments").select2({
            placeholder: "Please select Department.",
            allowClear: true
        });
        _cboDepartment=$("#cbo_department").select2({
            placeholder: "Please select Department.",
            allowClear: true
        });
        _cboSalesperson=$("#cbo_salesperson").select2({
            placeholder: "Please select sales person.",
            allowClear: true
        });
        _cboSalesperson.select2('val',null);
        /*_productType = $('#cbo_prodType').select2({
            placeholder: "Please select Product Type",
            allowClear: false
        });*/


        _cboCustomerType=$("#cbo_customer_type").select2({
            allowClear: false
        });

        _cboCustomerTypeCreate=$("#cbo_customer_type_create").select2({
            allowClear: false
        });

        $('.numeric').autoNumeric('init');
        $('#mobile_no').keypress(validateNumber);
        $('#landline').keypress(validateNumber);
        _cboDepartments.select2('val',null);
        _cboDepartment.select2('val',null);
        _cboCustomers.select2('val',null);
        $('.date-picker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
        });
         _cboSalesperson.on('select2:select',function(e){
            var i=$(this).select2('val');
            if(i == 0) {
                //clearFields($('#modal_new_salesperson').find('form'));
                clearFields($('#modal_new_salesperson'));
                _cboSalesperson.select2('val',null);
                $('#modal_new_salesperson').modal('show');
                $('#salesperson_title').text('Create New Salesperson');
            }
        });
         $('#btn_close_salesperson').on('click',function(){
            $('#modal_new_salesperson').modal('hide');
        });
         $('#btn_create_salesperson').click(function(){
            var btn=$(this);
            if(validateRequiredFields($('#frm_salesperson'))){
                var data=$('#frm_salesperson').serializeArray();
                $.ajax({
                    "dataType":"json",
                    "type":"POST",
                    "url":"Salesperson/transaction/create",
                    "data":data,
                    "beforeSend" : function(){
                        showSpinningProgress(btn);
                    }
                }).done(function(response){
                    showNotification(response);
                    $('#modal_new_salesperson').modal('hide');
                    var _salesperson=response.row_added[0];
                    $('#cbo_salesperson').append('<option value="'+_salesperson.salesperson_id+'" selected>'+ _salesperson.salesperson_code + ' - ' +_salesperson.fullname+'</option>');
                    $('#cbo_salesperson').select2('val',_salesperson.salesperson_id);
                }).always(function(){
                    showSpinningProgress(btn);
                });
            }
        });


         $('#refreshproducts').click(function(){
            getproduct().done(function(data){
                products.clear();
                products.local = data.data;
                products.initialize(true);
                    showNotification({title:"Success !",stat:"success",msg:"Products List successfully updated."});
            }).always(function(){
                $('#typeaheadsearch').val('');
                });
         });
        $('#custom-templates .typeahead').keypress(function(event){
            if (event.keyCode == 13) {
                $('.tt-suggestion:first').click();
            }
        });
        /*var products = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace(''),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                cache: false,
                url: 'Sales_order/transaction/list/',
                replace: function(url, uriEncodedQuery) {
                    //var prod_type=$('#cbo_prodType').select2('val');
                    return url + '?description='+uriEncodedQuery;
                }
            }
        });*/
        products = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('product_code','product_desc','product_desc1'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local : products
        });
        var _objTypeHead=$('#custom-templates .typeahead');
        _objTypeHead.typeahead(null, {
                name: 'products',
                display: 'product_code',
                source: products,
                templates: {
                    header: [
                        '<table class="tt-head">'+
                            '<tr>'+
                                '<td width=10%" style="padding-left: 1%;">'+
                                    '<b>PLU</b>'+
                                '</td>'+
                                '<td width="25%" align="left">'+
                                    '<b>Description</b>'+
                                '</td>'+
                                '<td width="5%"  style="padding-right: 2%;text-align:right;">'+
                                    '<b>On Hand</b>'+
                                '</td>'+
                                '<td width="5%" align="right" style="padding-right: 2%;">'+
                                    '<b>SRP</b>'+
                                '</td>'+
                            '</tr>'+
                        '</table>'
                    ].join('\n'),
                    suggestion: Handlebars.compile(
                        '<table class="tt-items">'+
                            '<tr>'+
                                '<td width="10%" style="padding-left: 1%">'+
                                    '{{product_code}}'+
                                '</td>'+
                                '<td width="25%" align="left">'+
                                    '{{product_desc}}'+
                                '</td>'+
                                '<td width="5%"  style="padding-right: 2%;text-align:right;">'+
                                    '{{CurrentQty}}'+
                                '</td>'+
                                '<td width="5%" align="right" style="padding-right: 2%;">'+
                                    '{{sale_price}}'+
                                '</td>'+
                            '</tr>'+
                        '</table>'
                        )
                }
            }).on('keyup', this, function (event) {
                if (_objTypeHead.typeahead('val') == '')
                    return false;
                if (event.keyCode == 13) {
                    //$('.tt-suggestion:first').click();
                    _objTypeHead.typeahead('close');
                    _objTypeHead.typeahead('val','');
                }
            }).bind('typeahead:select', function(ev, suggestion) {
                //var tax_rate=suggestion.tax_rate; // tax rate is based the tax type set to selected product
                //var _defLookUp=_lookUpPrice.select2('val');
                /*if(_defLookUp=="2"){
                    total=getFloat(suggestion.distributor_price);
                }else if(_defLookUp=="3"){
                    total=getFloat(suggestion.dealer_price);
                }
                else if(_defLookUp=="4"){
                    total=getFloat(suggestion.public_price);
                }
                else if(_defLookUp=="5"){
                    total=getFloat(suggestion.discounted_price);
                }
                else if(_defLookUp=="6"){
                    total=getFloat(suggestion.sale_price);
                }else{
                    total=getFloat(suggestion.sale_price);
                }*/
                //alert(suggestion.sale_price);
                if(!(checkProduct(suggestion.product_id))){ // Checks if item is already existing in the Table of Items for invoice
                    showNotification({title: suggestion.product_desc,stat:"error",msg: "Item is Already Added."});
                    return;
                }


                if(getFloat(suggestion.CurrentQty) <= 0){
                    showNotification({title: suggestion.product_desc,stat:"info",msg: "This item is currently out of stock.<br>Continuing will result to negative inventory."});
                }else if(getFloat(suggestion.CurrentQty) <= getFloat(suggestion.product_warn) ){
                    showNotification({title: suggestion.product_desc ,stat:"info",msg:"This item has low stock remaining.<br>It might result to negative inventory."});
                }
                var tax_rate=suggestion.tax_rate; //base on the tax rate set to current product
                //choose what purchase cost to be use
                _customer_type_ = _cboCustomerType.val();
                var sale_price=0.00;

                if(_customer_type_ == '' || _customer_type_ == 0){
                    sale_price=suggestion.sale_price;
                }else if(_customer_type_ == '1' ){ // DISCOUNTED CUSTOMER TYPE
                    sale_price=suggestion.discounted_price;
                }else if(_customer_type_ == '2' ){ // DEALER CUSTOMER TYPE
                    sale_price=suggestion.dealer_price;
                }else if(_customer_type_ == '3' ){ // DISTRIBUTOR CUSTOMER TYPE
                    sale_price=suggestion.distributor_price;
                }else if(_customer_type_ == '4' ){ // PUBLIC CUSTOMER TYPE
                    sale_price=suggestion.public_price;
                }else{
                    sale_price=suggestion.sale_price;
                }

                var total=getFloat(sale_price);
                var net_vat=0;
                var vat_input=0;
                var bulk_price = 0;
                var retail_price = 0;
                var a = ''; 
                if(suggestion.is_tax_exempt=="0"){ //not tax excempt
                    net_vat=total/(1+(getFloat(tax_rate)/100));
                    vat_input=total-net_vat;
                }else{
                    tax_rate=0;
                    net_vat=total;
                    vat_input=0;
                }
                bulk_price = sale_price;

                if(suggestion.is_bulk == 1){
                    retail_price = getFloat(sale_price) / getFloat(suggestion.child_unit_desc);

                }else if (suggestion.is_bulk== 0){
                    retail_price = 0;
                }
                if(suggestion.primary_unit == 1){ 
                        suggis_parent = 1;
                        temp_inv_price = sale_price;
                }else{ 
                        suggis_parent = 0;
                        temp_inv_price = retail_price;
                        net_vat = getFloat(net_vat) / getFloat(suggestion.child_unit_desc);
                        vat_input = getFloat(vat_input) / getFloat(suggestion.child_unit_desc);
                }                
                $('#tbl_items > tbody').append(newRowItem({
                    so_qty : "1",
                    product_code : suggestion.product_code,
                    size : suggestion.size,
                    product_id: suggestion.product_id,
                    product_desc : suggestion.product_desc,
                    so_line_total_discount : "0.00",
                    tax_exempt : false,
                    so_tax_rate : tax_rate,
                    so_gross : temp_inv_price,
                    so_price : temp_inv_price,
                    so_discount : "0.00",
                    tax_type_id : null,
                    so_line_total_price : temp_inv_price,
                    so_non_tax_amount: net_vat,
                    so_tax_amount:vat_input,
                    batch_no : suggestion.batch_no,
                    exp_date : suggestion.exp_date,
                        bulk_price: bulk_price,
                        retail_price: retail_price,
                        is_bulk: suggestion.is_bulk,
                        parent_unit_id : suggestion.parent_unit_id,
                        child_unit_id : suggestion.child_unit_id,
                        child_unit_name : suggestion.child_unit_name,
                        parent_unit_name : suggestion.parent_unit_name,
                        is_parent: suggis_parent,
                        primary_unit:suggestion.primary_unit,
                        a:a
                }));
                changetxn ='active';
                _line_unit=$('.line_unit'+a).select2({
                minimumResultsForSearch: -1
                });
 
                reInitializeNumeric();
                reComputeTotal();
        });
        $('div.tt-menu').on('click','table.tt-suggestion',function(){
            _objTypeHead.typeahead('val','');
        });
        $("input#touchspin4").TouchSpin({
            verticalbuttons: true,
            verticalupclass: 'fa fa-fw fa-plus',
            verticaldownclass: 'fa fa-fw fa-minus'
        });
    }();
    var bindEventHandlers=(function(){
        var detailRows = [];
        $('#tbl_sales_order tbody').on( 'click', 'tr td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = dt.row( tr );
            var idx = $.inArray( tr.attr('id'), detailRows );
            if ( row.child.isShown() ) {
                tr.removeClass( 'details' );
                row.child.hide();
                // Remove from the 'open' array
                detailRows.splice( idx, 1 );
            }
            else {
                tr.addClass( 'details' );
                //console.log(row.data());
                var d=row.data();
                $.ajax({
                    "dataType":"html",
                    "type":"POST",
                    "url":"Templates/layout/sales-order/"+ d.sales_order_id+"?type=fullview",
                    "beforeSend" : function(){
                        row.child( '<center><br /><img src="assets/img/loader/ajax-loader-lg.gif" /><br /><br /></center>' ).show();
                    }
                }).done(function(response){
                    row.child( response,'no-padding' ).show();
                    // Add to the 'open' array
                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                });
            }
        } );
        //loads modal to create new department
        _cboDepartments.on('select2:select', function(){
            if (_cboDepartments.val() == 0) {
                _cboDepartments.select2('val',null);
                clearFields($('#frm_department_new'));
                $('#modal_new_department').modal('show');
                //$('#modal_new_salesperson').modal('hide');
            }
        });
        _cboDepartment.on('select2:select', function(){
            if (_cboDepartment.val() == 0) {
                _cboDepartment.select2('val',null);
                clearFields($('#frm_department_new_sp'));
                $('#modal_new_department_sp').modal('show');
                $('#modal_new_salesperson').modal('hide');
            }
        });


        _cboCustomers.on("select2:select", function (e) {
            var i=$(this).select2('val');
                if(i==0){ 
                clearFields($('#frm_customer'));
                _cboCustomers.select2('val',null);
                _cboCustomerTypeCreate.select2('val',0);
                $('#modal_new_customer').modal('show');
                 }
            var obj_customers=$('#cbo_customers').find('option[value="' + i + '"]');
            $('#cbo_customer_type').select2('val',obj_customers.data('customer_type'));
            if(i==0){ _cboCustomerType.select2('val',0); }
        });
        $('#btn_close_department').on('click', function(){
            $('#modal_new_department').modal('hide');
            _cboDepartments.select2('val',null);
        });
        $('#btn_close_department_sp').on('click', function(){
            $('#modal_new_department_sp').modal('hide');
            $('#modal_new_salesperson').modal('show');
            _cboDepartment.select2('val',null);
        });
        //create new department
        $('#btn_create_department').click(function(){
            var btn=$(this);
            if(validateRequiredFields($('#frm_department_new'))){
                var data=$('#frm_department_new').serializeArray();
                $.ajax({
                    "dataType":"json",
                    "type":"POST",
                    "url":"Departments/transaction/create",
                    "data":data,
                    "beforeSend" : function(){
                        showSpinningProgress(btn);
                    }
                }).done(function(response){
                    showNotification(response);
                    $('#modal_new_department').modal('hide');
                    var _department=response.row_added[0];
                    $('#cbo_departments').append('<option value="'+_department.department_id+'" selected>'+_department.department_name+'</option>');
                    $('#cbo_departments').select2('val',_department.department_id);
                }).always(function(){
                    showSpinningProgress(btn);
                });
            }
        });
        $('#btn_create_department_sp').click(function(){
            var btn=$(this);
            if(validateRequiredFields($('#frm_department_new_sp'))){
                var data=$('#frm_department_new_sp').serializeArray();
                $.ajax({
                    "dataType":"json",
                    "type":"POST",
                    "url":"Departments/transaction/create",
                    "data":data,
                    "beforeSend" : function(){
                        showSpinningProgress(btn);
                    }
                }).done(function(response){
                    showNotification(response);
                    $('#modal_new_department_sp').modal('hide');
                    $('#modal_new_salesperson').modal('show');
                    var _department=response.row_added[0];
                    $('#cbo_department').append('<option value="'+_department.department_id+'" selected>'+_department.department_name+'</option>');
                    $('#cbo_department').select2('val',_department.department_id);
                }).always(function(){
                    showSpinningProgress(btn);
                });
            }
        });
        $('#tbl_sales_order tbody').on('click','#btn_email',function(){
            _selectRowObj=$(this).parents('tr').prev();
            var d=dt.row(_selectRowObj).data();
            var btn=$(this);
            $.ajax({
                "dataType":"json",
                "type":"POST",
                "url":"Email/send/po/"+ d.sales_order_id,
                "data": {email:$(this).data('supplier-email')},
                "beforeSend" : function(){
                    showSpinningProgress(btn);
                }
            }).done(function(response){
                showNotification(response);
                dt.row(_selectRowObj).data(response.row_updated[0]).draw();
            }).always(function(){
                showSpinningProgress(btn);
            });
        });
        $('#btn_new').click(function(){
            _txnMode="new";
            //$('.toggle-fullscreen').click();
            //$('#cbo_prodType').select2('val', 3);
            $('#cbo_salesperson').select2('val',null);
            $('#order_default').datepicker('setDate', 'today');
            clearFields($('#frm_sales_order'));
            $('#tbl_items tbody').html('');
            $('#cbo_departments').select2('val', null);
            $('#cbo_department').select2('val', null);
            $('#cbo_customers').select2('val', null);
            $('#cbo_customer_type').select2('val', 0);
            getproduct().done(function(data){
                products.clear();
                products.local = data.data;
                products.initialize(true);
                countproducts = data.data.length;
                    if(countproducts > 100){
                    showNotification({title:"Success !",stat:"success",msg:"Products List successfully updated."});
                    }

            }).always(function(){  });            
            showList(false);
        });
        $('#tbl_sales_order tbody').on('click','button[name="edit_info"]',function(){
            ///alert("ddd");
            _txnMode="edit";
            //$('.sales_order_title').html('Edit Sales Order');
                getproduct().done(function(data){
                    products.clear();
                    products.local = data.data;
                    products.initialize(true);
                    countproducts = data.data.length;
                        if(countproducts > 100){
                        showNotification({title:"Success !",stat:"success",msg:"Products List successfully updated."});
                        }

                }).always(function(){ });
                $('#typeaheadsearch').val('');


            _selectRowObj=$(this).closest('tr');
            var data=dt.row(_selectRowObj).data();
            _selectedID=data.sales_order_id;
            $('#span_so_no').html(data.so_no);
            $('textarea[name="remarks"]').val(data.remarks);
            $('input,textarea').each(function(){
                var _elem=$(this);
                $.each(data,function(name,value){
                    if(_elem.attr('name')==name&&_elem.attr('type')!='password'){
                        _elem.val(value);
                    }
                });
            });
            $('#txt_overall_discount').val(accounting.formatNumber($('#txt_overall_discount').val(),2));
            $('#cbo_departments').select2('val',data.department_id);
            $('#cbo_department').select2('val',data.department_id);
            $('#cbo_customers').select2('val',data.customer_id);
            $('#cbo_salesperson').select2('val',data.salesperson_id);
            $('#cbo_customer_type').select2('val',data.customer_type_id);
            $.ajax({
                url : 'Sales_order/transaction/items/'+data.sales_order_id,
                type : "GET",
                cache : false,
                dataType : 'json',
                processData : false,
                contentType : false,
                beforeSend : function(){
                    $('#tbl_items > tbody').html('<tr><td align="center" colspan="8"><br /><img src="assets/img/loader/ajax-loader-sm.gif" /><br /><br /></td></tr>');
                },
                success : function(response){
                    var rows=response.data;
                    $('#tbl_items > tbody').html('');
                      a=0;
                    $.each(rows,function(i,value){
                        _customer_type_ = _cboCustomerType.val();
                        var temp_sale_price=0.00;

                            if(_customer_type_ == '' || _customer_type_ == 0){
                                temp_sale_price=value.sale_price;
                            }else if(_customer_type_ == '1' ){ // DISCOUNTED CUSTOMER TYPE
                                temp_sale_price=value.discounted_price;
                            }else if(_customer_type_ == '2' ){ // DEALER CUSTOMER TYPE
                                temp_sale_price=value.dealer_price;
                            }else if(_customer_type_ == '3' ){ // DISTRIBUTOR CUSTOMER TYPE
                                temp_sale_price=value.distributor_price;
                            }else if(_customer_type_ == '4' ){ // PUBLIC CUSTOMER TYPE
                                temp_sale_price=value.public_price;
                            }else{
                                temp_sale_price=value.sale_price;
                            }
                        var retail_price;
                        if(value.is_bulk == 1){
                            retail_price = getFloat(temp_sale_price) / getFloat(value.child_unit_desc);
                        }else if (value.is_bulk == 0){
                            retail_price = 0;
                        }
 
                        $('#tbl_items > tbody').append(newRowItem({
                            so_gross:value.so_gross,
                            so_qty : value.so_qty,
                            product_code : value.product_code,
                            unit_id : value.unit_id,
                            unit_name : value.unit_name,
                            size : value.size,
                            product_id: value.product_id,
                            product_desc : value.product_desc,
                            so_line_total_discount : value.so_line_total_discount,
                            tax_exempt : false,
                            so_tax_rate : value.so_tax_rate,
                            so_price : value.so_price,
                            so_discount : value.so_discount,
                            tax_type_id : null,
                            so_line_total_price : value.so_line_total_price,
                            so_non_tax_amount: value.so_non_tax_amount,
                            so_tax_amount:value.so_tax_amount,
                            batch_no: value.batch_no,
                            exp_date: value.exp_date,
                            child_unit_id : value.child_unit_id,
                            child_unit_name : value.child_unit_name,
                            parent_unit_name : value.parent_unit_name,
                            parent_unit_id : getFloat(value.parent_unit_id),
                            is_bulk: value.is_bulk,
                            is_parent : value.is_parent,
                            bulk_price: temp_sale_price,
                            retail_price: retail_price,
                            a:a

                        }));
                        changetxn = 'inactive';
                        _line_unit=$('.line_unit'+a).select2({
                            minimumResultsForSearch: -1
                        });
                        _line_unit.select2('val',value.unit_id);
                        a++;
                    });
                    reComputeTotal();
                    changetxn = 'active';
                }
            });
            showList(false);
        });
        $('#tbl_sales_order tbody').on('click','button[name="remove_info"]',function(){
            _selectRowObj=$(this).closest('tr');
            var data=dt.row(_selectRowObj).data();
            _selectedID=data.sales_order_id;
            $('#modal_confirmation').modal('show');
        });
        //track every changes on numeric fields

        $('#txt_overall_discount').on('keyup',function(){
            reComputeTotal();
        });

        $('#tbl_items tbody').on('change','select',function(){
        if(changetxn == 'active'){
            var row=$(this).closest('tr');
            var unit_value=row.find(oTableItems.unit_value).find('option:selected').attr("data-unit-identifier"); 
            if(getFloat(unit_value) == 1 ){
                var price=parseFloat(accounting.unformat(row.find(oTableItems.bulk_price).find('input.numeric').val()));
            }else{
                var price=parseFloat(accounting.unformat(row.find(oTableItems.retail_price).find('input.numeric').val()));
            }
            $(oTableItems.unit_price,row).find('input').val(accounting.formatNumber(price,2));   
            $(oTableItems.unit_identifier,row).find('input').val(unit_value); 
            $('.number').keyup();
        }

        
        });

        $('#tbl_items tbody').on('keyup','input.numeric,input.number',function(){
            var row=$(this).closest('tr');

            var qty=parseFloat(accounting.unformat(row.find(oTableItems.qty).find('input.number').val()));             //0
            var price=parseFloat(accounting.unformat(row.find(oTableItems.unit_price).find('input.numeric').val()));   //3
            var discount=parseFloat(accounting.unformat(row.find(oTableItems.discount).find('input.numeric').val()));  //4
            var tax_rate=parseFloat(accounting.unformat(row.find(oTableItems.tax).find('input.numeric').val()))/100;   //6
            if(discount>price){
                showNotification({title:"Invalid",stat:"error",msg:"Discount must not greater than unit price."});
                row.find(oTableItems.discount).find('input.numeric').val('0.00');
                //$(this).trigger('keyup');
                //return;
            }


            // var discounted_price=price-discount; //
            // var line_total_discount=discount*qty; //
            // var line_total=discounted_price*qty; //


            var line_total = price*qty; //ok
            var line_total_discount=line_total*(discount/100);  
            var net_vat=line_total/(1+tax_rate); //  ok
            var vat_input=line_total-net_vat;  //ok
            var new_line_total=line_total-line_total_discount; 

            $(oTableItems.gross,row).find('input.numeric').val(accounting.formatNumber(line_total,2));
            $(oTableItems.total_line_discount,row).find('input.numeric').val(line_total_discount); //line total discount        //5
            $(oTableItems.total,row).find('input.numeric').val(accounting.formatNumber(new_line_total,2)); // line total amount     //7   ok
            $(oTableItems.vat_input,row).find('input.numeric').val(vat_input); //vat input                                      //8   ok
            $(oTableItems.net_vat,row).find('input.numeric').val(net_vat); //net of vat                                         //9  ok

            //console.log(net_vat);
            reComputeTotal();
        });
        $('#btn_yes').click(function(){
            //var d=dt.row(_selectRowObj).data();
            //if(getFloat(d.order_status_id)>1){
            //showNotification({title:"Error!",stat:"error",msg:"Sorry, you cannot delete purchase order that is already been recorded on purchase invoice."});
            //}else{
            removeIssuanceRecord().done(function(response){
                showNotification(response);
                if(response.stat=="success"){
                    dt.row(_selectRowObj).remove().draw();
                }
            });
            //}
        });
        $('#btn_cancel').click(function(){
            showList(true);
        });
        $('#btn_save').click(function(){
            if(validateRequiredFields($('#frm_sales_order'))){
                if(_txnMode=="new"){
                    createSalesOrder().done(function(response){
                        showNotification(response);
                        dt.row.add(response.row_added[0]).draw();
                        clearFields($('#frm_sales_order'));
                        showList(true);
                    }).always(function(){
                        showSpinningProgress($('#btn_save'));
                    });
                }else{
                    updateSalesOrder().done(function(response){
                        showNotification(response);
                        dt.row(_selectRowObj).data(response.row_updated[0]).draw(false);
                        clearFields($('#frm_sales_order'));
                        showList(true);
                    }).always(function(){
                        showSpinningProgress($('#btn_save'));
                    });
                }
            }
        });
        $('#tbl_items > tbody').on('click','button[name="remove_item"]',function(){
            $(this).closest('tr').remove();
            reComputeTotal();
        });
        //create new customer
        $('#btn_create_customer').click(function(){
            var btn=$(this);
            if(validateRequiredFields($('#frm_customer'))){
                var data=$('#frm_customer').serializeArray();
                data.push({name : "photo_path" ,value : $('img[name="img_user"]').attr('src')});
                $.ajax({
                    "dataType":"json",
                    "type":"POST",
                    "url":"Customers/transaction/new-create",
                    "data":data,
                    "beforeSend" : function(){
                        showSpinningProgress(btn);
                    }
                }).done(function(response){
                    showNotification(response);
                    $('#modal_new_customer').modal('hide');
                    var _customer=response.row_added[0];
                    $('#cbo_customers').append('<option value="'+_customer.customer_id+'" selected data-customer_type = "'+_customer.customer_type_id+'">'+ _customer.customer_name + '</option>');
                    $('#cbo_customers').select2('val',_customer.customer_id);
                    $('#cbo_customer_type').select2('val',_customer.customer_type_id);
                }).always(function(){
                    showSpinningProgress(btn);
                });
            }
        });
        $('#btn_browse').click(function(event){
            event.preventDefault();
            $('input[name="file_upload[]"]').click();
        });
        $('input[name="file_upload[]"]').change(function(event){
            var _files=event.target.files;
            /*$('#div_img_product').hide();
            $('#div_img_loader').show();*/
            var data=new FormData();
            $.each(_files,function(key,value){
                data.append(key,value);
            });
            console.log(_files);
            $.ajax({
                url : 'Customers/transaction/upload',
                type : "POST",
                data : data,
                cache : false,
                dataType : 'json',
                processData : false,
                contentType : false,
                success : function(response){
                    $('img[name="img_user"]').attr('src',response.path);
                }
            });
        });
        $('#btn_remove_photo').click(function(event){
            event.preventDefault();
            $('img[name="img_user"]').attr('src','assets/img/anonymous-icon.png');
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
    var getproduct=function(){
       return $.ajax({
           "dataType":"json",
           "type":"POST",
           "url":"products/transaction/list",
           "beforeSend": function(){
                countproducts = products.local.length;
                if(countproducts > 100){
                    showNotification({title:"Please Wait !",stat:"info",msg:"Refreshing your Products List."});
                }
           }
      });
    };


    var createCustomer=function(){
        var _data=$('#frm_customer').serializeArray();
        _data.push({name : "photo_path" ,value : $('img[name="img_user"]').attr('src')});
        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Customers/transaction/create",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_create_customer'))
        });
    };
    var createSalesOrder=function(){
        var _data=$('#frm_sales_order,#frm_items').serializeArray();
        var tbl_summary=$('#tbl_sales_order_summary');
        _data.push({name : "remarks", value : $('textarea[name="remarks"]').val()});
        

        _data.push({name : "total_after_discount", value: $('#td_total_after_discount').text()});
        _data.push({name : "summary_discount", value : tbl_summary.find(oTableDetails.discount).text()});
        _data.push({name : "summary_before_discount", value :tbl_summary.find(oTableDetails.before_tax).text()});
        _data.push({name : "summary_tax_amount", value : tbl_summary.find(oTableDetails.so_tax_amount).text()});
        _data.push({name : "summary_after_tax", value : tbl_summary.find(oTableDetails.after_tax).text()});

        console.log(_data);
        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Sales_order/transaction/create",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save'))
        });
    };
    var updateSalesOrder=function(){
        var _data=$('#frm_sales_order,#frm_items').serializeArray();
        var tbl_summary=$('#tbl_sales_order_summary');
        _data.push({name : "remarks", value : $('textarea[name="remarks"]').val()});
        _data.push({name : "total_after_discount", value: $('#td_total_after_discount').text()});
        _data.push({name : "summary_discount", value : tbl_summary.find(oTableDetails.discount).text()});
        _data.push({name : "summary_before_discount", value :tbl_summary.find(oTableDetails.before_tax).text()});
        _data.push({name : "summary_tax_amount", value : tbl_summary.find(oTableDetails.so_tax_amount).text()});
        _data.push({name : "summary_after_tax", value : tbl_summary.find(oTableDetails.after_tax).text()});

        _data.push({name : "sales_order_id" ,value : _selectedID});
        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Sales_order/transaction/update",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save'))
        });
    };
    var removeIssuanceRecord=function(){
        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Sales_order/transaction/delete",
            "data":{sales_order_id : _selectedID}
        });
    };
    var showList=function(b){
        if(b){
            $('#div_user_list').show();
            $('#div_user_fields').hide();
        }else{
            $('#div_user_list').hide();
            $('#div_user_fields').show();
        }
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
        $('input,textarea,select',f).val('');
        $(f).find('input:first').focus();
        $('#img_user').attr('src','assets/img/anonymous-icon.png');
        $('#order_default').datepicker('setDate', 'today');
        $('#td_discount').html('0.00');
        $('#td_total_before_tax').html('0.00');
        $('#td_tax').html('0.00');
        $('#td_after_tax').html('0.00');
        $('#txt_overall_discount').val('0.00');
        $('#td_total_after_discount').html('0.00');
        $('#remarks').val('');
    };
    function format ( d ) {
        //return
    };
    function validateNumber(event) {
        var key = window.event ? event.keyCode : event.which;
        if (event.keyCode === 8 || event.keyCode === 46
            || event.keyCode === 37 || event.keyCode === 39) {
            return true;
        }
        else if ( key < 48 || key > 57 ) {
            return false;
        }
        else return true;
    };
    var getFloat=function(f){
        return parseFloat(accounting.unformat(f));
    };
    var newRowItem=function(d){
        if(d.primary_unit == 1){ parent = ' selected'; child = ' '; }else{ parent = ' '; child = ' selected'; }
        if(d.is_bulk == '1'){ 
            unit = '<td ><select class="line_unit'+d.a+'" name="unit_id[]"><option value="'+d.parent_unit_id+'" data-unit-identifier="1" '+parent+'>'+d.parent_unit_name+'</option><option value="'+d.child_unit_id+'" data-unit-identifier="0" '+child+'>'+d.child_unit_name+'</option></select></td>';
        }else{ 
            unit  = '<td ><select class="line_unit'+d.a+'" name="unit_id[]" ><option value="'+d.parent_unit_id+'" data-unit-identifier="1" '+parent+'>'+d.parent_unit_name+'</option></select></td>';
        }
        return '<tr>'+
        // DISPLAY
        '<td ><input name="so_qty[]" type="text" class="number form-control" value="'+accounting.formatNumber(d.so_qty,2)+'"></td>'+unit+
        '<td >'+d.product_desc+'<input type="text" style="display:none;" class="form-control" name="is_parent[]" value="'+d.is_parent+'"></td>'+
        '<td ><input name="so_price[]" type="text" class="numeric form-control" value="'+accounting.formatNumber(d.so_price,2)+'" style="text-align:right;"></td>'+
        '<td  style=""><input name="so_discount[]" type="text" class="numeric form-control" value="'+ accounting.formatNumber(d.so_discount,2)+'" style="text-align:right;"></td>'+
        // DISPLAY NONE display:none;
        '<td style="display:none;" width="11%"><input name="so_line_total_discount[]" type="text" class="numeric form-control" value="'+ accounting.formatNumber(d.so_line_total_discount,2)+'" readonly></td>'+
        '<td  style="display:none;"><input name="so_tax_rate[]" type="text" class="numeric form-control" value="'+ accounting.formatNumber(d.so_tax_rate,2)+'"></td>'+
        // DISPLAY AGAIN 
        '<td  style=""><input name="so_gross[]" type="text" class="numeric form-control" value="'+ accounting.formatNumber(d.so_gross,2)+'" readonly></td>'+
        '<td  align="right"><input name="so_line_total_price[]" type="text" class="numeric form-control" value="'+ accounting.formatNumber(d.so_line_total_price,2)+'" readonly></td>'+
        //DISPLAY NONE display:none;
        '<td style="display:none;"><input name="so_tax_amount[]" type="text" class="numeric form-control" value="'+ d.so_tax_amount+'" readonly></td>'+
        '<td style="display:none;"><input name="so_non_tax_amount[]" type="text" class="numeric form-control" value="'+ d.so_non_tax_amount+'" readonly></td>'+
        '<td style="display:none;"><input name="product_id[]" type="text" class="form-control" value="'+ d.product_id+'" readonly></td>'+
        '<td align="center"><button type="button" name="remove_item" class="btn btn-red"><i class="fa fa-trash"></i></button></td>'+
        '<td style="display:none;"  ><input type="text" class="numeric form-control" value="'+ d.bulk_price+'" readonly></td>'+
        '<td style="display:none;"  ><input type="text" class="numeric form-control" value="'+ d.retail_price+'" readonly></td>'+
        '</tr>';
    };
    var reComputeTotal=function(){
        var rows=$('#tbl_items > tbody tr');
        var discounts=0; var before_tax=0; var after_tax=0; var so_tax_amount=0;
        $.each(rows,function(){
            //console.log($(oTableItems.net_vat,$(this)));
            discounts+=parseFloat(accounting.unformat($(oTableItems.total_line_discount,$(this)).find('input.numeric').val()));
            before_tax+=parseFloat(accounting.unformat($(oTableItems.net_vat,$(this)).find('input.numeric').val()));
            so_tax_amount+=parseFloat(accounting.unformat($(oTableItems.vat_input,$(this)).find('input.numeric').val()));
            after_tax+=parseFloat(accounting.unformat($(oTableItems.total,$(this)).find('input.numeric').val()));
        });
        var tbl_summary=$('#tbl_sales_order_summary');
        tbl_summary.find(oTableDetails.discount).html(accounting.formatNumber(discounts,2));
        tbl_summary.find(oTableDetails.before_tax).html(accounting.formatNumber(before_tax,2));
        tbl_summary.find(oTableDetails.so_tax_amount).html(accounting.formatNumber(so_tax_amount,2));
        tbl_summary.find(oTableDetails.after_tax).html('<b>'+accounting.formatNumber(after_tax,2)+'</b>');


        /*$('#td_before_tax').html(accounting.formatNumber(before_tax,2));
        $('#td_after_tax').html(accounting.formatNumber(after_tax,2));
        $('#td_discount').html(accounting.formatNumber(discounts,2));
        $('#td_tax').html(accounting.formatNumber(so_tax_amount,2));*/


        $('#txt_overall_discount_amount').val(accounting.formatNumber(after_tax * ($('#txt_overall_discount').val() / 100),2));
        $('#td_total_before_tax').html(accounting.formatNumber(before_tax,2)); // ok 
        $('#td_after_tax').html('<b>'+accounting.formatNumber(after_tax,2)+'</b>'); 
        $('#td_tax').html(accounting.formatNumber(so_tax_amount,2)); //ok 
        $('#td_total_after_discount').html(accounting.formatNumber(after_tax - (after_tax * ($('#txt_overall_discount').val() / 100)),2));
                $('#td_discount').html(accounting.formatNumber(discounts,2)); //unknown
    };
    var reInitializeNumeric=function(){
        $('.numeric').autoNumeric('init', {mDec:2});
        $('.number').autoNumeric('init', {mDec:2});
    };

    var initializeChange=function(){

    };

    var checkProduct= function(check_id){
        var prodstat=true;
        var rowcheck=$('#tbl_items > tbody tr');
        $.each(rowcheck,function(){
            item = parseFloat(accounting.unformat($(oTableItems.item_id,$(this)).find('input').val()));
            if(check_id == item){
                prodstat=false;
                return false;
            }
        });
         return prodstat;    
    };

});
</script>
</body>
</html>