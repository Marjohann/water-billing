<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penalty_sending extends CORE_Controller {
    function __construct() {
        parent::__construct('');
        $this->validate_session();
        $this->load->model('Meter_inventory_model');
        $this->load->model('Billing_penalty_model');
        $this->load->model('Account_title_model');
        $this->load->model('Departments_model');
        $this->load->model('Account_integration_model');
        $this->load->model('Meter_reading_period_model');
        $this->load->model('Meter_reading_input_model');
        $this->load->model('Customers_model');
        $this->load->model('Billing_model');
        $this->load->model('Users_model');
        $this->load->model('Trans_model');
        $this->load->model('Company_model');
        $this->load->model('Months_model');
        $this->load->library('excel');
    }

    public function index() {
        $this->Users_model->validate();
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', TRUE);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', TRUE);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', TRUE);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', TRUE);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', TRUE);
        $data['title'] = 'Penalties Sending';

        $data['customer'] = $this->Customers_model->get_list(array('is_active'=>TRUE,'is_deleted'=>FALSE));
        $data['months'] = $this->Months_model->get_list();

        (in_array('22-4',$this->session->user_rights)? 
        $this->load->view('penalty_sending_view', $data)
        :redirect(base_url('dashboard')));
        
    }

    function transaction($txn = null) {
        switch ($txn) {
            case 'penalties':

                $period_id = $this->input->get('period_id',TRUE);
                $year = $this->input->get('year',TRUE);

                $response['data']=$this->Billing_model->get_penalties_incurred($period_id,$year);
                echo json_encode($response);
                break;

            case 'send-to-accounting':
                $m_billing_penalty = $this->Billing_penalty_model;
                $m_month = $this->Months_model;

                $period_id = $this->input->post('period_id',TRUE);
                $year = $this->input->post('year',TRUE);
                $remarks = $this->input->post('remarks',TRUE);

                $m_billing_penalty->begin();

                $m_billing_penalty->month_id = $period_id;
                $m_billing_penalty->year = $year;
                $m_billing_penalty->remarks = $remarks;
                $m_billing_penalty->date_created = date('Y-m-d H:i:s');
                $m_billing_penalty->posted_by_user = $this->session->user_id;
                $m_billing_penalty->save();

                $billing_penalty_id=$m_billing_penalty->last_insert_id();

                $month = $m_month->get_list($period_id);
                $penalty = $month[0]->month_name.' '.$year;

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=12; //CRUD
                $m_trans->trans_type_id=85; // TRANS TYPE
                $m_trans->trans_log='Sent '.$penalty.' Penalty to Accounting';
                $m_trans->save();

                $m_billing_penalty->commit();

                if($m_billing_penalty->status()===TRUE){
                    $response['title'] = 'Success!';
                    $response['stat'] = 'success';
                    $response['msg'] = 'Penalty successfully sent.';
                    echo json_encode($response);
                }
                break;

            case 'check_sent_penalties':

                $m_billing_penalty = $this->Billing_penalty_model;
                $period_id = $this->input->post('period_id',TRUE);
                $year = $this->input->post('year',TRUE);

                $response['data'] = $m_billing_penalty->get_list(array("month_id"=>$period_id,"year"=>$year,"is_deleted"=>FALSE));
                echo json_encode($response);
                break;

            case 'penalties-for-review':

                $m_billing_penalty = $this->Billing_penalty_model;
                $response['data'] = $m_billing_penalty->get_penalties_list();
                echo json_encode($response);

                break;

            case 'billing-receivable-penalty-for-review':

                $m_billing_penalty = $this->Billing_penalty_model;
                $m_customers=$this->Customers_model;
                $m_accounts=$this->Account_title_model;
                $m_departments=$this->Departments_model;

                $billing_penalty_id = $this->input->get('id',TRUE);

                $penalty = $m_billing_penalty->get_list($billing_penalty_id);

                $period_id = $penalty[0]->month_id;
                $year = $penalty[0]->year;

                $data['items']=$this->Billing_model->get_penalties_incurred($period_id,$year);
                $data['info']=$m_billing_penalty->get_list($billing_penalty_id);
                $data['entries']=$m_billing_penalty->get_journal_entries($billing_penalty_id);
                $data['account_integration'] = $this->Account_integration_model->get_list()[0];
                $data['departments']=$m_departments->get_list(array('is_active'=>TRUE,'is_deleted'=>FALSE));
                $data['customers']=$m_customers->get_list(
                    array(
                        'customers.is_active'=>TRUE,
                        'customers.is_deleted'=>FALSE
                    ),

                    array(
                        'customers.customer_id',
                        'customers.customer_name'
                    )
                );
                $data['accounts']=$m_accounts->get_list(
                    array(
                        'account_titles.is_active'=>TRUE,
                        'account_titles.is_deleted'=>FALSE
                    )
                );

                echo $this->load->view('template/billing_penalty_receivable_for_review',$data,TRUE); //details of the journal
                break;

            case 'get_batches':
                $m_batches = $this->Meter_reading_input_model;
                $period_id = $this->input->post('period_id',TRUE);

                $response['data'] = $m_batches->get_batches($period_id);
                echo json_encode($response);
                break;

            case 'update_report':

                $start_date='2020-01-01';
                $end_date='2020-12-31';

                $m_billing = $this->Billing_model;

                $update_arrears = $m_billing->update_arrears($start_date,$end_date);

                if(count($update_arrears) > 0){


                    for ($i=0; $i < count($update_arrears); $i++) { 

                        $m_billing->arrears_penalty_amount = $this->get_numeric_value($update_arrears[$i]->fee);
                        $m_billing->modify($update_arrears[$i]->previous_id);

                    }

                    $response['title']='Success!';
                    $response['stat']='success';
                    $response['msg']='Arrears Penalty successfully updated.';

                }else{

                    $response['title']='Error!';
                    $response['stat']='error';
                    $response['msg']='No update found for billing statements.';

                }

                echo json_encode($response);

                break;

        }
    }
}
