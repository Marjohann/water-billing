<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <title>GHMW - <?php echo $title; ?></title>

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
    <link href="assets/plugins/select2/select2.min.css" rel="stylesheet">
    <link href="assets/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

    <?php echo $_switcher_settings; ?>
    <?php echo $_def_js_files; ?>


    <script type="text/javascript" src="assets/plugins/datatables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables/dataTables.bootstrap.js"></script>


    <!-- Date range use moment.js same as full calendar plugin -->
    <script src="assets/plugins/fullcalendar/moment.min.js"></script>
    <!-- Data picker -->
    <script src="assets/plugins/datapicker/bootstrap-datepicker.js"></script>

    <!-- Select2 -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>


    <!-- Date range use moment.js same as full calendar plugin -->
    <script src="assets/js/plugins/fullcalendar/moment.min.js"></script>
    <!-- Data picker -->
    <script src="assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>

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
    <style>
        .right-align{
            text-align: right;
        }  
        .toolbar{
            float: left;
            margin-bottom: 0px !important;
            padding-bottom: 0px !important;
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
        @keyframes spin {
            from { transform: scale(1) rotate(0deg); }
            to { transform: scale(1) rotate(360deg); }
        }

        @-webkit-keyframes spin2 {
            from { -webkit-transform: rotate(0deg); }
            to { -webkit-transform: rotate(360deg); }
        }

    </style>


</head>

<body class="animated-content" style="font-family: tahoma;">

<?php echo $_top_navigation; ?>

<div id="wrapper">
    <div id="layout-static">

        <?php echo $_side_bar_navigation;?>

        <div class="static-content-wrapper white-bg">
            <div class="static-content"  >
                <div class="page-content"><!-- #page-content -->

                    <ol class="breadcrumb" style="margin:0%;">
                        <li><a href="dashboard">Dashboard</a></li>
                        <li><a href="Service_trail" id="filter">Service Trail</a></li>
                    </ol>

                    <div class="container-fluid">
                        <div data-widget-group="group1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="div_product_list">
                                        <div class="panel panel-default">
<!--                                             <div class="panel-heading">
                                                <b style="color: white; font-size: 12pt;"><i class="fa fa-bars"></i>&nbsp; Products</b>
                                            </div> -->
                                            <div class="panel-body table-responsive" id="product_list_panel">
                                            <h2 class="h2-panel-heading">Service Trail</h2><hr>
                                                <div class="row">
                                                    <div class="col-sm-3" >
                                                        Transaction Type :
                                                        <select name="" id="trans_type_id" required>
                                                            <option value="all">All Transaction Types</option>
                                                            <?php foreach($trans_type as $row) { echo '<option value="'.$row->trans_type_id.'">'.$row->trans_type_desc.'</option>'; } ?>
                                                        </select>

                                                    </div>
                                                    <div class="col-sm-3" >
                                                        Record Type :
                                                        <select name="" id="trans_key_id"  required>
                                                            <option value="all">All Record Types</option>
                                                            <?php foreach($trans_key as $row) { echo '<option value="'.$row->trans_key_id.'">'.$row->trans_key_desc.'</option>'; } ?>
                                                        </select>

                                                    </div>
                                                    <div class="col-lg-offset-2 col-sm-2">
                                                        From :
                                                        <div class="input-group">
                                                            <input type="text" name="start_date" id="start_date" class="date-picker form-control" value="01/01/<?php echo date("Y"); ?>" placeholder="Date" data-error-msg="Please set the date." required>
                                                             <span class="input-group-addon">
                                                                 <i class="fa fa-calendar"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        To :
                                                        <div class="input-group">
                                                            <input type="text" name="end_date" id="end_date" class="date-picker form-control" value="<?php echo date("m/d/Y"); ?>" placeholder="Date" data-error-msg="Please set the date." required>
                                                             <span class="input-group-addon">
                                                                 <i class="fa fa-calendar"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3" >
                                                        User Account:
                                                        <select name="" id="user_id"  required>
                                                            <option value="all">All User Accounts</option>
                                                            <?php foreach($users as $row) { echo '<option value="'.$row->user_id.'">'.$row->full_name.'</option>'; } ?>
                                                        </select>

                                                    </div>
                                                    <div class="col-sm-3">
                                                        Service No:
                                                        <select name="" id="connection_id"  required>
                                                            <option value="all">All Services</option>
                                                            <?php foreach($services as $service) { echo '<option value="'.$service->connection_id.'">'.$service->service_no.'</option>'; } ?>
                                                        </select>

                                                    </div>
                                                    <div class="col-sm-offset-2 col-sm-4">
                                                        <br />
                                                        <button type="button" class="btn btn-primary" id="btn_print" style="width: 100%;height: 30px;">
                                                            <i class="fa fa-print"></i> Print Service Trail
                                                        </button>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                </div>
                                                <table id="tbl_services" class="table table-striped" cellspacing="0" width="100%">
                                                    <thead class="">
                                                    <tr>    
                                                        <th width="13%">Transaction Date</th>
                                                        <th width="12%">Transaction Type</th>
                                                        <th>Record Type</th>
                                                        <th width="10%">Service No</th>
                                                        <th width="10%">Account No</th>
                                                        <th>Customer Name</th>
                                                        <th>Meter Serial</th>
                                                        <th>Log Description</th>
                                                        <th>User</th>
                                                        <th>Transaction ID</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="panel-footer" style="text-align: right;">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div> <!-- .container-fluid -->

                </div> <!-- #page-content -->
            </div>


            <footer role="contentinfo">
                <div class="clearfix">
                    <ul class="list-unstyled list-inline pull-left">
                        <li><h6 style="margin: 0;">&copy; 2022 - SPOOGO IT SOLUTION INC</h6></li>
                    </ul>
                    <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="ti ti-arrow-up"></i></button>
                </div>
            </footer>

        </div>
    </div>
</div>




    <script>

$(document).ready(function(){
    var dt; var _txnMode; var _selectedID; var _selectRowObj; var _cboItemTypes; var _selectedProductType; var _isTaxExempt=0;
    var _cboSupplier; var _cboType; var _cboTax; var _cboInventory; var _cboMeasurement; var _cboCredit; var _cboDebit;
    var _cboTaxGroup;
    var _section_id; var _menu_id; var _trans_key_id; var _user_id; 
    /*$(document).ready(function(){
        $('#modal_filter').modal('show');
        showList(false);
    });*/

    var initializeControls=function() {
        dt=$('#tbl_services').DataTable({
            "fnInitComplete": function (oSettings, json) {
                // $.unblockUI();
                },
            "dom": '<"toolbar">frtip',
            "bLengthChange":false,
            "pageLength":15,
            "order": [[ 9, "asc" ]],
             "searching": false,
            // "scrollX": true,
            "ajax": {
              "url":"Service_trail/transaction/list",
              "type":"GET",
              "data":function (d) {
                return $.extend( {}, d, {
                    "user_id": $('#user_id').val(),
                    "trans_type_id": $('#trans_type_id').val(),
                    "trans_key_id": $('#trans_key_id').val(),
                    "connection_id": $('#connection_id').val(),
                    "start_date": $('#start_date').val(),
                    "end_date": $('#end_date').val()
                });
              }
            },
            "columns": [

                { targets:[0],data: "trans_date" },
                { targets:[1],data: "trans_type_desc" },
                { targets:[2],data: "trans_key_desc" },
                { targets:[3],data: "service_no" },
                { targets:[4],data: "account_no" },
                { targets:[5],data: "customer_name" },
                { targets:[6],data: "serial_no" },
                { targets:[7],data: "trans_log" },
                { targets:[8], data: null, render: function ( data, type, row ) {
                // Combine the first and last names into a single table field
                if(data.user_fname!== null){
                    return data.user_fname+' '+data.user_lname; // data to be rendered 
                }else{
                    return'';
                }

                } },
                { visible:false,targets:[9],data: "trans_id" }

            ],

            language: {
                         searchPlaceholder: "Search"
                     },
            "rowCallback":function( row, data, index ){

                // $(row).find('td').eq(4).attr({
                //     "align": "right"
                // });
            }


        });


        $('.date-picker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
        });

        _cboType=$('#trans_type_id').select2({
            allowClear: false
        });

        _trans_key_id=$('#trans_key_id').select2({
            allowClear: false
        });

        _user_id=$('#user_id').select2({
            allowClear: false
        });

        _cboConnection=$('#connection_id').select2({
            allowClear: false
        });

    }();

    var bindEventHandlers=(function(){
        $("#trans_type_id").on("change", function () {        
            $('#tbl_services').DataTable().ajax.reload()
        });
        $("#user_id").on("change", function () {        
            $('#tbl_services').DataTable().ajax.reload()
        });
        $("#trans_key_id").on("change", function () {        
            $('#tbl_services').DataTable().ajax.reload()
        });
        $("#end_date").on("change", function () {        
            $('#tbl_services').DataTable().ajax.reload()
        });
        $("#start_date").on("change", function () {        
            $('#tbl_services').DataTable().ajax.reload()
        });
        $("#connection_id").on("change", function () {        
            $('#tbl_services').DataTable().ajax.reload()
        });

        var format_date = function(date){
            var formattedDate = new Date(date);
            var d = formattedDate.getDate();
            var m =  formattedDate.getMonth();
            m += 1;  // JavaScript months are 0-11
            var y = formattedDate.getFullYear();
            return (y + "-" + m + "-" + d);
        }

        $('#btn_print').on( 'click', function () {

            var start_date = format_date($('#start_date').val());
            var end_date = format_date($('#end_date').val());
            var trans_type_id = _cboType.select2('val');
            var trans_key_id = _trans_key_id.select2('val');
            var user_id = _user_id.select2('val');
            var connection_id = _cboConnection.select2('val');

            window.open("Templates/layout/service_trail/"+trans_type_id+"/"+trans_key_id+"/"+user_id+"/"+connection_id+"/"+start_date+"/"+end_date+"?type=preview");
        });        
    })();

    var showList=function(b){
        if(b){
            $('#div_product_list').show();
            $('#div_product_fields').hide();
        }else{
            $('#div_product_list').hide();
            $('#div_product_fields').show();
        }
    };

    var showNotification=function(obj){
        PNotify.removeAll();
        new PNotify({
            title:  obj.title,
            text:  obj.msg,
            type:  obj.stat
        });
    };

    var showSpinningProgress=function(e){
        $(e).find('span').toggleClass('glyphicon glyphicon-refresh spinning');
        $(e).toggleClass('disabled');
    };



    var getFloat=function(f){
        return parseFloat(accounting.unformat(f));
    };


});

</script>
</body>

</html>