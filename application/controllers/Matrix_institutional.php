<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matrix_institutional extends CORE_Controller
{

    function __construct() {
        parent::__construct('');
        $this->validate_session();
        $this->load->model('Users_model');
        $this->load->model('Company_model');
        $this->load->model('Trans_model');
        $this->load->model('Matrix_institutional_model');
        $this->load->model('Matrix_institutional_items_model');
        $this->load->model('Account_integration_model');
        $this->load->library('excel');
    }

    public function index() {
        $this->Users_model->validate();
        $data['_def_css_files'] = $this->load->view('template/assets/css_files', '', TRUE);
        $data['_def_js_files'] = $this->load->view('template/assets/js_files', '', TRUE);
        $data['_rights'] = $this->load->view('template/elements/rights', '', TRUE);
        $data['_switcher_settings'] = $this->load->view('template/elements/switcher', '', TRUE);
        $data['_side_bar_navigation'] = $this->load->view('template/elements/side_bar_navigation', '', TRUE);
        $data['_top_navigation'] = $this->load->view('template/elements/top_navigation', '', TRUE);
        $data['title'] = 'Institutional Rate Matrix';


        (in_array('20-3',$this->session->user_rights)? 
        $this->load->view('matrix_institutional_view', $data)
        :redirect(base_url('dashboard')));
        
    }


    function transaction($txn=null,$filter_id=null){
        switch($txn){
            
            case 'list':
                $response['data']=$this->Matrix_institutional_model->get_list(array('is_active'=>TRUE,'is_deleted'=>FALSE));
                echo json_encode($response);
            break;

            case 'edit-items':
                $response['data']=$this->Matrix_institutional_items_model->get_list(array('matrix_institutional_id'=>$filter_id),
                    '*,
                    IF(is_fixed_amount = 1,"checked","") as is_fixed_amout_checked'
                    );
                echo json_encode($response);
            break;


             case 'create':
                $m_matrix=$this->Matrix_institutional_model;

                $m_matrix->begin();
                $m_matrix->description=$this->input->post('description',TRUE);
                $m_matrix->other_details=$this->input->post('other_details',TRUE);
                $m_matrix->save();

                $matrix_institutional_id=$m_matrix->last_insert_id();
                $matrix_institutional_from=$this->input->post('matrix_institutional_from',TRUE);
                $matrix_institutional_to=$this->input->post('matrix_institutional_to',TRUE);
                $matrix_institutional_amount=$this->input->post('matrix_institutional_amount',TRUE);   
                $is_fixed_amount=$this->input->post('is_fixed_amount',TRUE);   

                $m_matrix_items=$this->Matrix_institutional_items_model;

                for($i=0;$i<count($matrix_institutional_from);$i++){
                    $m_matrix_items->matrix_institutional_id=$matrix_institutional_id;
                    $m_matrix_items->matrix_institutional_from=$this->get_numeric_value($matrix_institutional_from[$i]);
                    $m_matrix_items->matrix_institutional_to=$this->get_numeric_value($matrix_institutional_to[$i]);
                    $m_matrix_items->matrix_institutional_amount=$this->get_numeric_value($matrix_institutional_amount[$i]);
                    $m_matrix_items->is_fixed_amount=$this->get_numeric_value($is_fixed_amount[$i]);
                    $m_matrix_items->save();
                }

                $m_matrix->matrix_institutional_code='MATRIX-INST-'.date('Y').'-'.$matrix_institutional_id;
                $m_matrix->modify($matrix_institutional_id);

                $m_matrix->commit();

                if($m_matrix->status()===TRUE){
                    $response['title'] = 'Success!';
                    $response['stat'] = 'success';
                    $response['msg'] = 'Matrix successfully created.';
                    $response['row_added']=$this->Matrix_institutional_model->get_list($matrix_institutional_id);

                    // Audittrail Log          
                    $m_trans=$this->Trans_model;
                    $m_trans->user_id=$this->session->user_id;
                    $m_trans->set('trans_date','NOW()');
                    $m_trans->trans_key_id=1; //CRUD
                    $m_trans->trans_type_id=86; // TRANS TYPE
                    $m_trans->trans_log='Created New Institutional Rate Matrix: (MATRIX-INST-'.date('Y').'-'.$matrix_institutional_id.')';
                    $m_trans->save();

                    echo json_encode($response);
                }


                break;

             case 'update':
                $m_matrix=$this->Matrix_institutional_model;

                $m_matrix->begin();
                $matrix_institutional_id=$this->input->post('matrix_institutional_id',TRUE);
                $m_matrix->description=$this->input->post('description',TRUE);
                $m_matrix->other_details=$this->input->post('other_details',TRUE);
                $m_matrix->modify($matrix_institutional_id);

                $matrix_institutional_from=$this->input->post('matrix_institutional_from',TRUE);
                $matrix_institutional_to=$this->input->post('matrix_institutional_to',TRUE);
                $matrix_institutional_amount=$this->input->post('matrix_institutional_amount',TRUE);   
                $is_fixed_amount=$this->input->post('is_fixed_amount',TRUE);   

                $m_matrix_items=$this->Matrix_institutional_items_model;
                $m_matrix_items->delete_via_fk($matrix_institutional_id);
                for($i=0;$i<count($matrix_institutional_from);$i++){
                    $m_matrix_items->matrix_institutional_id=$matrix_institutional_id;
                    $m_matrix_items->matrix_institutional_from=$this->get_numeric_value($matrix_institutional_from[$i]);
                    $m_matrix_items->matrix_institutional_to=$this->get_numeric_value($matrix_institutional_to[$i]);
                    $m_matrix_items->matrix_institutional_amount=$this->get_numeric_value($matrix_institutional_amount[$i]);
                    $m_matrix_items->is_fixed_amount=$this->get_numeric_value($is_fixed_amount[$i]);
                    $m_matrix_items->save();
                }
                $m_matrix->commit();

                if($m_matrix->status()===TRUE){
                    $response['title'] = 'Success!';
                    $response['stat'] = 'success';
                    $response['msg'] = 'Matrix successfully Updated.';
                    $response['row_updated']=$this->Matrix_institutional_model->get_list($matrix_institutional_id);

                    // Audittrail Log          
                    $m_trans=$this->Trans_model;
                    $m_trans->user_id=$this->session->user_id;
                    $m_trans->set('trans_date','NOW()');
                    $m_trans->trans_key_id=2; //CRUD
                    $m_trans->trans_type_id=86; // TRANS TYPE
                    $m_trans->trans_log='Updated Institutional Rate Matrix: ID('.$matrix_institutional_id.')';
                    $m_trans->save();

                    echo json_encode($response);
                }


                break;

            case 'delete':
                $m_matrix=$this->Matrix_institutional_model;
                $matrix_institutional_id=$this->input->post('matrix_institutional_id',TRUE);
                $ai=$this->Account_integration_model->get_list(1)[0];
                if($ai->default_matrix_institutional_id == $matrix_institutional_id){
                    $response['title']='Cannot Delete!';
                    $response['stat']='error';
                    $response['msg']='Cannot Delete Matrix in use.';
                }else{
                    $m_matrix->is_deleted=1;//mark as deleted
                    $m_matrix->modify($matrix_institutional_id);
                    $response['title']='Success!';
                    $response['stat']='success';
                    $response['msg']='Record successfully deleted.';

                    // Audittrail Log          
                    $m_trans=$this->Trans_model;
                    $m_trans->user_id=$this->session->user_id;
                    $m_trans->set('trans_date','NOW()');
                    $m_trans->trans_key_id=3; //CRUD
                    $m_trans->trans_type_id=86; // TRANS TYPE
                    $m_trans->trans_log='Deleted Institutional Rate Matrix: ID('.$matrix_institutional_id.')';
                    $m_trans->save();

                }

                echo json_encode($response);

                break;
        }
    }

}