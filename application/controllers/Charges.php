<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Charges extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();
        $this->load->library('excel');
        $this->load->model('Units_model');
        $this->load->model('Account_title_model');
        $this->load->model('Charges_model');
        $this->load->model('Charge_unit_model');
        $this->load->model('Users_model');
        $this->load->model('Trans_model');
    }

    public function index() {
        $this->Users_model->validate();
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', TRUE);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', TRUE);
        $data['_rights'] = $this->load->view('template/elements/rights', '', TRUE);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', TRUE);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', TRUE);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', TRUE);
        $data['title'] = 'Charges Management';
        $data['units'] = $this->Charge_unit_model->get_list(array('charge_unit.is_deleted'=>FALSE));
        $data['accounts'] = $this->Account_title_model->get_list(null,'account_id,account_title');

        (in_array('19-1',$this->session->user_rights)? 
        $this->load->view('charges_view', $data)
        :redirect(base_url('dashboard')));
    }

    function transaction($txn = null) {
        switch ($txn) {
            case 'list':
                $response['data']=$this->response_rows(array('charges.is_deleted'=>FALSE));
                echo json_encode($response);
                break;

            case 'create';
                $m_charges = $this->Charges_model;
                $m_charges->charge_code = $this->input->post('charge_code',TRUE);
                $m_charges->charge_desc = $this->input->post('charge_desc',TRUE);
                $m_charges->income_account_id = $this->input->post('income_account_id',TRUE);
                $m_charges->charge_unit_id = $this->input->post('charge_unit_id',TRUE);
                $m_charges->charge_amount = $this->get_numeric_value($this->input->post('charge_amount',TRUE));
                $m_charges->save();
                $charge_id = $m_charges->last_insert_id();
                $response['title']='Success!';
                $response['stat']='success';
                $response['msg']='Charge information successfully updated.';
                $response['row_added']=$this->response_rows($charge_id);

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=1; //CRUD
                $m_trans->trans_type_id=76; // TRANS TYPE
                $m_trans->trans_log='Created Charge: ('.$this->input->post('charge_code',TRUE).')';
                $m_trans->save();

                echo json_encode($response);

            break;

            case 'update';
                $m_charges = $this->Charges_model;
                $charge_id = $this->input->post('charge_id',TRUE);
                $m_charges->charge_code = $this->input->post('charge_code',TRUE);
                $m_charges->charge_desc = $this->input->post('charge_desc',TRUE);
                $m_charges->charge_unit_id = $this->input->post('charge_unit_id',TRUE);
                $m_charges->income_account_id = $this->input->post('income_account_id',TRUE);
                $m_charges->charge_amount = $this->get_numeric_value($this->input->post('charge_amount',TRUE));
                $m_charges->modify($charge_id);

                $response['title']='Success!';
                $response['stat']='success';
                $response['msg']='Charge Information Successfully Updated.';
                $response['row_updated']=$this->response_rows($charge_id);

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=76; // TRANS TYPE
                $m_trans->trans_log='Updated Charge: ID('.$charge_id.')';
                $m_trans->save();

                echo json_encode($response);

            break;


            case 'delete';
                $m_charges = $this->Charges_model;
                $charge_id = $this->input->post('charge_id',TRUE);
                $m_charges->deleted_by_user = $this->session->user_id;
                $m_charges->is_deleted=1;
                    if($m_charges->modify($charge_id)){
                        $response['title']='Success!';
                        $response['stat']='success';
                        $response['msg']='Charge information successfully deleted.';

                        $m_trans=$this->Trans_model;
                        $m_trans->user_id=$this->session->user_id;
                        $m_trans->set('trans_date','NOW()');
                        $m_trans->trans_key_id=3; //CRUD
                        $m_trans->trans_type_id=76; // TRANS TYPE
                        $m_trans->trans_log='Deleted Charge: ID('.$charge_id.')';
                        $m_trans->save();

                        echo json_encode($response); 
                    }
            break;

        }
    }

        function response_rows($filter){
        return $this->Charges_model->get_list(
            $filter,
            'charges.*,
            charge_unit.charge_unit_name',
            array(
                array('charge_unit','charge_unit.charge_unit_id=charges.charge_unit_id','left')
            )

            );
        }











}