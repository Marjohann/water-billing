<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dispatching extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();

        $this->load->model('Dispatching_invoice_model');
        $this->load->model('Dispatching_invoice_item_model');
        $this->load->model('Sales_order_model');
        $this->load->model('Sales_invoice_model');
        $this->load->model('Cash_invoice_model');
        $this->load->model('Departments_model');
        $this->load->model('Customers_model');
        $this->load->model('Products_model');
        $this->load->model('Company_model');
        $this->load->model('Salesperson_model');
        $this->load->model('Users_model');
        $this->load->model('Trans_model');
        $this->load->model('Customer_type_model');


    }

    public function index() {
        $this->Users_model->validate();
        //default resources of the active view
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', TRUE);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', TRUE);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', TRUE);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', TRUE);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', TRUE);


        //data required by active view
        $data['departments']=$this->Departments_model->get_list(
            array('departments.is_active'=>TRUE,'departments.is_deleted'=>FALSE)
        );

        $data['salespersons']=$this->Salesperson_model->get_list(
            array('salesperson.is_active'=>TRUE,'salesperson.is_deleted'=>FALSE),
            'salesperson_id, acr_name, CONCAT(firstname, " ", middlename, " ", lastname) AS fullname, firstname, middlename, lastname'
        );

        //data required by active view
        $data['customers']=$this->Customers_model->get_list(
            array('customers.is_active'=>TRUE,'customers.is_deleted'=>FALSE)
        );

        $data['customer_type']=$this->Customer_type_model->get_list(
            'is_deleted=FALSE'
        );

        $data['customer_type_create']=$this->Customer_type_model->get_list(
            'is_deleted=FALSE'
        );

        $tax_rate=$this->Company_model->get_list(
            null,
            array(
                'company_info.tax_type_id',
                'tt.tax_rate'
            ),
            array(
                array('tax_types as tt','tt.tax_type_id=company_info.tax_type_id','left')
            )
        );

        $data['tax_percentage']=(count($tax_rate)>0?$tax_rate[0]->tax_rate:0);
        $data['title'] = 'Warehouse Dispatching';
        
        (in_array('3-5',$this->session->user_rights)? 
        $this->load->view('dispatching_view.php', $data)
        :redirect(base_url('dashboard')));
    }


    function transaction($txn = null,$id_filter=null) {
        switch ($txn){


            case 'list':  //this returns JSON of Issuance to be rendered on Datatable
                $m_invoice=$this->Dispatching_invoice_model;
                $response['data']=$m_invoice->get_list(
                    'dispatching_invoice.is_active=TRUE AND dispatching_invoice.is_deleted=FALSE '.($id_filter==null?'':' AND dispatching_invoice.dispatching_invoice_id='.$id_filter),

                    'dispatching_invoice.*,
                    d.department_name,
                    c.customer_name
                    ',
                    array(
                        array('departments d','d.department_id = dispatching_invoice.department_id','left'),
                        array('customers c','c.customer_id = dispatching_invoice.customer_id','left')
                        )
                );
                echo json_encode($response);
                break;

            case 'open':  
                $m_invoice=$this->Dispatching_invoice_model;
                $response['data']=$this->Dispatching_invoice_model->list_of_open();
                echo json_encode($response);
                break;

            case 'item-balance-si':
                $m_items=$this->Dispatching_invoice_item_model;
                $response['data']=$m_items->get_products_with_balance_qty_si($id_filter);
                echo json_encode($response);
                break;


            case 'item-balance-ci':
                $m_items=$this->Dispatching_invoice_item_model;
                $response['data']=$m_items->get_products_with_balance_qty_ci($id_filter);
                echo json_encode($response);
                break;

            ////****************************************items/products of selected Items***********************************************
            case 'items': //items on the specific PO, loads when edit button is called
                $m_items=$this->Dispatching_invoice_item_model;
                $response['data']=$m_items->get_list(
                    array('dispatching_invoice_id'=>$id_filter),
                    array(
                        'dispatching_invoice_items.*',
                        'products.product_code',
                        'products.product_desc',
                        'products.sale_price',
                        'products.is_bulk',
                        'products.child_unit_id',
                        'products.parent_unit_id',
                        'products.child_unit_desc',
                        'products.discounted_price',
                        'products.dealer_price',
                        'products.distributor_price',
                        'products.public_price',
                        '(SELECT units.unit_name  FROM units WHERE  units.unit_id = products.parent_unit_id) as parent_unit_name',
                        '(SELECT units.unit_name  FROM units WHERE  units.unit_id = products.child_unit_id) as child_unit_name'
                    ),
                    array(
                        array('products','products.product_id=dispatching_invoice_items.product_id','left'),
                        array('units','units.unit_id=dispatching_invoice_items.unit_id','left')
                    ),
                    'dispatching_invoice_items.dispatching_item_id ASC'
                );


                echo json_encode($response);
                break;


            //***************************************create new Items************************************************

            
            case 'create':
                $m_invoice=$this->Dispatching_invoice_model;
                $m_customers=$this->Customers_model;

                $is_sales = $this->input->post('is_sales',TRUE);

                if($is_sales == 1){ // sales invoice
                $m_si=$this->Sales_invoice_model;
                $arr_si_info=$m_si->get_list(
                    array('sales_invoice.sales_inv_no'=>$this->input->post('inv_no',TRUE)),
                    'sales_invoice.sales_invoice_id,sales_invoice.sales_inv_no'
                );
                $sales_invoice_id=(count($arr_si_info)>0?$arr_si_info[0]->sales_invoice_id:0);
                $sales_inv_no = (count($arr_si_info)>0?$arr_si_info[0]->sales_inv_no:0);
                $cash_invoice_id=0;
                $cash_inv_no='';


                }else if($is_sales == 2){// cash invoice
                $m_ci=$this->Cash_invoice_model;
                $arr_ci_info=$m_ci->get_list(
                    array('cash_invoice.cash_inv_no'=>$this->input->post('inv_no',TRUE)),
                    'cash_invoice.cash_invoice_id,cash_invoice.cash_inv_no'
                );
                $cash_invoice_id=(count($arr_ci_info)>0?$arr_ci_info[0]->cash_invoice_id:0);
                $cash_inv_no = (count($arr_ci_info)>0?$arr_ci_info[0]->cash_inv_no:0);
                $sales_invoice_id=0;
                $sales_inv_no='';
                }else{ // No Invoice Selected
                    $cash_invoice_id=0;
                    $cash_inv_no='';
                    $sales_invoice_id=0;
                    $sales_inv_no='';
                }

                $m_invoice->begin();

                //treat NOW() as function and not string
                $m_invoice->set('date_created','NOW()'); //treat NOW() as function and not string
                $m_invoice->customer_type_id=$this->input->post('customer_type_id',TRUE);
                $m_invoice->customer_id=$this->input->post('customer',TRUE);
                $m_invoice->salesperson_id=$this->input->post('salesperson_id',TRUE);
                $m_invoice->department_id=$this->input->post('department',TRUE);
                $m_invoice->issue_to_department=$this->input->post('issue_to_department',TRUE);
                $m_invoice->address=$this->input->post('address',TRUE);
                $m_invoice->sales_invoice_id=$sales_invoice_id;
                $m_invoice->sales_inv_no=$sales_inv_no;
                $m_invoice->cash_invoice_id=$cash_invoice_id;
                $m_invoice->cash_inv_no=$cash_inv_no;
                $m_invoice->remarks=$this->input->post('remarks',TRUE);
                $m_invoice->contact_person=$this->input->post('contact_person',TRUE);
                $m_invoice->date_due=date('Y-m-d',strtotime($this->input->post('date_due',TRUE)));
                $m_invoice->date_invoice=date('Y-m-d',strtotime($this->input->post('date_invoice',TRUE)));
                $m_invoice->total_overall_discount_amount=$this->get_numeric_value($this->input->post('total_overall_discount_amount',TRUE));
                $m_invoice->total_discount=$this->get_numeric_value($this->input->post('summary_discount',TRUE));
                $m_invoice->total_overall_discount=$this->get_numeric_value($this->input->post('total_overall_discount',TRUE));
                $m_invoice->total_before_tax=$this->get_numeric_value($this->input->post('summary_before_discount',TRUE));
                //$m_invoice->inv_type=2;
                $m_invoice->total_tax_amount=$this->get_numeric_value($this->input->post('summary_tax_amount',TRUE));
                $m_invoice->total_after_tax=$this->get_numeric_value($this->input->post('summary_after_tax',TRUE));
                $m_invoice->total_after_discount=$this->get_numeric_value($this->input->post('total_after_discount',TRUE));
                $m_invoice->posted_by_user=$this->session->user_id;
                $m_invoice->save();

                $dispatching_invoice_id=$m_invoice->last_insert_id();

                $m_invoice_items=$this->Dispatching_invoice_item_model;

                $prod_id=$this->input->post('product_id',TRUE);
                $inv_qty=$this->input->post('inv_qty',TRUE);
                $inv_price=$this->input->post('inv_price',TRUE);
                $inv_gross=$this->input->post('inv_gross',TRUE);
                $inv_discount=$this->input->post('inv_discount',TRUE);
                $inv_line_total_discount=$this->input->post('inv_line_total_discount',TRUE);
                $inv_tax_rate=$this->input->post('inv_tax_rate',TRUE);
                $inv_line_total_price=$this->input->post('inv_line_total_price',TRUE);
                $inv_tax_amount=$this->input->post('inv_tax_amount',TRUE);
                $inv_non_tax_amount=$this->input->post('inv_non_tax_amount',TRUE);
                $inv_line_total_after_global=$this->input->post('inv_line_total_after_global',TRUE);
                $dr_invoice_id=$this->input->post('dr_invoice_id',TRUE);
                $exp_date=$this->input->post('exp_date',TRUE);
                $batch_no=$this->input->post('batch_no',TRUE);
                $cost_upon_invoice=$this->input->post('cost_upon_invoice',TRUE);
                $is_parent=$this->input->post('is_parent',TRUE);
                

                $m_products=$this->Products_model;

                for($i=0;$i<count($prod_id);$i++){

                    $m_invoice_items->dispatching_invoice_id=$dispatching_invoice_id;
                    $m_invoice_items->product_id=$this->get_numeric_value($prod_id[$i]);
                    $m_invoice_items->inv_line_total_after_global=$this->get_numeric_value($inv_line_total_after_global[$i]);
                    $m_invoice_items->inv_qty=$this->get_numeric_value($inv_qty[$i]);
                    $m_invoice_items->inv_price=$this->get_numeric_value($inv_price[$i]);
                    $m_invoice_items->inv_gross=$this->get_numeric_value($inv_gross[$i]);
                    $m_invoice_items->inv_discount=$this->get_numeric_value($inv_discount[$i]);
                    $m_invoice_items->inv_line_total_discount=$this->get_numeric_value($inv_line_total_discount[$i]);
                    $m_invoice_items->inv_tax_rate=$this->get_numeric_value($inv_tax_rate[$i]);
                    $m_invoice_items->inv_line_total_price=$this->get_numeric_value($inv_line_total_price[$i]);
                    $m_invoice_items->inv_tax_amount=$this->get_numeric_value($inv_tax_amount[$i]);
                    $m_invoice_items->inv_non_tax_amount=$this->get_numeric_value($inv_non_tax_amount[$i]);
                    //$m_invoice_items->dr_invoice_id=$dr_invoice_id[$i];
                    //$m_invoice_items->exp_date=date('Y-m-d', strtotime($exp_date[$i]));
                    //$m_invoice_items->batch_no=$batch_no[$i];
                    //$m_invoice_items->cost_upon_invoice=$this->get_numeric_value($cost_upon_invoice[$i]);

                    //unit id retrieval is change, because of TRIGGER restriction
                    $m_invoice_items->is_parent=$this->get_numeric_value($is_parent[$i]);
                    if($is_parent[$i] == '1'){
                                            $unit_id=$m_products->get_list(array('product_id'=>$this->get_numeric_value($prod_id[$i])));
                                            $m_invoice_items->unit_id=$unit_id[0]->parent_unit_id;
                    }else{
                                             $unit_id=$m_products->get_list(array('product_id'=>$this->get_numeric_value($prod_id[$i])));
                                            $m_invoice_items->unit_id=$unit_id[0]->child_unit_id;
                    }   
                    $m_invoice_items->save();
                }

                //update invoice number base on formatted last insert id
                $m_invoice->dispatching_inv_no='DIS-INV-'.date('Ymd').'-'.$dispatching_invoice_id;
                $m_invoice->modify($dispatching_invoice_id);



                if($is_sales == 1){
                    $m_si = $this->Sales_invoice_model;
                    $m_si->order_status_id=$this->get_si_status($sales_invoice_id);
                    $m_si->modify($sales_invoice_id);
                }else if($is_sales == 2){
                    $m_ci = $this->Cash_invoice_model;
                    $m_ci->order_status_id=$this->get_ci_status($cash_invoice_id);
                    $m_ci->modify($cash_invoice_id);
                }





                //******************************************************************************************
                //******************************************************************************************
                // $m_trans=$this->Trans_model;
                // $m_trans->user_id=$this->session->user_id;
                // $m_trans->set('trans_date','NOW()');
                // $m_trans->trans_key_id=1; //CRUD
                // $m_trans->trans_type_id=17; // TRANS TYPE
                // $m_trans->trans_log='Created Sales Invoice No: SAL-INV-'.date('Ymd').'-'.$sales_invoice_id;
                // $m_trans->save();

                $m_invoice->commit();

                if($m_invoice->status()===TRUE){
                    $response['title'] = 'Success!';
                    $response['stat'] = 'success';
                    $response['msg'] = 'Dispatching invoice successfully created.';
                    $response['row_added']=$this->response_rows($dispatching_invoice_id);

                    echo json_encode($response);
                }


                break;


            ////***************************************update Items************************************************
            case 'update':
                $m_invoice=$this->Dispatching_invoice_model;
                $m_customers=$this->Customers_model;

                $is_sales = $this->input->post('is_sales',TRUE);
                $dispatching_invoice_id = $this->input->post('dispatching_invoice_id',TRUE);

                if($is_sales == 1){ // sales invoice
                $m_si=$this->Sales_invoice_model;
                $arr_si_info=$m_si->get_list(
                    array('sales_invoice.sales_inv_no'=>$this->input->post('inv_no',TRUE)),
                    'sales_invoice.sales_invoice_id,sales_invoice.sales_inv_no'
                );
                $sales_invoice_id=(count($arr_si_info)>0?$arr_si_info[0]->sales_invoice_id:0);
                $sales_inv_no = (count($arr_si_info)>0?$arr_si_info[0]->sales_inv_no:0);
                $cash_invoice_id=0;
                $cash_inv_no='';


                }else if($is_sales == 2){// cash invoice
                $m_ci=$this->Cash_invoice_model;
                $arr_ci_info=$m_ci->get_list(
                    array('cash_invoice.cash_inv_no'=>$this->input->post('inv_no',TRUE)),
                    'cash_invoice.cash_invoice_id,cash_invoice.cash_inv_no'
                );
                $cash_invoice_id=(count($arr_ci_info)>0?$arr_ci_info[0]->cash_invoice_id:0);
                $cash_inv_no = (count($arr_ci_info)>0?$arr_ci_info[0]->cash_inv_no:0);
                $sales_invoice_id=0;
                $sales_inv_no='';
                }else{ // No Invoice Selected
                    $cash_invoice_id=0;
                    $cash_inv_no='';
                    $sales_invoice_id=0;
                    $sales_inv_no='';
                }

                $m_invoice->begin();
                $m_invoice->sales_invoice_id=$sales_invoice_id;
                $m_invoice->sales_inv_no=$sales_inv_no;
                $m_invoice->cash_invoice_id=$cash_invoice_id;
                $m_invoice->cash_inv_no=$cash_inv_no;
                $m_invoice->customer_type_id=$this->input->post('customer_type_id',TRUE);
                $m_invoice->customer_id=$this->input->post('customer',TRUE);
                $m_invoice->department_id=$this->input->post('department',TRUE);
                $m_invoice->remarks=$this->input->post('remarks',TRUE);
                $m_invoice->customer_id=$this->input->post('customer',TRUE);
                $m_invoice->salesperson_id=$this->input->post('salesperson_id',TRUE);
                $m_invoice->date_due=date('Y-m-d',strtotime($this->input->post('date_due',TRUE)));
                $m_invoice->date_invoice=date('Y-m-d',strtotime($this->input->post('date_invoice',TRUE)));
                $m_invoice->contact_person=$this->input->post('contact_person',TRUE);
                $m_invoice->total_overall_discount=$this->get_numeric_value($this->input->post('total_overall_discount',TRUE));
                $m_invoice->total_overall_discount_amount=$this->get_numeric_value($this->input->post('total_overall_discount_amount',TRUE));
                $m_invoice->total_discount=$this->get_numeric_value($this->input->post('summary_discount',TRUE));
                $m_invoice->total_before_tax=$this->get_numeric_value($this->input->post('summary_before_discount',TRUE));
                $m_invoice->total_tax_amount=$this->get_numeric_value($this->input->post('summary_tax_amount',TRUE));
                $m_invoice->total_after_tax=$this->get_numeric_value($this->input->post('summary_after_tax',TRUE));
                $m_invoice->total_after_discount=$this->get_numeric_value($this->input->post('total_after_discount',TRUE));
                $m_invoice->address=$this->input->post('address',TRUE);
                $m_invoice->modified_by_user=$this->session->user_id;
                $m_invoice->modify($dispatching_invoice_id);


                $m_invoice_items=$this->Dispatching_invoice_item_model;

                $m_invoice_items->delete_via_fk($dispatching_invoice_id); //delete previous items then insert those new

                $prod_id=$this->input->post('product_id',TRUE);
                $inv_price=$this->input->post('inv_price',TRUE);
                $inv_discount=$this->input->post('inv_discount',TRUE);
                $inv_line_total_discount=$this->input->post('inv_line_total_discount',TRUE);
                $inv_tax_rate=$this->input->post('inv_tax_rate',TRUE);
                $inv_qty=$this->input->post('inv_qty',TRUE);
                $inv_gross=$this->input->post('inv_gross',TRUE);
                $inv_line_total_price=$this->input->post('inv_line_total_price',TRUE);
                $inv_line_total_after_global=$this->input->post('inv_line_total_after_global',TRUE);
                $inv_tax_amount=$this->input->post('inv_tax_amount',TRUE);
                $inv_non_tax_amount=$this->input->post('inv_non_tax_amount',TRUE);
                $batch_no=$this->input->post('batch_no',TRUE);
                $exp_date=$this->input->post('exp_date',TRUE);
                $orig_so_price=$this->input->post('orig_so_price',TRUE);
                $cost_upon_invoice=$this->input->post('cost_upon_invoice',TRUE);
                $is_parent=$this->input->post('is_parent',TRUE);

                $m_products=$this->Products_model;

                for($i=0;$i<count($prod_id);$i++){

                    $m_invoice_items->dispatching_invoice_id=$dispatching_invoice_id;
                    $m_invoice_items->product_id=$this->get_numeric_value($prod_id[$i]);
                    $m_invoice_items->inv_line_total_after_global=$this->get_numeric_value($inv_line_total_after_global[$i]);
                    $m_invoice_items->inv_price=$this->get_numeric_value($inv_price[$i]);
                    $m_invoice_items->inv_discount=$this->get_numeric_value($inv_discount[$i]);
                    $m_invoice_items->inv_line_total_discount=$this->get_numeric_value($inv_line_total_discount[$i]);
                    $m_invoice_items->inv_tax_rate=$this->get_numeric_value($inv_tax_rate[$i]);
                    $m_invoice_items->inv_qty=$this->get_numeric_value($inv_qty[$i]);
                    $m_invoice_items->inv_gross=$this->get_numeric_value($inv_gross[$i]);
                    $m_invoice_items->inv_line_total_price=$this->get_numeric_value($inv_line_total_price[$i]);
                    $m_invoice_items->inv_tax_amount=$this->get_numeric_value($inv_tax_amount[$i]);
                    $m_invoice_items->inv_non_tax_amount=$this->get_numeric_value($inv_non_tax_amount[$i]);
                    //$m_invoice_items->batch_no=$batch_no[$i];
                    //$m_invoice_items->exp_date=date('Y-m-d', strtotime($exp_date[$i]));
                    $m_invoice_items->orig_so_price=$this->get_numeric_value($orig_so_price[$i]);
                    //$m_invoice_items->cost_upon_invoice=$this->get_numeric_value($cost_upon_invoice[$i]);

                    //unit id retrieval is change, because of TRIGGER restriction
                    $m_invoice_items->is_parent=$this->get_numeric_value($is_parent[$i]);
                    if($is_parent[$i] == '1'){
                                            $unit_id=$m_products->get_list(array('product_id'=>$prod_id[$i]));
                                            $m_invoice_items->unit_id=$unit_id[0]->parent_unit_id;
                    }else{
                                             $unit_id=$m_products->get_list(array('product_id'=>$prod_id[$i]));
                                            $m_invoice_items->unit_id=$unit_id[0]->child_unit_id;
                    }   
                    $m_invoice_items->save();
                }



                if($is_sales == 1){
                    $m_si = $this->Sales_invoice_model;
                    $m_si->order_status_id=$this->get_si_status($sales_invoice_id);
                    $m_si->modify($sales_invoice_id);
                }else if($is_sales == 2){
                    $m_ci = $this->Cash_invoice_model;
                    $m_ci->order_status_id=$this->get_ci_status($cash_invoice_id);
                    $m_ci->modify($cash_invoice_id);
                }



                // $sal_info=$m_invoice->get_list($sales_invoice_id,'sales_inv_no');
                // $m_trans=$this->Trans_model;
                // $m_trans->user_id=$this->session->user_id;
                // $m_trans->set('trans_date','NOW()');
                // $m_trans->trans_key_id=2; //CRUD
                // $m_trans->trans_type_id=17; // TRANS TYPE
                // $m_trans->trans_log='Updated Sales Invoice No: '.$sal_info[0]->sales_inv_no;
                // $m_trans->save();

                $m_invoice->commit();




                if($m_invoice->status()===TRUE){
                    $response['title'] = 'Success!';
                    $response['stat'] = 'success';
                    $response['msg'] = 'Dispatching Invoice Successfully Updated.';
                    $response['row_updated']=$this->response_rows($dispatching_invoice_id);

                    echo json_encode($response);
                }




                break;


            //***************************************************************************************
            case 'delete':
                $m_invoice=$this->Dispatching_invoice_model;
                $dispatching_invoice_id=$this->input->post('dispatching_invoice_id',TRUE);

                $m_invoice->set('date_deleted','NOW()'); //treat NOW() as function and not string
                $m_invoice->deleted_by_user=$this->session->user_id;//user that deleted the record
                $m_invoice->is_deleted=1;//mark as deleted
                $m_invoice->modify($dispatching_invoice_id);


               $info = $m_invoice->get_list($dispatching_invoice_id);
               $sales_invoice_id = $info[0]->sales_invoice_id;
               $cash_invoice_id =  $info[0]->cash_invoice_id;

               if($sales_invoice_id !=0){ // is sales
                    $m_si = $this->Sales_invoice_model;
                    $m_si->order_status_id=$this->get_si_status($sales_invoice_id);
                    $m_si->modify($sales_invoice_id);

               }else if($cash_invoice_id !=0){ // is cash invoice
                    $m_ci = $this->Cash_invoice_model;
                    $m_ci->order_status_id=$this->get_ci_status($cash_invoice_id);
                    $m_ci->modify($cash_invoice_id);     
               }


                // $sal_info=$m_invoice->get_list($sales_invoice_id,'sales_inv_no');
                // $m_trans=$this->Trans_model;
                // $m_trans->user_id=$this->session->user_id;
                // $m_trans->set('trans_date','NOW()');
                // $m_trans->trans_key_id=3; //CRUD
                // $m_trans->trans_type_id=17; // TRANS TYPE
                // $m_trans->trans_log='Deleted Sales Invoice No: '.$sal_info[0]->sales_inv_no;
                // $m_trans->save();

                $response['title']='Success!';
                $response['stat']='success';
                $response['msg']='Record successfully deleted.';
                echo json_encode($response);

                break;

            //***************************************************************************************
         
        }

    }



    function response_rows($filter_value){
        return $this->Dispatching_invoice_model->get_list(
            $filter_value,
            array(
                'dispatching_invoice.dispatching_invoice_id',
                'dispatching_invoice.dispatching_inv_no',
                'dispatching_invoice.remarks', 
                'dispatching_invoice.date_created',
                'dispatching_invoice.customer_id',
                'dispatching_invoice.inv_type',
                'dispatching_invoice.contact_person',
                'dispatching_invoice.cash_invoice_id',
                'dispatching_invoice.sales_invoice_id',
                'dispatching_invoice.customer_type_id',
                'DATE_FORMAT(dispatching_invoice.date_invoice,"%m/%d/%Y") as date_invoice',
                'DATE_FORMAT(dispatching_invoice.date_due,"%m/%d/%Y") as date_due',
                'departments.department_id',
                'departments.department_name',
                'customers.customer_name',
                'dispatching_invoice.salesperson_id',
                'dispatching_invoice.address',
                'sales_invoice.sales_inv_no',
                'cash_invoice.cash_inv_no'
            ),
            array(
                array('departments','departments.department_id=dispatching_invoice.department_id','left'),
                array('customers','customers.customer_id=dispatching_invoice.customer_id','left'),
                array('sales_invoice','sales_invoice.sales_invoice_id=dispatching_invoice.sales_invoice_id','left'),
                array('cash_invoice','cash_invoice.cash_invoice_id=dispatching_invoice.cash_invoice_id','left')
            ),
            'dispatching_invoice.dispatching_invoice_id DESC'
        );
    }

    function get_si_status($id){
        //NOTE : 1 means open, 2 means Closed, 3 means partially invoice
        $m_dispatching_invoice=$this->Dispatching_invoice_model;

        if(count($m_dispatching_invoice->get_list(
                array('dispatching_invoice.sales_invoice_id'=>$id,'dispatching_invoice.is_active'=>TRUE,'dispatching_invoice.is_deleted'=>FALSE),
                'dispatching_invoice.sales_invoice_id'))==0  
                ){
            return 1;

        }else{
            $m_si=$this->Dispatching_invoice_model;
            $row=$m_si->get_si_balance_qty($id);
            return ($row[0]->balance>0?3:2);
        }
    }

    function get_ci_status($id){
        //NOTE : 1 means open, 2 means Closed, 3 means partially invoice
        $m_dispatching_invoice=$this->Dispatching_invoice_model;
        if(count($m_dispatching_invoice->get_list(
                array('dispatching_invoice.cash_invoice_id'=>$id,'dispatching_invoice.is_active'=>TRUE,'dispatching_invoice.is_deleted'=>FALSE),
                'dispatching_invoice.cash_invoice_id'))==0  
                ){
            return 1;

        }else{
            $m_si=$this->Dispatching_invoice_model;
            $row=$m_si->get_ci_balance_qty($id);
            return ($row[0]->balance>0?3:2);
        }
    }
}
