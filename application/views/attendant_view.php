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
        <link href="assets/plugins/select2/select2.min.css" rel="stylesheet">
        <link type="text/css" href="assets/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
        <link type="text/css" href="assets/plugins/datatables/dataTables.themify.css" rel="stylesheet">

        <style>
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

            @keyframes spin {
                from { transform: scale(1) rotate(0deg); }
                to { transform: scale(1) rotate(360deg); }
            }

            @-webkit-keyframes spin2 {
                from { -webkit-transform: rotate(0deg); }
                to { -webkit-transform: rotate(360deg); }
            }

            .select2-close-mask{
                z-index: 999999;
            }
            .select2-dropdown{
                z-index: 999999;
            }

            #tbl_attendant_filter{
                display: none;
            }
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

                            <ol class="breadcrumb" style="margin:0;">
                                <li><a href="dashboard">Dashboard</a></li>
                                <li><a href="Attendant">Attendant Management</a></li>
                            </ol>

                            <div class="container-fluid">
                                <div data-widget-group="group1">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div id="div_category_list">
                                                <div class="panel panel-default">
                                                    <div class="panel-body table-responsive">
                                                    <h2 class="h2-panel-heading">Attendant Management</h2><hr>
                                                    <div class="row">
                                                            <div class="col-lg-3"><br>
                                                                    <button class="btn btn-primary create_attendant_management"  id="btn_new" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;" data-toggle="modal" data-target="" data-placement="left" title="New Attendant" ><i class="fa fa-plus"></i> New Attendant person</button>
                                                            </div>
                                                            <div class="col-lg-offset-6 col-lg-3">
                                                                    Search :<br />
                                                                     <input type="text" id="searchbox_attendant" class="form-control">
                                                            </div>
                                                    </div><br>
                                                        <table id="tbl_attendant" class="table table-striped" cellspacing="0" width="100%">
                                                            <thead class="">
                                                                <tr>
                                                                    <th>Code</th>
                                                                    <th>Attendant Name</th>
                                                                    <th>Department</th>
                                                                    <th><center>Action</center></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- <div class="panel-footer"></div> -->
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
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                <h4 class="modal-title" style="color:white;"><span id="modal_mode"> </span>Confirm Deletion</h4>
                            </div>

                            <div class="modal-body">
                                <p id="modal-body-message">Are you sure ?</p>
                            </div>

                            <div class="modal-footer">
                                <button id="btn_yes" type="button" class="btn btn-danger" data-dismiss="modal">Yes</button>
                                <button id="btn_close" type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            </div>
                        </div><!-content-->
                    </div>
                </div><!---modal-->
                <div id="modal_new_attendant" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color:#2ecc71;">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                <h4 id="attendant_title" class="modal-title" style="color: #ecf0f1;"><span id="modal_mode"></span></h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <form id="frm_attendant" role="form">
                                        <div class="">
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label class="col-xs-12 col-md-4 control-label "><strong> Attendant Code :</strong></label>
                                                    <div class="col-xs-12 col-md-8">
                                                        <input type="text" name="attendant_code" class="form-control" placeholder="ATD-YYYYMMDD-XXXX" readonly>
                                                    </div>
                                                </div>
                                            </div><br><br>
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label class="col-xs-12 col-md-4 control-label "><strong><font color="red">*</font> First name :</strong></label>
                                                    <div class="col-xs-12 col-md-8">
                                                        <input type="text" name="first_name" class="form-control" placeholder="Firstname" data-error-msg="Firstname is required!" required>
                                                    </div>
                                                </div>
                                            </div><br><br>
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label class="col-xs-12 col-md-4 control-label "><strong>&nbsp;&nbsp;Middle name :</strong></label>
                                                    <div class="col-xs-12 col-md-8">
                                                        <input type="text" name="middle_name" class="form-control" placeholder="Middlename">
                                                    </div>
                                                </div>
                                            </div><br><br>
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label class="col-xs-12 col-md-4 control-label "><strong><font color="red">*</font> Last name :</strong></label>
                                                    <div class="col-xs-12 col-md-8">
                                                        <input type="text" name="last_name" class="form-control" placeholder="Last name" required data-error-msg="Lastname is required!">
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
                                                <label class="col-xs-12 col-md-4 control-label "><strong>Department :</strong></label>
                                                <div class="col-xs-12 col-md-8">
                                                    <select name="department_id" id="cbo_department" class="form-control" data-error-msg="Department is required!" style="width: 100%;">
                                                        <option value="0">[ Create New Department ]</option>
                                                        <?php foreach($departments as $department) { ?>
                                                            <option value="<?php echo $department->department_id; ?>"><?php echo $department->department_name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="btn_save" class="btn btn-primary" name="btn_save"><span></span>Save</button>
                                <button id="btn_cancel" class="btn btn-default">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="modal_new_department" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #2ecc71">
                             <h2 id="department_title" class="modal-title" style="color:white;">Create New Department</h2>
                        </div>
                        <div class="modal-body">
                            <form id="frm_department" role="form" class="form-horizontal">
                                <div class="row" style="margin: 1%;">
                                    <div class="col-lg-12">
                                        <div class="form-group" style="margin-bottom:0px;">
                                            <label class=""><font color="red">*</font> Department Name :</label>
                                            <textarea name="department_name" class="form-control" data-error-msg="Department Name is required!" placeholder="Department name" required></textarea>

                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin: 1%;">
                                    <div class="col-lg-12">
                                        <div class="form-group" style="margin-bottom:0px;">
                                            <label class="">Department Description :</label>
                                            <textarea name="department_desc" class="form-control" placeholder="Department Description"></textarea>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button id="btn_save_department" class="btn btn-primary">Save</button>
                            <button id="btn_cancel_department" class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

                <footer role="contentinfo">
                    <div class="clearfix">
                        <ul class="list-unstyled list-inline pull-left">
                            <li><h6 style="margin: 0;">&copy; 2022 - SPOOGO IT SOLUTION INC.</h6></li>
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

    <script src="assets/plugins/select2/select2.full.min.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables/dataTables.bootstrap.js"></script>

    <?php echo $_rights; ?>
    <script>

    $(document).ready(function(){
        var dt; var _txnMode; var _selectedID; var _selectRowObj; var _cboDepartment;

        var initializeControls=function(){
            dt=$('#tbl_attendant').DataTable({
                "dom": '<"toolbar">frtip',
                "bLengthChange":false,
                "order": [[ 4, "desc" ]],
                "ajax" : "Attendant/transaction/list",
                "columns": [
                    { targets:[0],data: "attendant_code" },
                    { targets:[1],data: "full_name" },
                    { targets:[2],data: "department_name" },
                    {
                        targets:[3],
                        render: function (data, type, full, meta){
                            return '<center>'+btn_edit_attendant_management+'&nbsp;'+btn_trash_attendant_management+'</center>';
                        }
                    },
                    { targets:[4],data: "attendant_id", visible:false},
                ]
            });

            _cboDepartment=$('#cbo_department').select2({
                placeholder: "Please Select Department",
                allowClear: true
            });

            _cboDepartment.select2('val',null);
        }();

        var bindEventHandlers=(function(){
            var detailRows = [];

            $("#searchbox_attendant").keyup(function(){         
                dt
                    .search(this.value)
                    .draw();
            });

            _cboDepartment.on('select2:select', function(){
                if (_cboDepartment.val() == 0) {
                    clearFields($('#frm_department'));
                    $('#modal_new_department').modal('show');
                    $('#modal_new_attendant').modal('hide');
                }
            });

            $('#btn_cancel_department').on('click', function(){
                $('#modal_new_department').modal('hide');
                $('#modal_new_department_sp').modal('hide');
                $('#modal_new_attendant').modal('show');
                _cboDepartment.select2('val',null);
            });

            $('#tbl_attendant tbody').on( 'click', 'tr td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = dt.row( tr );
                var idx = $.inArray( tr.attr('id'), detailRows );

                if ( row.child.isShown() ) {
                    tr.removeClass( 'details' );
                    row.child.hide();

                    detailRows.splice( idx, 1 );
                }
                else {
                    tr.addClass( 'details' );

                    row.child( format( row.data() ) ).show();

                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                }
            } );

            $('#btn_new').click(function(){
                _txnMode="new";
                clearFields($('#frm_attendant'));
                $('#attendant_title').text('New Attendant person');
                $('#modal_new_attendant').modal('show');
                _cboDepartment.select2('val',null);
            });

            $('#tbl_attendant tbody').on('click','button[name="edit_info"]',function(){
                _txnMode="edit";
                _selectRowObj=$(this).closest('tr');
                var data=dt.row(_selectRowObj).data();
                _selectedID=data.attendant_id;
                
                $('input,textarea').each(function(){
                    var _elem=$(this);
                    $.each(data,function(name,value){
                        if(_elem.attr('name')==name){
                            _elem.val(value);
                        }
                    });
                });

                _cboDepartment.select2('val',data.department_id);

                $('#attendant_title').text('Edit Attendant person');
                $('#modal_new_attendant').modal('show');
            });

            $('#tbl_attendant tbody').on('click','button[name="remove_info"]',function(){
                _selectRowObj=$(this).closest('tr');
                var data=dt.row(_selectRowObj).data();
                _selectedID=data.attendant_id;
                $('#modal_confirmation').modal('show');
            });

            $('#btn_yes').click(function(){
                removeAttendant().done(function(response){
                    showNotification(response);
                    dt.row(_selectRowObj).remove().draw();
                });
            });

            $('#btn_cancel').click(function(){
                $('#modal_new_attendant').modal('hide');
            });

            $('#btn_save_department').click(function(){
                if(validateRequiredFields($('#frm_department'))){
                    createDepartment().done(function(response){
                        var department=response.row_added[0];

                        $('#cbo_department').append('<option value="'+ department.department_id +'">'+ department.department_name +'</option>');
                        _cboDepartment.select2('val',department.department_id);

                        $('#modal_new_department').modal('hide');
                        $('#modal_new_attendant').modal('show');
                        clearFields($('#frm_department'));
                    });
                }
            }); 

            $('#btn_save').click(function(){
                if(validateRequiredFields($('#frm_attendant'))){
                    if(_txnMode=="new"){
                        createAttendant().done(function(response){
                            showNotification(response);
                            dt.row.add(response.row_added[0]).draw();
                            clearFields($('#frm_attendant'));

                        }).always(function(){
                            showSpinningProgress($('#btn_save'));
                        });
                    }else{
                        updateAttendant().done(function(response){
                            showNotification(response);
                            dt.row(_selectRowObj).data(response.row_updated[0]).draw();
                            clearFields($('#frm_attendant'));
                        }).always(function(){
                            showSpinningProgress($('#btn_save'));
                        });
                    }
                    $('#modal_new_attendant').modal('hide');
                }
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

        var createDepartment=function(){
            var _dataDepartment=$('#frm_department').serializeArray();

            return $.ajax({
                "dataType":"json",
                "type":"POST",
                "url":"Departments/transaction/create",
                "data":_dataDepartment
            });
        }

        var createAttendant=function(){
            var _data=$('#frm_attendant').serializeArray();

            return $.ajax({
                "dataType":"json",
                "type":"POST",
                "url":"Attendant/transaction/create",
                "data":_data,
                "beforeSend": showSpinningProgress($('#btn_save'))
            });
        };

        var updateAttendant=function(){
            var _data=$('#frm_attendant').serializeArray();
            _data.push({name : "attendant_id" ,value : _selectedID});

            return $.ajax({
                "dataType":"json",
                "type":"POST",
                "url":"Attendant/transaction/update",
                "data":_data,
                "beforeSend": showSpinningProgress($('#btn_save'))
            });
        };

        var removeAttendant=function(){
            return $.ajax({
                "dataType":"json",
                "type":"POST",
                "url":"Attendant/transaction/delete",
                "data":{attendant_id : _selectedID}
            });
        };

        var showList=function(b){
            if(b){
                $('#div_category_list').show();
                $('#div_category_fields').hide();
            }else{
                $('#div_category_list').hide();
                $('#div_category_fields').show();
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

        var clearFields=function(frm){
            $('input[required],input,textarea',frm).val('');
            $('form').find('input:first').focus();
        };

        function format ( d ) {
            return '<br /><table style="margin-left:10%;width: 80%;">' +
            '<thead>' +
            '</thead>' +
            '<tbody>' +
            '<tr>' +
            '<td>Category Name : </td><td><b>'+ d.category_name+'</b></td>' +
            '</tr>' +
            '<tr>' +
            '<td>Category Description : </td><td>'+ d.category_desc+'</td>' +
            '</tr>' +
            '</tbody></table><br />';
        };
    });

    </script>

    </body>

</html>