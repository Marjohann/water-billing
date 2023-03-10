<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deliveries extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();

        $this->load->model('Delivery_invoice_model');
        $this->load->model('Suppliers_model');
        $this->load->model('Tax_types_model');
        $this->load->model('Products_model');
        $this->load->model('Delivery_invoice_item_model');
        $this->load->model('Purchases_model');
        $this->load->model('Departments_model');
        $this->load->model('Refproduct_model');
        $this->load->model('Users_model');
        $this->load->model('Trans_model');          

    }

    public function index() {
        $this->Users_model->validate();
        //default resources of the active view
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', TRUE);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', TRUE);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', TRUE);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', TRUE);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', TRUE);

        $data['refproducts']=$this->Refproduct_model->get_list(
            'is_deleted=FALSE'
        );

        $data['departments']=$this->Departments_model->get_list(
            array('departments.is_active'=>TRUE,'departments.is_deleted'=>FALSE)
        );

        //data required by active view
        $data['suppliers']=$this->Suppliers_model->get_list(
            array('suppliers.is_deleted'=>FALSE, 'suppliers.is_active'=>TRUE),
            'suppliers.*,IFNULL(tax_types.tax_rate,0)as tax_rate',
            array(
                array('tax_types','tax_types.tax_type_id=suppliers.tax_type_id','left')
            )
        );

        $data['tax_types']=$this->Tax_types_model->get_list('is_deleted=0');
        $data['title'] = 'Delivery Invoice';
        
        (in_array('2-2',$this->session->user_rights)? 
        $this->load->view('delivery_view', $data)
        :redirect(base_url('dashboard')));

    }

    function transaction($txn = null,$id_filter=null) {
        switch ($txn){

                case'close-invoice':  
                $m_delivery=$this->Delivery_invoice_model;
                $dr_invoice_id =$this->input->post('dr_invoice_id');
                $m_delivery->closing_reason = $this->input->post('closing_reason');
                $m_delivery->closed_by_user = $this->session->user_id;
                $m_delivery->is_closed = TRUE;
                $m_delivery->modify($dr_invoice_id);


                $invoice_info=$m_delivery->get_list($dr_invoice_id,'dr_invoice_no');
                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=11; //CRUD
                $m_trans->trans_type_id=12; // TRANS TYPE
                $m_trans->trans_log='Closed/ Did Not Post Purchase Invoice No: '.$invoice_info[0]->dr_invoice_no.' from Accounts Payable Pending with reason: '.$this->input->post('closing_reason');
                $m_trans->save();
                $response['title'] = 'Success!';
                $response['stat'] = 'success';
                $response['msg'] = 'Purchase Invoice successfully closed.';
                echo json_encode($response);    

                break;
            case'delivery_list_count':  //this returns JSON of Purchase Order to be rendered on Datatable with validation of count in invoice
            $m_delivery=$this->Delivery_invoice_model;
            $response['data']=$m_delivery->delivery_list_count($id_filter);

            echo json_encode($response);    

            break;



            case 'list':  //this returns JSON of Purchase Order to be rendered on Datatable

                $response['data']=$this->response_rows(
                    'delivery_invoice.is_active=TRUE AND delivery_invoice.is_deleted=FALSE'.($id_filter==null?'':' AND delivery_invoice.dr_invoice_id='.$id_filter),
                    'delivery_invoice.dr_invoice_id DESC'
                );
                echo json_encode($response);
                break;

            ////****************************************items/products of selected purchase invoice***********************************************
            case 'items': //items on the specific PO, loads when edit button is called
                $m_items=$this->Delivery_invoice_item_model;
                $response['data']=$m_items->get_list(
                    array('dr_invoice_id'=>$id_filter),
                    array(
                        'delivery_invoice_items.*',
                        'products.product_code',
                        'products.product_desc',    
                        'products.purchase_cost',
                        'products.is_bulk',
                        'products.child_unit_id',
                        'products.parent_unit_id',
                        'products.child_unit_desc',
                        '(SELECT units.unit_name  FROM units WHERE  units.unit_id = products.parent_unit_id) as parent_unit_name',
                        '(SELECT units.unit_name  FROM units WHERE  units.unit_id = products.child_unit_id) as child_unit_name'
                    ),
                    array(
                        array('products','products.product_id=delivery_invoice_items.product_id','left'),
                        array('units','units.unit_id=delivery_invoice_items.unit_id','left')
                    ),
                    'delivery_invoice_items.dr_invoice_item_id ASC'
                );


                echo json_encode($response);
                break;


            //***************************************create new purchase invoice************************************************
            case 'create':
                $m_delivery_invoice=$this->Delivery_invoice_model;

                /*if(count($m_delivery_invoice->get_list(array('dr_invoice_no'=>$this->input->post('dr_invoice_no',TRUE))))>0){
                    $response['title'] = 'Invalid!';
                    $response['stat'] = 'error';
                    $response['msg'] = 'Delivery Invoice No. already exists.';

                    echo json_encode($response);
                    exit;
                }*/

                //get purchase order id base on po number
                $m_po=$this->Purchases_model;
                $arr_po_info=$m_po->get_list(
                    array('purchase_order.po_no'=>$this->input->post('po_no',TRUE)),
                    'purchase_order.purchase_order_id'
                );
                $purchase_order_id=(count($arr_po_info)>0?$arr_po_info[0]->purchase_order_id:0);

                $m_delivery_invoice->begin();

                $m_delivery_invoice->set('date_created','NOW()'); //treat NOW() as function and not string

                $m_delivery_invoice->purchase_order_id=$purchase_order_id;
                //$m_delivery_invoice->dr_invoice_no=$this->input->post('dr_invoice_no',TRUE);
                //$m_delivery_invoice->batch_no=$this->input->post('batch_no',TRUE);
                $m_delivery_invoice->external_ref_no=$this->input->post('external_ref_no',TRUE);
                $m_delivery_invoice->contact_person=$this->input->post('contact_person',TRUE);
                $m_delivery_invoice->terms=$this->input->post('terms',TRUE);
                //$m_delivery_invoice->duration=$this->input->post('duration',TRUE);
                $m_delivery_invoice->supplier_id = $this->input->post('supplier',TRUE);
                $m_delivery_invoice->department_id = $this->input->post('department',TRUE);
                $m_delivery_invoice->remarks = $this->input->post('remarks',TRUE);
                $m_delivery_invoice->tax_type_id = $this->input->post('tax_type',TRUE);
                $m_delivery_invoice->date_delivered = date('Y-m-d',strtotime($this->input->post('date_delivered',TRUE)));
                $m_delivery_invoice->date_due = date('Y-m-d',strtotime($this->input->post('date_due',TRUE)));
                $m_delivery_invoice->posted_by_user = $this->session->user_id;
                $m_delivery_invoice->total_discount = $this->get_numeric_value($this->input->post('summary_discount',TRUE));
                $m_delivery_invoice->total_before_tax=$this->get_numeric_value($this->input->post('summary_before_discount',TRUE));
                $m_delivery_invoice->total_tax_amount=$this->get_numeric_value($this->input->post('summary_tax_amount',TRUE));
                $m_delivery_invoice->total_after_tax=$this->get_numeric_value($this->input->post('summary_after_tax',TRUE));
                $m_delivery_invoice->total_overall_discount=$this->get_numeric_value($this->input->post('total_overall_discount',TRUE));
                $m_delivery_invoice->total_overall_discount_amount=$this->get_numeric_value($this->input->post('total_overall_discount_amount',TRUE));
                $m_delivery_invoice->total_after_discount=$this->get_numeric_value($this->input->post('total_after_discount',TRUE));

                $m_delivery_invoice->save();

                $dr_invoice_id=$m_delivery_invoice->last_insert_id();
                $m_dr_items=$this->Delivery_invoice_item_model;

                $prod_id=$this->input->post('product_id',TRUE);
                $dr_qty=$this->input->post('dr_qty',TRUE);
                $dr_price=$this->input->post('dr_price',TRUE);
                $dr_discount=$this->input->post('dr_discount',TRUE);
                $dr_line_total_discount=$this->input->post('dr_line_total_discount',TRUE);
                $dr_line_total_after_global=$this->input->post('dr_line_total_after_global',TRUE);
                $dr_tax_rate=$this->input->post('dr_tax_rate',TRUE);
                $dr_line_total_price=$this->input->post('dr_line_total_price',TRUE);
                $dr_tax_amount=$this->input->post('dr_tax_amount',TRUE);
                $dr_non_tax_amount=$this->input->post('dr_non_tax_amount',TRUE);
                $exp_date= $this->input->post('exp_date',TRUE);
                $batch_code= $this->input->post('batch_code',TRUE);
                $is_parent=$this->input->post('is_parent',TRUE);
                $m_products=$this->Products_model;

                for($i=0;$i<count($prod_id);$i++){

                    $m_dr_items->dr_invoice_id=$dr_invoice_id;
                    $m_dr_items->product_id=$this->get_numeric_value($prod_id[$i]);
                    $m_dr_items->dr_qty=$this->get_numeric_value($dr_qty[$i]);
                    $m_dr_items->dr_price=$this->get_numeric_value($dr_price[$i]);
                    $m_dr_items->dr_discount=$this->get_numeric_value($dr_discount[$i]);
                    $m_dr_items->dr_line_total_discount=$this->get_numeric_value($dr_line_total_discount[$i]);
                    $m_dr_items->dr_tax_rate=$this->get_numeric_value($dr_tax_rate[$i]);
                    $m_dr_items->dr_line_total_price=$this->get_numeric_value($dr_line_total_price[$i]);
                    $m_dr_items->dr_tax_amount=$this->get_numeric_value($dr_tax_amount[$i]);
                    $m_dr_items->dr_non_tax_amount=$this->get_numeric_value($dr_non_tax_amount[$i]);
                    $m_dr_items->dr_line_total_after_global=$this->get_numeric_value($dr_line_total_after_global[$i]);
                    $m_dr_items->exp_date=date('Y-m-d', strtotime($exp_date[$i]));
                    $m_dr_items->batch_no=$batch_code[$i];

                    /*if($exp_date[$i]==null||$exp_date[$i]==""){
                        $response['title'] = 'Invalid Expiration!';
                        $response['stat'] = 'error';
                        $response['msg'] = 'Expiration date is required.';
                        $response['current_row_index'] = $i;

                        die(json_encode($response));
                    }*/

                    //$m_dr_items->set('unit_id','(SELECT unit_id FROM products WHERE product_id='.(int)$prod_id[$i].')');

                    //unit id retrieval is change, because of TRIGGER restriction
                    $m_dr_items->is_parent=$this->get_numeric_value($is_parent[$i]);
                    if($is_parent[$i] == '1'){
                                            $unit_id=$m_products->get_list(array('product_id'=>$this->get_numeric_value($prod_id[$i])));
                                            $m_dr_items->unit_id=$unit_id[0]->parent_unit_id;
                    }else{
                                             $unit_id=$m_products->get_list(array('product_id'=>$this->get_numeric_value($prod_id[$i])));
                                            $m_dr_items->unit_id=$unit_id[0]->child_unit_id;
                    }   
                    $m_dr_items->save();
                }

                //update invoice number base on formatted last insert id
                $m_delivery_invoice->dr_invoice_no='P-INV-'.date('Ymd').'-'.$dr_invoice_id;
                $m_delivery_invoice->modify($dr_invoice_id);

                //update status of po
                $m_po->order_status_id=$this->get_po_status($purchase_order_id);
                $m_po->modify($purchase_order_id);

                //update payable amount of supplier
                $m_suppliers=$this->Suppliers_model;
                $m_suppliers->recalculate_supplier_payable($this->input->post('supplier',TRUE));

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=1; //CRUD
                $m_trans->trans_type_id=12; // TRANS TYPE
                $m_trans->trans_log='Created Purchase Invoice No: P-INV-'.date('Ymd').'-'.$dr_invoice_id;
                $m_trans->save();


                $m_delivery_invoice->commit();



                if($m_delivery_invoice->status()===TRUE){
                    $response['title'] = 'Success!';
                    $response['stat'] = 'success';
                    $response['msg'] = 'Delivery invoice successfully created.';
                    $response['row_added']=$this->response_rows($dr_invoice_id);

                    echo json_encode($response);
                }


                break;


            ////***************************************update purchase invoice************************************************
            case 'update':
                $m_delivery_invoice=$this->Delivery_invoice_model;
                $dr_invoice_id=$this->input->post('dr_invoice_id',TRUE);


                //get purchase order id base on po number
                $m_po=$this->Purchases_model;
                $arr_po_info=$m_po->get_list(
                    array('purchase_order.po_no'=>$this->input->post('po_no',TRUE)),
                    'purchase_order.purchase_order_id'
                );
                $purchase_order_id=(count($arr_po_info)>0?$arr_po_info[0]->purchase_order_id:0);

                $m_delivery_invoice->begin();

                $m_delivery_invoice->purchase_order_id=$purchase_order_id;
                //$m_delivery_invoice->dr_invoice_no=$this->input->post('dr_invoice_no',TRUE);
                //$m_delivery_invoice->batch_no=$this->input->post('batch_no',TRUE);
                $m_delivery_invoice->external_ref_no=$this->input->post('external_ref_no',TRUE);
                $m_delivery_invoice->contact_person=$this->input->post('contact_person',TRUE);
                $m_delivery_invoice->terms=$this->input->post('terms',TRUE);
                //$m_delivery_invoice->duration=$this->input->post('duration',TRUE);
                $m_delivery_invoice->supplier_id=$this->input->post('supplier',TRUE);
                $m_delivery_invoice->department_id = $this->input->post('department',TRUE);
                $m_delivery_invoice->remarks=$this->input->post('remarks',TRUE);
                $m_delivery_invoice->tax_type_id=$this->input->post('tax_type',TRUE);
                $m_delivery_invoice->date_delivered=date('Y-m-d',strtotime($this->input->post('date_delivered',TRUE)));
                $m_delivery_invoice->date_due=date('Y-m-d',strtotime($this->input->post('date_due',TRUE)));
                $m_delivery_invoice->modified_by_user=$this->session->user_id;
                $m_delivery_invoice->total_discount=$this->get_numeric_value($this->input->post('summary_discount',TRUE));
                $m_delivery_invoice->total_before_tax=$this->get_numeric_value($this->input->post('summary_before_discount',TRUE));
                $m_delivery_invoice->total_tax_amount=$this->get_numeric_value($this->input->post('summary_tax_amount',TRUE));
                $m_delivery_invoice->total_after_tax=$this->get_numeric_value($this->input->post('summary_after_tax',TRUE));
                $m_delivery_invoice->total_overall_discount=$this->get_numeric_value($this->input->post('total_overall_discount',TRUE));
                $m_delivery_invoice->total_overall_discount_amount=$this->get_numeric_value($this->input->post('total_overall_discount_amount',TRUE));
                $m_delivery_invoice->total_after_discount=$this->get_numeric_value($this->input->post('total_after_discount',TRUE));
                $m_delivery_invoice->modify($dr_invoice_id);


                $m_dr_items=$this->Delivery_invoice_item_model;

                $tmp_prod_id = $m_dr_items->get_list(
                    array('dr_invoice_id'=>$dr_invoice_id),
                    'product_id'
                );

                $m_dr_items->delete_via_fk($dr_invoice_id); //delete previous items then insert those new

                $prod_id=$this->input->post('product_id',TRUE);
                $dr_price=$this->input->post('dr_price',TRUE);
                $dr_discount=$this->input->post('dr_discount',TRUE);
                $dr_line_total_discount=$this->input->post('dr_line_total_discount',TRUE);
                $dr_tax_rate=$this->input->post('dr_tax_rate',TRUE);
                $dr_qty=$this->input->post('dr_qty',TRUE);
                $dr_line_total_price=$this->input->post('dr_line_total_price',TRUE);
                $dr_line_total_after_global=$this->input->post('dr_line_total_after_global',TRUE);
                $dr_tax_amount=$this->input->post('dr_tax_amount',TRUE);
                $dr_non_tax_amount=$this->input->post('dr_non_tax_amount',TRUE);
                $exp_date = $this->input->post('exp_date',TRUE);
                $batch_code= $this->input->post('batch_code',TRUE);
                $is_parent=$this->input->post('is_parent',TRUE);

                $m_products=$this->Products_model;

                for($i=0;$i<count($prod_id);$i++){
                    $m_dr_items->dr_invoice_id=$dr_invoice_id;
                    $m_dr_items->product_id=$this->get_numeric_value($prod_id[$i]);
                    $m_dr_items->dr_price=$this->get_numeric_value($dr_price[$i]);
                    $m_dr_items->dr_discount=$this->get_numeric_value($dr_discount[$i]);
                    $m_dr_items->dr_line_total_discount=$this->get_numeric_value($dr_line_total_discount[$i]);
                    $m_dr_items->dr_tax_rate=$this->get_numeric_value($dr_tax_rate[$i]);
                    $m_dr_items->dr_line_total_price=$this->get_numeric_value($dr_line_total_price[$i]);
                    $m_dr_items->dr_qty=$this->get_numeric_value($dr_qty[$i]);
                    $m_dr_items->dr_tax_amount=$this->get_numeric_value($dr_tax_amount[$i]);
                    $m_dr_items->dr_non_tax_amount=$this->get_numeric_value($dr_non_tax_amount[$i]);
                    $m_dr_items->dr_line_total_after_global=$this->get_numeric_value($dr_line_total_after_global[$i]);
                    $m_dr_items->exp_date=date('Y-m-d', strtotime($exp_date[$i]));
                    $m_dr_items->batch_no=$batch_code[$i];
                    //$m_dr_items->set('unit_id','(SELECT unit_id FROM products WHERE product_id='.(int)$prod_id[$i].')');
                    //unit id retrieval is change, because of TRIGGER restriction

                    $m_dr_items->is_parent=$this->get_numeric_value($is_parent[$i]);
                    if($is_parent[$i] == '1'){
                                            $unit_id=$m_products->get_list(array('product_id'=>$this->get_numeric_value($prod_id[$i])));
                                            $m_dr_items->unit_id=$unit_id[0]->parent_unit_id;
                    }else{
                                             $unit_id=$m_products->get_list(array('product_id'=>$this->get_numeric_value($prod_id[$i])));
                                            $m_dr_items->unit_id=$unit_id[0]->child_unit_id;
                    }   
                    $m_dr_items->save();
                }


                //update status of po
                $m_po->order_status_id=$this->get_po_status($purchase_order_id);
                $m_po->modify($purchase_order_id);


                //update payable amount of supplier
                $m_suppliers=$this->Suppliers_model;
                $m_suppliers->recalculate_supplier_payable($this->input->post('supplier',TRUE));

                $pi_info=$m_delivery_invoice->get_list($dr_invoice_id,'dr_invoice_no');
                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=12; // TRANS TYPE
                $m_trans->trans_log='Updated Purchase Invoice No: '.$pi_info[0]->dr_invoice_no;
                $m_trans->save();
                
                $m_delivery_invoice->commit();



                if($m_delivery_invoice->status()===TRUE){
                    $response['title'] = 'Success!';
                    $response['stat'] = 'success';
                    $response['msg'] = 'Delivery invoice successfully updated.';
                    $response['row_updated']=$this->response_rows($dr_invoice_id);

                    echo json_encode($response);
                }


                break;


            //***************************************************************************************
            case 'delete':
                $m_delivery_invoice=$this->Delivery_invoice_model;
                $m_delivery_items=$this->Delivery_invoice_item_model;
                $dr_invoice_id=$this->input->post('dr_invoice_id',TRUE);

                // mark purchase invoice as deleted
                $m_delivery_invoice->set('date_deleted','NOW()'); //treat NOW() as function and not string,set deletion date
                $m_delivery_invoice->deleted_by_user=$this->session->user_id; //user that delete this record
                $m_delivery_invoice->is_deleted=1;
                $m_delivery_invoice->modify($dr_invoice_id);

                //end update product on_hand after purchase invoice is deleted...

                //********************************************************************************************************************
                //if purchase invoice is mark as deleted, make sure purchase order status is updated(open, closed, partially invoice)
                $po_info=$m_delivery_invoice->get_list($dr_invoice_id,'delivery_invoice.purchase_order_id'); //get purchase order first
                if(count($po_info)>0){ //make sure po info return resultset before executing other process
                    $purchase_order_id=$po_info[0]->purchase_order_id; //pass it to variable
                    //update purchase order status
                    $m_purchases=$this->Purchases_model;
                    $m_purchases->order_status_id=$this->get_po_status($purchase_order_id);
                    $m_purchases->modify($purchase_order_id);
                }
                //********************************************************************************************************************
                    $pi_info=$m_delivery_invoice->get_list($dr_invoice_id,'dr_invoice_no');
                    $m_trans=$this->Trans_model;
                    $m_trans->user_id=$this->session->user_id;
                    $m_trans->set('trans_date','NOW()');
                    $m_trans->trans_key_id=3; //CRUD
                    $m_trans->trans_type_id=12; // TRANS TYPE
                    $m_trans->trans_log='Deleted Purchase Invoice No: '.$pi_info[0]->dr_invoice_no;
                    $m_trans->save();
                $response['title']='Success!';
                $response['stat']='success';
                $response['msg']='Delivery invoice successfully deleted.';
                echo json_encode($response);

                break;


            //****************************************list of purchase invoice that are not yet posted as journal***********************************************
            case 'purchases-for-review':
                $m_delivery_invoice=$this->Delivery_invoice_model;
                // $response['data']=$m_delivery_invoice->get_list(

                //     array(
                //         'delivery_invoice.is_active'=>TRUE,
                //         'delivery_invoice.is_deleted'=>FALSE,
                //         'delivery_invoice.is_journal_posted'=>FALSE
                //     ),

                //     array(
                //         'delivery_invoice.dr_invoice_id',
                //         'delivery_invoice.dr_invoice_no',
                //         'CONCAT_WS(" ",delivery_invoice.terms,delivery_invoice.duration)as term_description',
                //         'delivery_invoice.remarks',
                //         'DATE_FORMAT(delivery_invoice.date_delivered,"%m/%d/%Y") as date_delivered',
                //         'suppliers.supplier_name'
                //     ),

                //     array(
                //         array('suppliers','suppliers.supplier_id=delivery_invoice.supplier_id','left')
                //     ),
                //     'delivery_invoice.dr_invoice_id DESC'



                // );

                // OLD Response - Invoice not subject to finalizing are still showing up so i revised the code

                $response['data']=$m_delivery_invoice->get_purchases_for_review();
                echo json_encode($response);
                break;
            //***************************************************************************************
            case 'test':
                $m_po=$this->Purchases_model;
                $row=$m_po->get_po_balance_qty($id_filter);
                echo json_encode($row);
                break;
            //***************************************************************************************
        }

    }



//**************************************user defined*************************************************
    function response_rows($filter_value,$order_by=null){
        // 07/21/2017 OLD response rows without count from invoices

        // return $this->Delivery_invoice_model->get_list(
        //     $filter_value,
        //     array(
        //         'delivery_invoice.*',
        //         'DATE_FORMAT(delivery_invoice.date_due,"%m/%d/%Y")as date_due',
        //         'DATE_FORMAT(delivery_invoice.date_delivered,"%m/%d/%Y")as date_delivered',
        //         'CONCAT_WS(" ",CAST(delivery_invoice.terms AS CHAR),delivery_invoice.duration)as term_description',
        //         'suppliers.supplier_name',
        //         'tax_types.tax_type',
        //         'purchase_order.po_no'
        //     ),
        //     array(
        //         array('suppliers','suppliers.supplier_id=delivery_invoice.supplier_id','left'),
        //         array('tax_types','tax_types.tax_type_id=delivery_invoice.tax_type_id','left'),
        //         array('purchase_order','purchase_order.purchase_order_id=delivery_invoice.purchase_order_id','left')
        //     ),
        //     $order_by
        // ); 


         // 07/21/2017 NEW response rows with count from invoices for validation
         return $this->Delivery_invoice_model->delivery_list_count($filter_value);


    }


    function get_po_status($id){
            //NOTE : 1 means open, 2 means Closed, 3 means partially invoice
            $m_delivery=$this->Delivery_invoice_model;

            if(count($m_delivery->get_list(
                        array('delivery_invoice.purchase_order_id'=>$id,'delivery_invoice.is_active'=>TRUE,'delivery_invoice.is_deleted'=>FALSE),
                        'delivery_invoice.dr_invoice_id'))==0 ){ //means no po found on delivery/purchase invoice that means this po is still open

                return 1;

            }else{

                $m_po=$this->Purchases_model;
                $row=$m_po->get_po_balance_qty($id);
                return ($row[0]->Balance>0?3:2);

            }

    }
//***************************************************************************************





}
