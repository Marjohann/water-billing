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
    <style>
        .toolbar{
            float: left;
        }

        td.details-control {
            background: url('assets/img/Folder_Closed.png') no-repeat center center;
            cursor: pointer;
        }
        td.details-control-print {
            background: url('assets/img/print.png') no-repeat center center;
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

        @keyframes spin {
            from { transform: scale(1) rotate(0deg); }
            to { transform: scale(1) rotate(360deg); }
        }

        @-webkit-keyframes spin2 {
            from { -webkit-transform: rotate(0deg); }
            to { -webkit-transform: rotate(360deg); }
        }

        #tbl_penalties_filter, #tbl_account_list_filter{
                display: none;
        }

        div.dataTables_processing{ 
        position: absolute!important; 
        top: 0%!important; 
        right: -45%!important; 
        left: auto!important; 
        width: 100%!important; 
        height: 40px!important; 
        background: none!important; 
        background-color: transparent!important; 
        } 
    </style>

</head>

<body class="animated-content">

<?php echo $_top_navigation; ?>

<div id="wrapper">
    <div id="layout-static">

        <?php echo $_side_bar_navigation;?>

        <div class="static-content-wrapper white-bg custom-background">
            <div class="static-content"  >
                <div class="page-content"><!-- #page-content -->
                    <ol class="breadcrumb transparent-background" style="margin: 0;">
                        <li><a href="dashboard">Dashboard</a></li>
                        <li><a href="Penalty_sending">Penalty Sending</a></li>
                    </ol>
                    <div class="container-fluid">
                        <div data-widget-group="group1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="div_reconnection_list">
                                        <div class="panel panel-default">
                                            <div class="panel-body table-responsive" style="overflow-x: hidden;">
                                            <h2 class="h2-panel-heading">Penalties to Accounting</h2><hr>
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label>Year :</label> <br />
                                                        <select name="year" id="cbo_year" data-error-msg="Month is required." required style="width: 100%;">
                                                            <?php 
                                                                $x=2000; $y=2055; 
                                                                for($i=$x;$i!= $y;$i++){ ?>
                                                                <option value="<?php echo $i; ?>">
                                                                    <?php echo $i; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                        <label> Month :</label> <br />
                                                        <select name="meter_reading_period_id" id="cbo_period" data-error-msg="Month is required." required style="width: 100%;">
                                                            <?php foreach($months as $month){ ?>
                                                                <option value="<?php echo $month->month_id; ?>">
                                                                    <?php echo $month->month_name; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label> Remarks :</label> <br />
                                                        <textarea class="form-control" name="remarks" id="remarks" rows="4"></textarea>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label>  Search :</label> <br />
                                                        <input type="text" id="searchbox_penalties" class="form-control">
                                                        <button type="button" class="btn btn-primary btn_sending_penalties" id="sent_to_accounting" style="width: 100%;margin-top: 15px;">
                                                            <i class="fa fa-send"></i> Send to Accounting
                                                        </button>
                                                    </div>
                                                </div>
                                                <br/>

                                                <table id="tbl_penalties" class="table table-striped" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Account No</th>
                                                            <th>Meter Serial</th>
                                                            <th>Particular</th>
                                                            <th>Penalty Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
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
            <div id="modal_confirmation" class="modal fade" tabindex="-1" role="dialog"><!--modal-->
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                            <h4 class="modal-title" style="color:white;"><span id="modal_mode"> </span>Confirm Process</h4>
                        </div>

                        <div class="modal-body">
                            <p id="modal-body-message">Are you sure?</p>
                        </div>

                        <div class="modal-footer">
                            <button id="btn_yes" type="button" class="btn btn-success" data-dismiss="modal">Yes</button>
                            <button id="btn_close" type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div><!---modal-->
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


<?php echo $_switcher_settings; ?>
<?php echo $_def_js_files; ?>

<script src="assets/plugins/spinner/dist/spin.min.js"></script>
<script src="assets/plugins/spinner/dist/ladda.min.js"></script>

<script src="assets/plugins/datapicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="assets/plugins/datatables/jquery.dataTables.js"></script>
<script type="text/javascript" src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/select2/select2.full.min.js"></script>
<!-- numeric formatter -->
<script src="assets/plugins/formatter/autoNumeric.js" type="text/javascript"></script>
<script src="assets/plugins/formatter/accounting.js" type="text/javascript"></script>

<script>

$(document).ready(function(){
    var dt; var _txnMode; var _selectedID; var _selectRowObj;
    var d = new Date(),
        n = d.getMonth(),
        y = d.getFullYear();

    var initializeControls=function(){

        _cboPeriod=$("#cbo_period").select2({
            placeholder: "Please Select Period.",
            allowClear: false
        })
        _cboPeriod.select2('val', n);

        _cboYear=$("#cbo_year").select2({
            placeholder: "Please Select Year.",
            allowClear: false
        });

        _cboYear.select2('val', y);

        dt=$('#tbl_penalties').DataTable({
            "bLengthChange":false,
            "order": [[2, 'asc' ]],
            oLanguage: {
                    sProcessing: '<center><br /><img src="assets/img/loader/ajax-loader-sm.gif" /><br /><br /></center>'
            },
            processing : true,
            "ajax" : {
                "url" : "Penalties_incurred/transaction/penalties",
                "bDestroy": true,            
                "data": function ( d ) {
                        return $.extend( {}, d, {
                            "period_id": $('#cbo_period').val(),
                            "year": $('#cbo_year').val()
                        });
                    }
            }, 
            "columns": [
                { targets:[0],data: "account_no" },
                { targets:[1],data: "serial_no" },
                { targets:[2],data: "receipt_name" },
                {
                    className: "text-right",
                    targets:[3],data: "penalty_fee",
                    render: function(data){
                        return accounting.formatNumber(data,2);
                    }
                },
            ]
        });

    $('.date-picker').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true
    });

    }();

    var bindEventHandlers=(function(){
        var detailRows = [];

        var reinitializeSendButton = function(){

            $.ajax({
                "dataType":"json",
                "type":"POST",
                "url":"Penalty_sending/transaction/check_sent_penalties",
                "data":{ "period_id": $('#cbo_period').val(), "year": $('#cbo_year').val() }
            }).done(function(response){
                var rows = response.data;
                $('#remarks').val("");

                if(rows.length > 0){
                    $('.btn_sending_penalties').prop('disabled', true);
                    $('.btn_sending_penalties').html('<i class="fa fa-check-circle"></i> Already Sent to Accounting');
                    $('.btn_sending_penalties').removeClass('btn-primary');
                    $('.btn_sending_penalties').addClass('btn-success');
                    $('#remarks').prop('readonly', true);
                    $('#remarks').val(rows[0].remarks);
                }else{
                    $('.btn_sending_penalties').prop('disabled', false);
                    $('.btn_sending_penalties').html('<i class="fa fa-send"></i> Send to Accounting');
                    $('.btn_sending_penalties').removeClass('btn-success');
                    $('.btn_sending_penalties').addClass('btn-primary');
                    $('#remarks').prop('readonly', false);
                    $('#remarks').val("");
                }

            });

        }

        reinitializeSendButton();

        $('#sent_to_accounting').on('click', function(){
            
            var count = dt.rows().data().length;

            if(count > 0){
                $('#modal_confirmation').modal('show');
            }else{
                showNotification({title:"Error!",stat:"error",msg: 'Records not found.'});
            }

        });

        $('#btn_yes').on('click', function(){
            btn = $(this);
            $.ajax({
                "dataType":"json",
                "type":"POST",
                "url":"Penalty_sending/transaction/send-to-accounting",
                "data":{ "period_id": $('#cbo_period').val(),  
                         "year": $('#cbo_year').val(),
                         "remarks": $('#remarks').val()
                        },
                "beforeSend" : function(){
                    showSpinningProgress(btn);
                }
            }).done(function(response){
                showNotification(response);
                reinitializeSendButton();
            }).always(function(){
                showSpinningProgress(btn);
            });
        });


        $('#tbl_penalties tbody').on( 'click', 'tr td.details-control', function () {
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
                    "url":"Templates/layout/billing_statement/"+ d.billing_id+"?type=contentview",
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
        });  

        $('#print_billing').on( 'click', function () {

            var period_id = _cboPeriod.select2('val');
            var meter_reading_input_id = _cboBatchNo.select2('val');
            var customer_id = _cboCustomer.select2('val');

            if(period_id != null){
                window.open("Templates/layout/billing_statement_all/"+period_id+"/"+meter_reading_input_id+"/"+customer_id+"?type=preview");
            }else{
                showNotification({title:"Error!",stat:"error",msg:"Meter Period is Required!"});
            }

        });

        $('#print_report').on( 'click', function () {

            var period_id = _cboPeriod.select2('val');
            var year = _cboYear.select2('val');

            // if(period_id != null){
                window.open("Templates/layout/penalties_incurred/"+period_id+"/"+year+"?type=preview");
            // }else{
            //     showNotification({title:"Error!",stat:"error",msg:"Month is Required!"});
            // }

        });

        $(document).on('click','#export_report',function(){

            var period_id = _cboPeriod.select2('val');
            var year = _cboYear.select2('val');

            // if(period_id != null){
                window.open('Templates/layout/penalties_incurred_export?type=export&month_id='+period_id+'&year='+year);
            // }else{
            //     showNotification({title:"Error!",stat:"error",msg:"Meter Period is Required!"});
            // }

        });        

        $("#searchbox_penalties").keyup(function(){         
            dt
                .search(this.value)
                .draw();
        });        

        _cboPeriod.on('select2:select', function(){
            $('#tbl_penalties tbody').html('<tr><td colspan="4"><center><br/><br /><br /></center></td></tr>');
            dt.ajax.reload( null, false );
            reinitializeSendButton();
        });  

        _cboYear.on('select2:select', function(){
            $('#tbl_penalties tbody').html('<tr><td colspan="4"><center><br/><br /><br /></center></td></tr>');
            dt.ajax.reload( null, false );
            reinitializeSendButton();
        });  

    })();

    var validateRequiredFields=function(f){
        var stat=true;

        $('div.form-group').removeClass('has-error');
        $('input[required],textarea[required],select[required]',f).each(function(){

                if($(this).is('select')){
                    if($(this).val()==null || $(this).val()==""){
                        showNotification({title:"Error!",stat:"error",msg:$(this).data('error-msg')});
                        $(this).closest('div.form-group').addClass('has-error');
                        $(this).focus();
                        stat=false;
                        return false;
                    }
                }else{
                if($(this).val()=="" || $(this).val()== '0'){
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

    var batches=function(i){
        var _data=$('#').serializeArray();
        _data.push({name : "period_id" ,value : i});

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Billing_statement/transaction/get_batches",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_process'))
        });
    };

    var process_billing=function(){
        var _data = dt.$('input, select').serialize();
        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Process_billing/transaction/process",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_process'))
        });
    };

    var check_process=function(){
        var _data = dt.$('input, select').serialize();
        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Process_billing/transaction/check_process",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_process'))
        });
    };    

    var showList=function(b){
        if(b){
            $('#div_reconnection_list').show();
            $('#div_department_fields').hide();
        }else{
            $('#div_reconnection_list').hide();
            $('#div_department_fields').show();
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
        $(e).find('i').toggleClass('glyphicon glyphicon-refresh spinning');
    };

    var clearFields=function(f){
        $('input:not(.date-picker),input[required],textarea',f).val('');
        $('form').find('input:first').focus();
    };

    function format ( d ) {
        return '<br /><table style="margin-left:10%;width: 80%;">' +
        '<thead>' +
        '</thead>' +
        '<tbody>' +
        '<tr>' +
        '<td>Department Name : </td><td><b>'+ d.department_name+'</b></td>' +
        '</tr>' +
        '<tr>' +
        '<td>Department Description : </td><td>'+ d.department_desc+'</td>' +
        '</tr>' +
        '</tbody></table><br />';
    };
});

</script>

</body>

</html>