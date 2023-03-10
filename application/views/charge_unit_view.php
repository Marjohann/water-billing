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

        #tbl_units_filter{
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
                        <li><a href="Charge_unit">Charge Units</a></li>
                    </ol>

                    <div class="container-fluid">
                        <div data-widget-group="group1">
                            <div class="row">
                                <div class="col-md-12">

                                    <div id="div_unit_list">
                                        <div class="panel panel-default">
                                            <div class="panel-body table-responsive">
                                            <h2 class="h2-panel-heading"> Charge Units</h2><hr>
                                            <div class="row">
                                                    <div class="col-lg-3"><br>
                                                            <button class="btn btn-primary create_charge_unit_management"  id="btn_new" style="text-transform: capitalize;font-family: Tahoma, Georgia, Serif;" data-toggle="modal" data-target="" data-placement="left" title="New Charge unit" ><i class="fa fa-plus"></i> New Charge Unit</button>
                                                    </div>
                                                    <div class="col-lg-offset-6 col-lg-3">
                                                            Search :<br />
                                                             <input type="text" id="searchbox_unit" class="form-control">
                                                    </div>
                                            </div><br>
                                            <div class="row-panel">                                                
                                                <table id="tbl_units" cellspacing="0" class="table table-striped" width="100%">

                                                    <thead class="table table-striped">
                                                    <tr>
                                                        <th>Charge Unit Name</th>
                                                        <th>Charge Unit Description</th>
                                                        <th><center>Action</center></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                            </div>
                                            <div class="panel-footer"></div>
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
                            <button type="button" class="close"   data-dismiss="modal" aria-hidden="true">X</button>
                            <h4 class="modal-title" style="color:white;"><span id="modal_mode"> </span>Confirm Deletion</h4>
                        </div>

                        <div class="modal-body">
                            <p id="modal-body-message">Are you sure ?</p>
                        </div>

                        <div class="modal-footer">
                            <button id="btn_yes" type="button" class="btn btn-danger" data-dismiss="modal">Yes</button>
                            <button id="btn_close" type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div><!---modal-->

            <div id="modal_units" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="unit_title" class="modal-title" style="color: white;"></h4>
                        </div>
                        <div class="modal-body">
                            <form id="frm_unit" role="form" class="form-horizontal row-border">
                                <div class="form-group">
                                    <label class="col-md-3 col-md-offset-1 control-label"><strong><b class="required">*</b> Charge Unit Name :</strong></label>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-tag"></i>
                                            </span>
                                            <input type="text" name="charge_unit_name" class="form-control" placeholder="Unit Name" data-error-msg="Unit name is required!" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 col-md-offset-1 control-label"><strong><b class="required">*</b> Charge Unit Description :</strong></label>
                                    <div class="col-md-7">
                                        <textarea name="charge_unit_desc" class="form-control" data-error-msg="Unit Description is required!" placeholder="Description" required></textarea>
                                    </div>
                                </div><br/>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button id="btn_save" class="btn btn-primary">Save</button>
                            <button id="btn_cancel" class="btn btn-default">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <footer role="contentinfo">
                <div class="clearfix">
                    <ul class="list-unstyled list-inline pull-left">
                         <li><h6 style="margin: 0;">&copy; 2022 - SPOOGO SOLUTIONS INC.</h6></li>
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

<?php echo $_rights; ?>
<script>

$(document).ready(function(){
    var dt; var _txnMode; var _selectedID; var _selectRowObj;

    var initializeControls=function(){
        dt=$('#tbl_units').DataTable({
            "dom": '<"toolbar">frtip',
            "bLengthChange":false,
            "order": [[ 3, "desc" ]],
            "ajax" : "Charge_unit/transaction/list",
            "language": {
                "searchPlaceholder":"Search Unit"
            },
            "columns": [
                { targets:[0],data: "charge_unit_name" },
                { targets:[1],data: "charge_unit_desc" },
                {
                    targets:[2],
                    render: function (data, type, full, meta){
                        return '<center>'+btn_edit_charge_unit_management+'&nbsp;'+btn_trash_charge_unit_management+'</center>';
                    }
                },
                { targets:[3],data: "charge_unit_id", visible:false}
            ]
        });
    }();

    var bindEventHandlers=(function(){
        var detailRows = [];

        $("#searchbox_unit").keyup(function(){         
            dt
                .search(this.value)
                .draw();
        });

        $('#btn_new').click(function(){
            _txnMode="new";
            $('#unit_title').text('New Unit');
            $('#modal_units').modal('show');
            //showList(false);
        });

        $('#tbl_units tbody').on('click','button[name="edit_info"]',function(){
            _txnMode="edit";
            _selectRowObj=$(this).closest('tr');
            var data=dt.row(_selectRowObj).data();
            _selectedID=data.charge_unit_id;

            $('input,textarea').each(function(){
                var _elem=$(this);
                $.each(data,function(name,value){
                    if(_elem.attr('name')==name){
                        _elem.val(value);
                    }
                });
            });
            $('#unit_title').text('Edit Unit');
            $('#modal_units').modal('show');
            //showList(false);
        });

        $('#tbl_units tbody').on('click','button[name="remove_info"]',function(){
            _selectRowObj=$(this).closest('tr');
            var data=dt.row(_selectRowObj).data();
            _selectedID=data.charge_unit_id;

            $('#modal_confirmation').modal('show');
        });

        $('#btn_yes').click(function(){
            removeUnit().done(function(response){
                showNotification(response);
                dt.row(_selectRowObj).remove().draw();
            });
        });



        $('#btn_cancel').click(function(){
            $('#modal_units').modal('hide');
            //showList(true);
        });

        $('#btn_save').click(function(){
            if(validateRequiredFields()){
                if(_txnMode=="new"){
                    createUnit().done(function(response){
                        showNotification(response);
                        dt.row.add(response.row_added[0]).draw();
                        clearFields();
                    }).always(function(){
                        showSpinningProgress($('#btn_save'));
                    });
                }else{
                    updateUnit().done(function(response){
                        showNotification(response);
                        dt.row(_selectRowObj).data(response.row_updated[0]).draw();
                        clearFields();
                        //showList(true);
                    }).always(function(){
                        showSpinningProgress($('#btn_save'));
                    });
                }
                $('#modal_units').modal('hide');
            }
        });
    })();

    var validateRequiredFields=function(){
        var stat=true;

        $('div.form-group').removeClass('has-error');
        $('input[required],textarea','#frm_unit').each(function(){
            if($(this).val()==""){
                showNotification({title:"Error!",stat:"error",msg:$(this).data('error-msg')});
                $(this).closest('div.form-group').addClass('has-error');
                stat=false;
                return false;
            }
        });
        return stat;
    };

    var createUnit=function(){
        var _data=$('#frm_unit').serializeArray();

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Charge_unit/transaction/create",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save'))
        });
    };

    var updateUnit=function(){
        var _data=$('#frm_unit').serializeArray();
        _data.push({name : "unit_id" ,value : _selectedID});

        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Charge_unit/transaction/update",
            "data":_data,
            "beforeSend": showSpinningProgress($('#btn_save'))
        });
    };

    var removeUnit=function(){
        return $.ajax({
            "dataType":"json",
            "type":"POST",
            "url":"Charge_unit/transaction/delete",
            "data":{unit_id : _selectedID}
        });
    };

    var showList=function(b){
        if(b){
            $('#div_unit_list').show();
            $('#div_unit_fields').hide();
        }else{
            $('#div_unit_list').hide();
            $('#div_unit_fields').show();
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
    };

    var clearFields=function(){
        $('input[required],textarea','#frm_unit').val('');
        $('form').find('input:first').focus();
    };


});

</script>

</body>

</html>