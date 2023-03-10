<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts_receivable extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();

        $this->load->model(
            array(
                'Customers_model',
                'Account_title_model',
                'Payment_method_model',
                'Journal_info_model',
                'Journal_account_model',
                'Tax_types_model',
                'Sales_invoice_model',
                'Departments_model',
                'Users_model',
                'Accounting_period_model',
                'Trans_model',
                'Customer_type_model',
                'Meter_reading_input_model',
                'Billing_penalty_model'
 
            )
        );

    }

    public function index() {
        $this->Users_model->validate();
        //default resources of the active view
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', TRUE);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', TRUE);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', TRUE);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', TRUE);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', TRUE);

        $data['customers']=$this->Customers_model->get_list('is_active=TRUE AND is_deleted=FALSE');
        $data['departments']=$this->Departments_model->get_list('is_active=TRUE AND is_deleted=FALSE');
        $data['accounts']=$this->Account_title_model->get_list('is_active=TRUE AND is_deleted=FALSE');
        $data['methods']=$this->Payment_method_model->get_list('is_active=TRUE AND is_deleted=FALSE');
        $data['customer_type']=$this->Customer_type_model->get_list('is_deleted=FALSE');

        $data['title'] = 'Accounts Receivable';
        (in_array('1-4',$this->session->user_rights)? 
        $this->load->view('accounts_receivable_view', $data)
        :redirect(base_url('dashboard')));
        
    }


    public function transaction($txn=null){
        switch($txn){
            case 'list':
                $m_journal=$this->Journal_info_model;
                $tsd = date('Y-m-d',strtotime($this->input->get('tsd')));
                $ted = date('Y-m-d',strtotime($this->input->get('ted')));
                $additional = " AND DATE(journal_info.date_txn) BETWEEN '$tsd' AND '$ted'";
                $response['data']=$this->get_response_rows(null,$additional);
                echo json_encode($response);
                break;
            case 'get-entries':
                $journal_id=$this->input->get('id');
                $m_accounts=$this->Account_title_model;
                $m_journal_accounts=$this->Journal_account_model;

                $data['accounts']=$m_accounts->get_list(array('is_deleted'=>FALSE));
                $data['entries']=$m_journal_accounts->get_list('journal_accounts.journal_id='.$journal_id);

                $this->load->view('template/journal_entries', $data);
                break;
            case 'create' :
                $m_journal=$this->Journal_info_model;
                $m_journal_accounts=$this->Journal_account_model;

                //validate if still in valid range
                $valid_range=$this->Accounting_period_model->get_list("'".date('Y-m-d',strtotime($this->input->post('date_txn',TRUE)))."'<=period_end");
                if(count($valid_range)>0){
                    $response['stat']='error';
                    $response['title']='<b>Accounting Period is Closed!</b>';
                    $response['msg']='Please make sure transaction date is valid!<br />';
                    die(json_encode($response));
                }

                $m_journal->customer_id=$this->input->post('customer_id',TRUE);
                $m_journal->department_id=$this->input->post('department_id',TRUE);
                $m_journal->remarks=$this->input->post('remarks',TRUE);
                $m_journal->date_txn=date('Y-m-d',strtotime($this->input->post('date_txn',TRUE)));
                $m_journal->book_type='SJE';
                $m_journal->ref_no=$this->input->post('ref_no',TRUE);
                $m_journal->is_sales=1;

                //for audit details
                $m_journal->set('date_created','NOW()');
                $m_journal->created_by_user=$this->session->user_id;
                $m_journal->save();

                $journal_id=$m_journal->last_insert_id();
                $accounts=$this->input->post('accounts',TRUE);
                $memos=$this->input->post('memo',TRUE);
                $dr_amounts=$this->input->post('dr_amount',TRUE);
                $cr_amounts=$this->input->post('cr_amount',TRUE);

                for($i=0;$i<=count($accounts)-1;$i++){
                    $m_journal_accounts->journal_id=$journal_id;
                    $m_journal_accounts->account_id=$accounts[$i];
                    $m_journal_accounts->memo=$memos[$i];
                    $m_journal_accounts->dr_amount=$this->get_numeric_value($dr_amounts[$i]);
                    $m_journal_accounts->cr_amount=$this->get_numeric_value($cr_amounts[$i]);
                    $m_journal_accounts->save();
                }


                //update transaction number base on formatted last insert id
                $m_journal->txn_no='TXN-'.date('Ymd').'-'.$journal_id;
                $m_journal->modify($journal_id);


                //if sales invoice is available, sales invoice is recorded as journal so mark this as posted
                $sales_invoice_id=$this->input->post('sales_invoice_id',TRUE);
                if($sales_invoice_id!=null){
                    $m_sales_invoice=$this->Sales_invoice_model;
                    $m_sales_invoice->journal_id=$journal_id;
                    $m_sales_invoice->is_journal_posted=TRUE;
                    $m_sales_invoice->modify($sales_invoice_id);
                    // AUDIT TRAIL START
                    $sales_invoice=$m_sales_invoice->get_list($sales_invoice_id,'sales_inv_no');
                    $m_trans=$this->Trans_model;
                    $m_trans->user_id=$this->session->user_id;
                    $m_trans->set('trans_date','NOW()');
                    $m_trans->trans_key_id=8; //CRUD
                    $m_trans->trans_type_id=17; // TRANS TYPE
                    $m_trans->trans_log='Finalized Sales Invoice No.'.$sales_invoice[0]->sales_inv_no.' For Sales Journal Entry TXN-'.date('Ymd').'-'.$journal_id;
                    $m_trans->save();
                    //AUDIT TRAIL END
                }

                $meter_reading_input_id=$this->input->post('meter_reading_input_id',TRUE);
                if($meter_reading_input_id!=null){
                    $m_meter_model=$this->Meter_reading_input_model;
                    $m_meter_model->journal_id=$journal_id;
                    $m_meter_model->is_journal_posted=TRUE;
                    $m_meter_model->modify($meter_reading_input_id);
                    // AUDIT TRAIL START
                    $meter_info=$m_meter_model->get_list($meter_reading_input_id,'batch_no');
                    $m_trans=$this->Trans_model;
                    $m_trans->user_id=$this->session->user_id;
                    $m_trans->set('trans_date','NOW()');
                    $m_trans->trans_key_id=8; //CRUD
                    $m_trans->trans_type_id=82; // TRANS TYPE
                    $m_trans->trans_log='Finalized Meter Entry Batch No.'.$meter_info[0]->batch_no.' For Sales Journal Entry TXN-'.date('Ymd').'-'.$journal_id;
                    $m_trans->save();
                    //AUDIT TRAIL END
                }

                $billing_penalty_id=$this->input->post('billing_penalty_id',TRUE);
                if($billing_penalty_id!=null){
                    $m_billing_penalty=$this->Billing_penalty_model;
                    $m_billing_penalty->journal_id=$journal_id;
                    $m_billing_penalty->is_journal_posted=TRUE;
                    $m_billing_penalty->modify($billing_penalty_id);
                    
                    // AUDIT TRAIL START
                    $penalty=$m_billing_penalty->get_list(
                            $billing_penalty_id,
                            array(
                                'billing_penalties.year',
                                'months.month_name'
                            ),
                            array(
                                array('months','months.month_id=billing_penalties.month_id','left')
                            )
                        );

                    $month_year = $penalty[0]->month_name.' '.$penalty[0]->year;

                    $m_trans=$this->Trans_model;
                    $m_trans->user_id=$this->session->user_id;
                    $m_trans->set('trans_date','NOW()');
                    $m_trans->trans_key_id=8; //CRUD
                    $m_trans->trans_type_id=85; // TRANS TYPE
                    $m_trans->trans_log='Finalized Penalty for the month of .'.$month_year.' TXN-'.date('Ymd').'-'.$journal_id;
                    $m_trans->save();
                    //AUDIT TRAIL END
                }


                // AUDIT TRAIL START

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=1; //CRUD
                $m_trans->trans_type_id=4; // TRANS TYPE
                $m_trans->trans_log='Created Sales Journal Entry TXN-'.date('Ymd').'-'.$journal_id;
                $m_trans->save();
                //AUDIT TRAIL END
                
                $response['stat']='success';
                $response['title']='Success!';
                $response['msg']='Journal successfully posted';
                $response['row_added']=$this->get_response_rows($journal_id);
                echo json_encode($response);
                break;
            case 'update':
                $journal_id=$this->input->get('id');
                $m_journal=$this->Journal_info_model;
                $m_journal_accounts=$this->Journal_account_model;

                //validate if this transaction is not yet closed
                $not_closed=$m_journal->get_list('accounting_period_id>0 AND journal_id='.$journal_id);
                if(count($not_closed)>0){
                    $response['stat']='error';
                    $response['title']='<b>Journal is Locked!</b>';
                    $response['msg']='Sorry, you cannot update journal that is already closed!<br />';
                    die(json_encode($response));
                }

                //validate if still in valid range
                $valid_range=$this->Accounting_period_model->get_list("'".date('Y-m-d',strtotime($this->input->post('date_txn',TRUE)))."'<=period_end");
                if(count($valid_range)>0){
                    $response['stat']='error';
                    $response['title']='<b>Accounting Period is Closed!</b>';
                    $response['msg']='Please make sure transaction date is valid!<br />';
                    die(json_encode($response));
                }


                $m_journal->customer_id=$this->input->post('customer_id',TRUE);
                $m_journal->department_id=$this->input->post('department_id',TRUE);
                $m_journal->remarks=$this->input->post('remarks',TRUE);
                $m_journal->date_txn=date('Y-m-d',strtotime($this->input->post('date_txn',TRUE)));
                $m_journal->book_type='SJE';

                //for audit details
                $m_journal->set('date_modified','NOW()');
                $m_journal->modified_by_user=$this->session->user_id;
                $m_journal->modify($journal_id);


                $accounts=$this->input->post('accounts',TRUE);
                $memos=$this->input->post('memo',TRUE);
                $dr_amounts=$this->input->post('dr_amount',TRUE);
                $cr_amounts=$this->input->post('cr_amount',TRUE);

                $m_journal_accounts->delete_via_fk($journal_id);

                for($i=0;$i<=count($accounts)-1;$i++){
                    $m_journal_accounts->journal_id=$journal_id;
                    $m_journal_accounts->account_id=$accounts[$i];
                    $m_journal_accounts->memo=$memos[$i];
                    $m_journal_accounts->dr_amount=$this->get_numeric_value($dr_amounts[$i]);
                    $m_journal_accounts->cr_amount=$this->get_numeric_value($cr_amounts[$i]);
                    $m_journal_accounts->save();
                }


                $response['stat']='success';
                $response['title']='Success!';
                $response['msg']='Journal successfully updated';
                $response['row_updated']=$this->get_response_rows($journal_id);
                echo json_encode($response);
                break;
            //***************************************************************************************
            case 'cancel':
                $m_journal=$this->Journal_info_model;
                $journal_id=$this->input->post('journal_id',TRUE);

                //validate if this transaction is not yet closed
                $not_closed=$m_journal->get_list('accounting_period_id>0 AND journal_id='.$journal_id);
                if(count($not_closed)>0){
                    $response['stat']='error';
                    $response['title']='<b>Journal is Locked!</b>';
                    $response['msg']='Sorry, you cannot cancel journal that is already closed!<br />';
                    die(json_encode($response));
                }


                //mark Items as deleted
                $m_journal->set('date_cancelled','NOW()'); //treat NOW() as function and not string
                $m_journal->cancelled_by_user=$this->session->user_id;//user that cancelled the record
                $m_journal->set('is_active','NOT is_active');
                $m_journal->modify($journal_id);


                $journal_txn_no =$m_journal->get_list($journal_id,'txn_no,is_active');
                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                if($journal_txn_no[0]->is_active ==TRUE){

                $m_trans->trans_key_id=9; //CRUD
                $m_trans->trans_type_id=4; // TRANS TYPE
                $m_trans->trans_log='Uncancelled Sales Journal Entry : '.$journal_txn_no[0]->txn_no;
                

                }else if($journal_txn_no[0]->is_active ==FALSE){
                $m_trans->trans_key_id=4; //CRUD
                $m_trans->trans_type_id=4; // TRANS TYPE
                $m_trans->trans_log='Cancelled Sales Journal Entry : '.$journal_txn_no[0]->txn_no;
                }
                $m_trans->save();

                $response['title']='Cancelled!';
                $response['stat']='success';
                $response['msg']='Journal successfully cancelled.';
                $response['row_updated']=$this->get_response_rows($journal_id);

                echo json_encode($response);

                break;
        };
    }



    public function get_response_rows($criteria=null,$additional=null){
        $m_journal=$this->Journal_info_model;
        return $m_journal->get_list(

            "journal_info.is_deleted=FALSE AND journal_info.book_type='SJE' AND journal_info.is_sales=1 ".($criteria==null?'':'  AND journal_info.journal_id='.$criteria)."".($additional==null?'':$additional),

            array(
                'journal_info.journal_id',
                'journal_info.txn_no',
                'DATE_FORMAT(journal_info.date_txn,"%m/%d/%Y")as date_txn',
                'journal_info.is_active',
                'journal_info.remarks',
                'journal_info.customer_id',
                'journal_info.department_id',
                'customers.customer_name as particular',
                'CONCAT_WS(" ",user_accounts.user_fname,user_accounts.user_lname)as posted_by'
            ),
            array(
                array('customers','customers.customer_id=journal_info.customer_id','left'),
                array('user_accounts','user_accounts.user_id=journal_info.created_by_user','left')
            ),
            'journal_info.journal_id DESC'
        );
    }






}
