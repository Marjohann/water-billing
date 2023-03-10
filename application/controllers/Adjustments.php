<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Adjustments extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();

        $this->load->model('Adjustment_model');
        $this->load->model('Adjustment_item_model');
        $this->load->model('Departments_model');
        $this->load->model('Products_model');
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


        //data required by active view
        $data['departments']=$this->Departments_model->get_list(
            array('departments.is_active'=>TRUE,'departments.is_deleted'=>FALSE)
        );

        $data['refproducts']=$this->Refproduct_model->get_list(
            'is_deleted=FALSE'
        );

        $data['title'] = 'Inventory Adjustment';
        
        (in_array('15-3',$this->session->user_rights)? 
        $this->load->view('adjustment_view', $data)
        :redirect(base_url('dashboard')));

    }




    function transaction($txn = null,$id_filter=null) {
        switch ($txn){

            case'close-invoice':  
            $m_sales=$this->Adjustment_model;
            $adjustment_id =$this->input->post('adjustment_id');
            $m_sales->closing_reason = $this->input->post('closing_reason');
            $m_sales->closed_by_user = $this->session->user_id;
            $m_sales->is_closed = TRUE;
            $m_sales->modify($adjustment_id);


            $adj_inv_info=$m_sales->get_list($adjustment_id,'adjustment_code');
            $m_trans=$this->Trans_model;
            $m_trans->user_id=$this->session->user_id;
            $m_trans->set('trans_date','NOW()');
            $m_trans->trans_key_id=11; //CRUD
            $m_trans->trans_type_id=15; // TRANS TYPE
            $m_trans->trans_log='Closed/Did Not Post Adjustment No: '.$adj_inv_info[0]->adjustment_code.' from General Journal Pending with reason: '.$this->input->post('closing_reason');
            $m_trans->save();
            $response['title'] = 'Success!';
            $response['stat'] = 'success';
            $response['msg'] = 'Adjustments successfully closed.';
            echo json_encode($response);    
            break;

            
            
            case 'list':  //this returns JSON of Issuance to be rendered on Datatable
                $m_adjustment=$this->Adjustment_model;
                $response['data']=$this->response_rows(
                    "adjustment_info.is_active=TRUE AND adjustment_info.is_deleted=FALSE".($id_filter==null?"":" AND adjustment_info.adjustment_id=".$id_filter)
                );
                echo json_encode($response);
                break;

             case 'adjustment-for-review': 
                $m_adjustment=$this->Adjustment_model;
                $response['data']=$m_adjustment->get_adjustments_for_review();
                echo json_encode($response);
                break;

            ////****************************************items/products of selected Items***********************************************
            case 'items': //items on the specific PO, loads when edit button is called
                $m_items=$this->Adjustment_item_model;
                $response['data']=$m_items->get_list(
                    array('adjustment_id'=>$id_filter),
                    array(
                        'adjustment_items.*',
                        'products.product_code',
                        'products.product_desc',
                        'DATE_FORMAT(adjustment_items.exp_date,"%m/%d/%Y")as expiration',                            
                        'products.purchase_cost',
                            'products.is_bulk',
                            'products.child_unit_id',
                            'products.parent_unit_id',
                            'products.child_unit_desc',
                            '(SELECT units.unit_name  FROM units WHERE  units.unit_id = products.parent_unit_id) as parent_unit_name',
                            '(SELECT units.unit_name  FROM units WHERE  units.unit_id = products.child_unit_id) as child_unit_name'),
  
                    array(
                        array('products','products.product_id=adjustment_items.product_id','left'),
                        array('units','units.unit_id=adjustment_items.unit_id','left')
                    ),
                    'adjustment_items.adjustment_item_id DESC'
                );


                echo json_encode($response);
                break;


            //***************************************create new Items************************************************
            case 'create':
                $m_adjustment=$this->Adjustment_model;

                if(count($m_adjustment->get_list(array('adjustment_code'=>$this->input->post('adjustment_code',TRUE))))>0){
                    $response['title'] = 'Invalid!';
                    $response['stat'] = 'error';
                    $response['msg'] = 'Slip No. already exists.';

                    echo json_encode($response);
                    exit;
                }



                $m_adjustment->begin();

                //$m_adjustment->set('date_adjusted','NOW()'); //treat NOW() as function and not string
                $m_adjustment->set('date_created','NOW()'); //treat NOW() as function and not string


                $m_adjustment->department_id=$this->input->post('department',TRUE);
                $m_adjustment->adjustment_type=$this->input->post('adjustment_type',TRUE);
                $m_adjustment->remarks=$this->input->post('remarks',TRUE);
                $m_adjustment->date_adjusted=date('Y-m-d',strtotime($this->input->post('date_adjusted',TRUE)));
                $m_adjustment->total_discount=$this->get_numeric_value($this->input->post('summary_discount',TRUE));
                $m_adjustment->total_before_tax=$this->get_numeric_value($this->input->post('summary_before_discount',TRUE));
                $m_adjustment->total_tax_amount=$this->get_numeric_value($this->input->post('summary_tax_amount',TRUE));
                $m_adjustment->total_after_tax=$this->get_numeric_value($this->input->post('summary_after_tax',TRUE));
                $m_adjustment->posted_by_user=$this->session->user_id;
                $m_adjustment->save();

                $adjustment_id=$m_adjustment->last_insert_id();

                $m_adjustment_items=$this->Adjustment_item_model;

                $prod_id=$this->input->post('product_id',TRUE);
                $adjust_qty=$this->input->post('adjust_qty',TRUE);
                $adjust_price=$this->input->post('adjust_price',TRUE);
                $adjust_discount=$this->input->post('adjust_discount',TRUE);
                $adjust_line_total_discount=$this->input->post('adjust_line_total_discount',TRUE);
                $adjust_tax_rate=$this->input->post('adjust_tax_rate',TRUE);
                $adjust_line_total_price=$this->input->post('adjust_line_total_price',TRUE);
                $adjust_tax_amount=$this->input->post('adjust_tax_amount',TRUE);
                $adjust_non_tax_amount=$this->input->post('adjust_non_tax_amount',TRUE);
                $is_parent=$this->input->post('is_parent',TRUE);
                $m_products=$this->Products_model;

                for($i=0;$i<count($prod_id);$i++){

                    $m_adjustment_items->adjustment_id=$adjustment_id;
                    $m_adjustment_items->product_id=$this->get_numeric_value($prod_id[$i]);
                    $m_adjustment_items->adjust_qty=$this->get_numeric_value($adjust_qty[$i]);
                    $m_adjustment_items->adjust_price=$this->get_numeric_value($adjust_price[$i]);
                    $m_adjustment_items->adjust_discount=$this->get_numeric_value($adjust_discount[$i]);
                    $m_adjustment_items->adjust_line_total_discount=$this->get_numeric_value($adjust_line_total_discount[$i]);
                    $m_adjustment_items->adjust_tax_rate=$this->get_numeric_value($adjust_tax_rate[$i]);
                    $m_adjustment_items->adjust_line_total_price=$this->get_numeric_value($adjust_line_total_price[$i]);
                    $m_adjustment_items->adjust_tax_amount=$this->get_numeric_value($adjust_tax_amount[$i]);
                    $m_adjustment_items->adjust_non_tax_amount=$this->get_numeric_value($adjust_non_tax_amount[$i]);

                        $m_adjustment_items->is_parent=$this->get_numeric_value($is_parent[$i]);
                        if($is_parent[$i] == '1'){
                            $m_adjustment_items->set('unit_id','(SELECT parent_unit_id FROM products WHERE product_id='.(int)$this->get_numeric_value($prod_id[$i]).')');
                        }else{
                             $m_adjustment_items->set('unit_id','(SELECT child_unit_id FROM products WHERE product_id='.(int)$this->get_numeric_value($prod_id[$i]).')');
                        } 

                    $m_adjustment_items->save();
                }

                //update invoice number base on formatted last insert id
                $m_adjustment->adjustment_code='ADJ-'.date('Ymd').'-'.$adjustment_id;
                $m_adjustment->modify($adjustment_id);

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=1; //CRUD
                $m_trans->trans_type_id=15; // TRANS TYPE
                $m_trans->trans_log='Created Adjustment No: ADJ-'.date('Ymd').'-'.$adjustment_id;
                $m_trans->save();

                $m_adjustment->commit();

                if($m_adjustment->status()===TRUE){
                    $response['title'] = 'Success!';
                    $response['stat'] = 'success';
                    $response['msg'] = 'Items successfully Adjusted.';
                    $response['row_added']=$this->response_rows($adjustment_id);

                    echo json_encode($response);
                }

                break;


            ////***************************************update Items************************************************
            case 'update':
                $m_adjustment=$this->Adjustment_model;
                $adjustment_id=$this->input->post('adjustment_id',TRUE);


                $m_adjustment->begin();

                $m_adjustment->department_id=$this->input->post('department',TRUE);
                $m_adjustment->remarks=$this->input->post('remarks',TRUE);
                $m_adjustment->adjustment_type=$this->input->post('adjustment_type',TRUE);
                $m_adjustment->date_adjusted=date('Y-m-d',strtotime($this->input->post('date_adjusted',TRUE)));
                $m_adjustment->total_discount=$this->get_numeric_value($this->input->post('summary_discount',TRUE));
                $m_adjustment->total_before_tax=$this->get_numeric_value($this->input->post('summary_before_discount',TRUE));
                $m_adjustment->total_tax_amount=$this->get_numeric_value($this->input->post('summary_tax_amount',TRUE));
                $m_adjustment->total_after_tax=$this->get_numeric_value($this->input->post('summary_after_tax',TRUE));
                $m_adjustment->modified_by_user=$this->session->user_id;
                $m_adjustment->modify($adjustment_id);


                $m_adjustment_items=$this->Adjustment_item_model;

                $tmp_prod_id = $m_adjustment_items->get_list(
                    array('adjustment_id'=>$adjustment_id),
                    'product_id'
                );

                $m_adjustment_items->delete_via_fk($adjustment_id); //delete previous items then insert those new

                $prod_id=$this->input->post('product_id',TRUE);
                $adjust_price=$this->input->post('adjust_price',TRUE);
                $adjust_discount=$this->input->post('adjust_discount',TRUE);
                $adjust_line_total_discount=$this->input->post('adjust_line_total_discount',TRUE);
                $adjust_tax_rate=$this->input->post('adjust_tax_rate',TRUE);
                $adjust_qty=$this->input->post('adjust_qty',TRUE);
                $adjust_line_total_price=$this->input->post('adjust_line_total_price',TRUE);
                $adjust_tax_amount=$this->input->post('adjust_tax_amount',TRUE);
                $adjust_non_tax_amount=$this->input->post('adjust_non_tax_amount',TRUE);
                $is_parent=$this->input->post('is_parent',TRUE);

                for($i=0;$i<count($prod_id);$i++){

                    $m_adjustment_items->adjustment_id=$adjustment_id;
                    $m_adjustment_items->product_id=$this->get_numeric_value($prod_id[$i]);
                    $m_adjustment_items->adjust_price=$this->get_numeric_value($adjust_price[$i]);
                    $m_adjustment_items->adjust_discount=$this->get_numeric_value($adjust_discount[$i]);
                    $m_adjustment_items->adjust_line_total_discount=$this->get_numeric_value($adjust_line_total_discount[$i]);
                    $m_adjustment_items->adjust_tax_rate=$this->get_numeric_value($adjust_tax_rate[$i]);
                    $m_adjustment_items->adjust_qty=$this->get_numeric_value($adjust_qty[$i]);
                    $m_adjustment_items->adjust_line_total_price=$this->get_numeric_value($adjust_line_total_price[$i]);
                    $m_adjustment_items->adjust_tax_amount=$this->get_numeric_value($adjust_tax_amount[$i]);
                    $m_adjustment_items->adjust_non_tax_amount=$this->get_numeric_value($adjust_non_tax_amount[$i]);

                    //$m_adjustment_items->set('unit_id','(SELECT unit_id FROM products WHERE product_id='.(int)$prod_id[$i].')');

                    $m_adjustment_items->is_parent=$this->get_numeric_value($is_parent[$i]);
                    if($is_parent[$i] == '1'){
                        $m_adjustment_items->set('unit_id','(SELECT parent_unit_id FROM products WHERE product_id='.(int)$this->get_numeric_value($prod_id[$i]).')');
                    }else{
                         $m_adjustment_items->set('unit_id','(SELECT child_unit_id FROM products WHERE product_id='.(int)$this->get_numeric_value($prod_id[$i]).')');
                    } 

                    $m_adjustment_items->save();
                }


                $adj_info=$m_adjustment->get_list($adjustment_id,'adjustment_code');
                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=15; // TRANS TYPE
                $m_trans->trans_log='Updated Adjustment No: '.$adj_info[0]->adjustment_code;
                $m_trans->save();

                $m_adjustment->commit();
                if($m_adjustment->status()===TRUE){
                    $response['title'] = 'Success!';
                    $response['stat'] = 'success';
                    $response['msg'] = 'Adjusted items successfully updated.';
                    $response['row_updated']=$this->response_rows($adjustment_id);

                    echo json_encode($response);
                }


                break;


            //***************************************************************************************
            case 'delete':
                $m_adjustment=$this->Adjustment_model;
                $m_adjustment_items=$this->Adjustment_item_model;
                $adjustment_id=$this->input->post('adjustment_id',TRUE);
                $prod_id=$this->input->post('product_id',TRUE);

                //mark Items as deleted
                $m_adjustment->set('date_deleted','NOW()'); //treat NOW() as function and not string
                $m_adjustment->deleted_by_user=$this->session->user_id;//user that deleted the record
                $m_adjustment->is_deleted=1;//mark as deleted
                $m_adjustment->modify($adjustment_id);

                //end update product on_hand after Adjustment is deleted...
                $adj_info=$m_adjustment->get_list($adjustment_id,'adjustment_code');
                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=3; //CRUD
                $m_trans->trans_type_id=15; // TRANS TYPE
                $m_trans->trans_log='Deleted Adjustment No: '.$adj_info[0]->adjustment_code;
                $m_trans->save();
                
                $response['title']='Success!';
                $response['stat']='success';
                $response['msg']='Record successfully deleted.';
                echo json_encode($response);

                break;

            //***************************************************************************************
        }

    }



//**************************************user defined*************************************************
    function response_rows($filter_value){
        return $this->Adjustment_model->get_list(
            $filter_value,
            array(
                'adjustment_info.adjustment_id',
                'adjustment_info.adjustment_code',
                'adjustment_info.remarks',
                'adjustment_info.adjustment_type',
                'adjustment_info.is_journal_posted',
                'adjustment_info.date_created',
                'DATE_FORMAT(adjustment_info.date_adjusted,"%m/%d/%Y") as date_adjusted',
                'departments.department_id',
                'departments.department_name'
            ),
            array(
                array('departments','departments.department_id=adjustment_info.department_id','left')
            ),
            'adjustment_info.adjustment_id DESC'
        );
    }


//***************************************************************************************





}
