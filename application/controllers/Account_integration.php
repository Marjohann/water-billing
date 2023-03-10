<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_integration extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();
        $this->load->model(array(
                'Account_title_model',
                'Account_integration_model',
                'Account_year_model',
                'Users_model',
                'Invoice_counter_model',
                'Accounting_period_model',
                'Journal_info_model',
                'Sales_invoice_model',
                'Users_model',
                'Customers_model',
                'Suppliers_model',
                'Departments_model',
                'Purchasing_integration_model',
                'Trans_model',
                'Matrix_residential_model',
                'Matrix_commercial_model',
                'Sched_expense_integration'
            )

        );
    }

    public function index() {
        $this->Users_model->validate();
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', TRUE);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', TRUE);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', TRUE);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', TRUE);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', TRUE);
        $data['title'] = 'Account Integration';

        $data['accounts'] = $this->Account_title_model->get_list(array('is_deleted'=>FALSE));
        $current_accounts= $this->Account_integration_model->get_list();
        $data['current_accounts'] =$current_accounts[0];
        $data['users_counter']=$this->Users_model->get_user_invoice_counter();

        $current_accounts_purchasing= $this->Purchasing_integration_model->get_list();
        $data['current_accounts_purchasing'] =$current_accounts_purchasing[0];
        $data['departments'] = $this->Departments_model->get_list(array('is_active'=>TRUE, 'is_deleted'=>FALSE));
        $data['customers']=$this->Customers_model->get_list('is_active=TRUE AND is_deleted=FALSE');
        $data['suppliers']=$this->Suppliers_model->get_list('is_active=TRUE AND is_deleted=FALSE');
        $data['residentials']=$this->Matrix_residential_model->get_list(array('is_active'=>TRUE,'is_deleted'=>FALSE));
        $data['commercials']=$this->Matrix_commercial_model->get_list(array('is_active'=>TRUE,'is_deleted'=>FALSE));




        //grand parent account only
        $data['expenses']=$this->Account_title_model->get_list(
            'account_titles.is_active=1 AND account_titles.is_deleted=0 AND at.account_type_id=5 AND account_titles.account_id IN(SELECT x.grand_parent_id FROM account_titles as x WHERE x.parent_account_id=0)',
            array(
                'account_titles.*',
                'ac.account_class',
                'at.account_type',
                'IF(ISNULL(sei.account_id),1,2)as group_id'
            ),
            array(
                array( 'account_classes as ac','ac.account_class_id=account_titles.account_class_id','left'),
                array( 'account_types as at','at.account_type_id=ac.account_type_id','left'),
                array( 'sched_expense_integration as sei','sei.account_id=account_titles.account_id','left')
            ),
            'account_title,account_no'
        );
        (in_array('6-3',$this->session->user_rights)? 
        $this->load->view('account_integration_view', $data)
        :redirect(base_url('dashboard')));
        

    }


    public function transaction($txn=null){
        switch($txn){
            case 'save-counter':
                $m_counter=$this->Invoice_counter_model;

                $id=$this->input->post('id',TRUE);
                $start=$this->input->post('start',TRUE);
                $end=$this->input->post('end',TRUE);

                if($this->validate_counter($start,$end,$id)){

                    $counter_exist=$this->Invoice_counter_model->get_list(array('invoice_counter.user_id'=>$id));
                    if(count($counter_exist)>0){ //if counter found, just update it
                        $m_counter->user_id=$id;
                        $m_counter->counter_start=$start;
                        $m_counter->counter_end=$end;
                        $m_counter->modify($counter_exist[0]->counter_id);
                    }else{
                        $m_counter->user_id=$id;
                        $m_counter->counter_start=$start;
                        $m_counter->counter_end=$end;
                        $m_counter->save();
                    }

                    $response['stat']="success";
                    $response['title']="Success!";
                    $response['msg']="Invoice counter successfully saved.";

                    echo json_encode($response);
                }

                break;

            case 'save':
                $m_integration=$this->Account_integration_model;
                $m_integration->retained_earnings_id=$this->input->post('retained_earnings_id',TRUE);
                $m_integration->petty_cash_account_id=$this->input->post('petty_cash_account_id',TRUE);
                $m_integration->depreciation_expense_debit_id=$this->input->post('depreciation_expense_debit_id',TRUE);
                $m_integration->depreciation_expense_credit_id=$this->input->post('depreciation_expense_credit_id',TRUE);        
                $m_integration->modify(1);

                $response['stat']="success";
                $response['title']="Success!";
                $response['msg']="Other Account successfully integrated.";

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=57; // TRANS TYPE
                $m_trans->trans_log='Updated System General Configuration';
                $m_trans->save();
                echo json_encode($response);

                break;



            case 'save_water_billing':
                $m_integration=$this->Account_integration_model;
                $m_integration->default_matrix_residential_id=$this->input->post('default_matrix_residential_id',TRUE);
                $m_integration->default_matrix_commercial_id=$this->input->post('default_matrix_commercial_id',TRUE);      
                $m_integration->billing_meter_account_id=$this->input->post('billing_meter_account_id',TRUE);      
                $m_integration->billing_receivable_penalty_account_id=$this->input->post('billing_receivable_penalty_account_id',TRUE);  
                $m_integration->billing_penalty_account_id=$this->input->post('billing_penalty_account_id',TRUE);   
                $m_integration->billing_department_id=$this->input->post('billing_department_id',TRUE);      
                $m_integration->billing_customer_id=$this->input->post('billing_customer_id',TRUE);      
                $m_integration->billing_security_deposit_account_id=$this->input->post('billing_security_deposit_account_id',TRUE);     
                $m_integration->modify(1);

                $response['stat']="success";
                $response['title']="Success!";
                $response['msg']="Water Billing Settings Successfully Integrated.";

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=57; // TRANS TYPE
                $m_trans->trans_log='Updated Water Billing System Configuration';
                $m_trans->save();
                echo json_encode($response);

                break;








            case 'save_supplier':
                $m_integration=$this->Account_integration_model;

                //suppliers
                $m_integration->input_tax_account_id=$this->input->post('input_tax_account_id',TRUE);
                $m_integration->payable_account_id=$this->input->post('payable_account_id',TRUE);
                $m_integration->payable_discount_account_id=$this->input->post('payable_discount_account_id',TRUE);
                $m_integration->payment_to_supplier_id=$this->input->post('payment_to_supplier_id',TRUE);
                $m_integration->supplier_wtax_account_id=$this->input->post('supplier_wtax_account_id',TRUE);


        
                $m_integration->modify(1);

                $response['stat']="success";
                $response['title']="Success!";
                $response['msg']="Supplier Accounts successfully integrated.";

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=57; // TRANS TYPE
                $m_trans->trans_log='Updated Supplier Configuration';
                $m_trans->save();
                echo json_encode($response);

                break;

            case 'save_customer':
                $m_integration=$this->Account_integration_model;

                // //customers
                $m_integration->output_tax_account_id=$this->input->post('output_tax_account_id',TRUE);
                $m_integration->receivable_account_id=$this->input->post('receivable_account_id',TRUE);
                $m_integration->receivable_discount_account_id=$this->input->post('receivable_discount_account_id',TRUE);
                $m_integration->payment_from_customer_id=$this->input->post('payment_from_customer_id',TRUE);       
                $m_integration->modify(1);

                $response['stat']="success";
                $response['title']="Success!";
                $response['msg']="Customer Accounts successfully integrated.";

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=57; // TRANS TYPE
                $m_trans->trans_log='Updated System General Configuration Customer';
                $m_trans->save();
                echo json_encode($response);

                break;

            case 'save_inventory':
                $m_integration=$this->Account_integration_model;


                // inventory
                $m_integration->sales_invoice_inventory=$this->get_numeric_value($this->input->post('sales_invoice_inventory',TRUE));
                $m_integration->cash_invoice_inventory=$this->get_numeric_value($this->input->post('cash_invoice_inventory',TRUE));
                $m_integration->dispatching_invoice_inventory=$this->get_numeric_value($this->input->post('dispatching_invoice_inventory',TRUE));
                $m_integration->modify(1);

                $response['stat']="success";
                $response['title']="Success!";
                $response['msg']="Inventory Configuration successfully integrated.";

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=57; // TRANS TYPE
                $m_trans->trans_log='Updated System General Configuration Inventory ';
                $m_trans->save();
                echo json_encode($response);

                break;



            case 'save_adjustments':
                $m_purchasing_integration=$this->Purchasing_integration_model;
                $m_purchasing_integration->adj_supplier_id=$this->input->post('adj_supplier_id',TRUE);
                $m_purchasing_integration->adj_debit_id=$this->input->post('adj_debit_id',TRUE);
                $m_purchasing_integration->adj_credit_id=$this->input->post('adj_credit_id',TRUE);


                $m_purchasing_integration->modify(1);

                $response['stat']="success";
                $response['title']="Success!";
                $response['msg']="Adjustment Accounts successfully integrated.";

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=58; // TRANS TYPE
                $m_trans->trans_log='Updated System Purchasing Configuration Adjustments';
                $m_trans->save();
                echo json_encode($response);

                break;

            case 'save_adjustments':
                $m_purchasing_integration=$this->Purchasing_integration_model;
                $m_purchasing_integration->adj_supplier_id=$this->input->post('adj_supplier_id',TRUE);
                $m_purchasing_integration->adj_debit_id=$this->input->post('adj_debit_id',TRUE);
                $m_purchasing_integration->adj_credit_id=$this->input->post('adj_credit_id',TRUE);
                $m_purchasing_integration->modify(1);

                $response['stat']="success";
                $response['title']="Success!";
                $response['msg']="Adjustment Accounts successfully integrated.";

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=58; // TRANS TYPE
                $m_trans->trans_log='Updated System Purchasing Configuration Adjustments';
                $m_trans->save();
                echo json_encode($response);

                break;

            case 'save_item_transfer':
                $m_purchasing_integration=$this->Purchasing_integration_model;
                $m_purchasing_integration->iss_supplier_id=$this->input->post('iss_supplier_id',TRUE);
                $m_purchasing_integration->iss_debit_id=$this->input->post('iss_debit_id',TRUE);
                $m_purchasing_integration->modify(1);
                $response['stat']="success";
                $response['title']="Success!";
                $response['msg']="Item Transfer Accounts successfully integrated.";

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=58; // TRANS TYPE
                $m_trans->trans_log='Updated System Purchasing Configuration Item Transfer';
                $m_trans->save();
                echo json_encode($response);

                break;

            case 'save_material_issuance':
                $m_purchasing_integration=$this->Purchasing_integration_model;
                $m_purchasing_integration->iss_mat_debit_id=$this->input->post('iss_mat_debit_id',TRUE);
                $m_purchasing_integration->iss_mat_supplier_id=$this->input->post('iss_mat_supplier_id',TRUE);
                $m_purchasing_integration->modify(1);

                $response['stat']="success";
                $response['title']="Success!";
                $response['msg']="Material Issuances Accounts successfully integrated.";

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=58; // TRANS TYPE
                $m_trans->trans_log='Updated System Purchasing Configuration Material Issuance';
                $m_trans->save();
                echo json_encode($response);

                break;
            case 'get-account-year':
                $m_year=$this->Accounting_period_model;

                $response['data']=$m_year->get_list(
                    null,
                    array(
                        'CONCAT(DATE_FORMAT(period_start,"%M %d, %Y")," to ",DATE_FORMAT(period_end,"%M %d, %Y")) as date_covered',
                        'accounting_period.*',
                        'CONCAT_WS(" ",ua.user_fname,ua.user_lname) as user'

                    ),
                    array(
                        array('user_accounts as ua','ua.user_id=accounting_period.closed_by_user','left')
                    )
                );
                echo json_encode($response);
                break;

            case 'close-period':
                $m_acc_period=$this->Accounting_period_model;
                $m_journal_info=$this->Journal_info_model;

                $up_to_date=date('Y-m-d',strtotime($this->input->post('date')));


                //get period start and end
                /*
                 * if count(accounting_period_id)>0 then
                 *      START=get max date of period on accounting period +1 Day
                 *
                 * else
                 *      START=get min date_txn from journal info
                 *
                 * end if
                 *
                 * */

                //get the first date of journal info
                $journal=$m_journal_info->get_list(
                    "is_active=TRUE AND is_deleted=FALSE AND accounting_period_id=0 AND date_txn<='".$up_to_date."'",
                    array(
                        'MIN(date_txn) as start_date'
                    ),
                    null,
                    null,
                    null,
                    TRUE,
                    null,
                    'NOT ISNULL(start_date)'
                );

                //validate
                if(count($journal)==0){ //there is no accounting transaction posted
                    $response['stat']="error";
                    $response['title']="No Transaction!";
                    $response['msg']="Sorry, you cannot closed current period because there is no open accounting transactions on the date specified.";
                    die(json_encode($response));
                }

                $period_rows=$m_acc_period->get_list();
                if(count($period_rows)>0){ //if there is previous accounting period, we will based on last date txn is closed then add 1 day
                    $account_period=$m_acc_period->get_list(
                        null,
                        array(
                            'MAX(period_end) as last_date'
                        ),
                        null,
                        null,
                        null,
                        TRUE,
                        null,
                        'NOT ISNULL(last_date)'
                    );
                    $last_date=date('Y-m-d',strtotime($account_period[0]->last_date));

                    //used the Last Date Period of Closed Accounting + 1 as the Start Date
                    $new_start_date=date('Y-m-d',strtotime("1 days", strtotime($last_date)));
                }else{
                    //if there is no previous period that is closed, used the first txn date posted as the START DATE
                    $new_start_date=date('Y-m-d',strtotime($journal[0]->start_date));
                }


                $m_acc_period->begin();

                $m_acc_period->set('date_time_closed','NOW()');
                $m_acc_period->period_start=$new_start_date;
                $m_acc_period->period_end=$up_to_date;
                $m_acc_period->remarks=$this->input->post('remarks',TRUE);
                $m_acc_period->closed_by_user=$this->session->user_id;
                $m_acc_period->save();

                $account_year_id=$m_acc_period->last_insert_id();

                //update journal
                $m_journal_info->accounting_period_id=$account_year_id;
                $m_journal_info->modify(
                    "accounting_period_id=0 AND date_txn<='".$up_to_date."'"
                );


                $m_acc_period->commit();

                $response['stat']="success";
                $response['title']="Success!";
                $response['msg']="You have successfully closed accounting transactions.";
                $response['row_added']=$this->get_response_rows($account_year_id);



                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=8; //CRUD
                $m_trans->trans_type_id=57; // TRANS TYPE
                $m_trans->trans_log='Closed Accounting Period ';
                $m_trans->save();

                echo json_encode($response);

                break;
        }





    }


    function get_response_rows($filter){
        $m_acc_period=$this->Accounting_period_model;

        return $m_acc_period->get_list(
            $filter,
            array(
                'CONCAT(DATE_FORMAT(period_start,"%M %d, %Y")," to ",DATE_FORMAT(period_end,"%M %d, %Y")) as date_covered',
                'accounting_period.*',
                'CONCAT_WS(" ",ua.user_fname,ua.user_lname) as user'

            ),
            array(
                array('user_accounts as ua','ua.user_id=accounting_period.closed_by_user','left')
            ),

            'accounting_period.accounting_period_id DESC'
        );
    }


    function validate_counter($start,$end,$id){

        if($start>=$end){
            $response['title']="Error!";
            $response['stat']="error";
            $response['msg']="Invalid invoice counter. Please check end invoice number.";

            die(json_encode($response));
        }

        //check if start no is not yet used
        $start_exist=$this->Sales_invoice_model->get_list(array('sales_inv_no'=>$start));
        if(count($start_exist)>0){
            $response['title']="Invalid Counter Start!";
            $response['stat']="error";
            $response['msg']="Counter start is already used. Please check invoice range.";

            die(json_encode($response));
        }

        //check if end no is not yet used
        $end_exist=$this->Sales_invoice_model->get_list(array('sales_inv_no'=>$end));
        if(count($end_exist)>0){
            $response['title']="Invalid Counter End!";
            $response['stat']="error";
            $response['msg']="Counter end is already used. Please check invoice range.";

            die(json_encode($response));
        }




        $count_exist=$this->Invoice_counter_model->get_list(
            '(('.$start.' BETWEEN counter_start AND counter_end) OR ('.$end.' BETWEEN counter_start AND counter_end)) AND  invoice_counter.user_id NOT IN('.$id.')',


            array(
                'invoice_counter.*',
                'CONCAT_WS(" ",user_accounts.user_fname,user_accounts.user_lname) as user_fullname'
            ),

            array(
                array('user_accounts','user_accounts.user_id=invoice_counter.user_id','left')
            )
        );

        if(count($count_exist)>0){
            $response['title']="Error!";
            $response['stat']="error";
            $response['msg']="Invalid invoice counter. The range you specified is cover under ".$count_exist[0]->user_fullname." (".$count_exist[0]->counter_start." to ".$count_exist[0]->counter_end.").";

            die(json_encode($response));
        }


        $count_exist=$this->Invoice_counter_model->get_list(
           " ((counter_start BETWEEN ".$start." AND ".$end.") OR (counter_end BETWEEN ".$start." AND ".$end.")) AND invoice_counter.user_id NOT IN(".$id.")",

            array(
                'invoice_counter.*',
                'CONCAT_WS(" ",user_accounts.user_fname,user_accounts.user_lname) as user_fullname'
            ),

            array(
                array('user_accounts','user_accounts.user_id=invoice_counter.user_id','left')
            )

        );

        if(count($count_exist)>0){

            $response['title']="Error!";
            $response['stat']="error";
            $response['msg']="Invalid invoice counter. The range you specified is cover under ".$count_exist[0]->user_fullname." (".$count_exist[0]->counter_start." to ".$count_exist[0]->counter_end.").";

            die(json_encode($response));
        }




        return TRUE;
    }




}
