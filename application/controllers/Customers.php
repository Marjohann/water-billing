<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CORE_Controller {

    function __construct()
    {
        parent::__construct('');
        $this->validate_session();
        $this->load->model('Customers_model');
        $this->load->model('Customer_photos_model');
        $this->load->model('Departments_model');
        $this->load->model('RefCustomerType_model');
        $this->load->model('Journal_info_model');
        $this->load->model('Sales_order_model');
        $this->load->model('Sales_invoice_model');
        $this->load->model('Receivable_payment_model');
        $this->load->model('Users_model');
        $this->load->model('Trans_model');
        $this->load->model('Customer_type_model');
        $this->load->model('Soa_settings_model');
        $this->load->model('Company_model');
        $this->load->model('Nationality_model');
        $this->load->model('Civil_status_model');
        $this->load->model('Sex_model');
        $this->load->model('Customer_account_type_model');
        $this->load->library('excel');

    }
 
    public function index()
    {
        $this->Users_model->validate();
        $data['_def_css_files']=$this->load->view('template/assets/css_files','',TRUE);
        $data['_def_js_files']=$this->load->view('template/assets/js_files','',TRUE);
        $data['_switcher_settings']=$this->load->view('template/elements/switcher','',TRUE);
        $data['_side_bar_navigation']=$this->load->view('template/elements/side_bar_navigation','',TRUE);
        $data['_top_navigation']=$this->load->view('template/elements/top_navigation','',TRUE);
        $data['title']='Customer Management';

        $data['customer_type']=$this->Customer_type_model->get_list(
            'is_deleted=FALSE'
        );

        $data['customer_account_types']=$this->Customer_account_type_model->get_list(
            'is_deleted=FALSE'
        );

        $data['nationalities']=$this->Nationality_model->get_list(
            'is_deleted=FALSE AND is_active=TRUE'
        );

        $data['civils']=$this->Civil_status_model->get_list(
            'is_deleted=FALSE AND is_active=TRUE'
        );

        $data['sexes']=$this->Sex_model->get_list(
            'is_deleted=FALSE AND is_active=TRUE'
        );

        $data['departments'] = $this->Departments_model->get_list(array('departments.is_deleted'=>FALSE));
        $data['refcustomertype'] = $this->RefCustomerType_model->get_list(array('refcustomertype.is_deleted'=>FALSE));
        (in_array('5-3',$this->session->user_rights)? 
        $this->load->view('customers_view',$data)
        :redirect(base_url('dashboard')));
        
    }


    function transaction($txn=null){
        switch($txn){
            //****************************************************************************************************************
            case 'list':
                $m_customers=$this->Customers_model;

                $response['data']=$this->response_rows('customers.is_active=TRUE AND customers.is_deleted=FALSE');

                echo json_encode($response);

                break;

            case 'getcustomer':
                $department_id = $this->input->post('department_id', TRUE);
                $get = "";

                if($department_id > 1){
                    $get = array('customers.department_id'=>$department_id,'customers.is_deleted'=>FALSE);
                }else {
                    $get = array('customers.is_deleted'=>FALSE);
                }

                $response['data'] = $this->response_rows($get);
                echo json_encode($response);
                break;

            //****************************************************************************************************************
            case 'create':
                $m_customers=$this->Customers_model;
                $m_photos=$this->Customer_photos_model;

                $m_customers->customer_name=$this->input->post('customer_name',TRUE);
                $m_customers->contact_name=$this->input->post('contact_name',TRUE);
                $m_customers->address=$this->input->post('address',TRUE);
                $m_customers->email_address=$this->input->post('email_address',TRUE);
                $m_customers->customer_type_id=$this->input->post('customer_type_id',TRUE);
                $m_customers->contact_no=$this->input->post('contact_no',TRUE);
                $m_customers->tin_no=$this->input->post('tin_no',TRUE);
                $m_customers->refcustomertype_id=$this->input->post('refcustomertype_id',TRUE);
                $m_customers->customer_account_type_id=$this->input->post('customer_account_type_id',TRUE);
                $m_customers->department_id=$this->input->post('department_id',TRUE);
                $m_customers->photo_path=$this->input->post('photo_path',TRUE);
                $m_customers->term=$this->input->post('term',TRUE);
                $m_customers->credit_limit=$this->input->post('credit_limit',TRUE);

                $m_customers->spouse_nationality_id=$this->input->post('spouse_nationality_id',TRUE);
                $m_customers->spouse_occupation=$this->input->post('spouse_occupation',TRUE);
                $m_customers->spouse_name=$this->input->post('spouse_name',TRUE);
                $m_customers->tenant_birth_date=date('Y-m-d',strtotime($this->input->post('tenant_birth_date',TRUE)));
                $m_customers->sex_id=$this->input->post('sex_id',TRUE);
                $m_customers->civil_status_id=$this->input->post('civil_status_id',TRUE);
                $m_customers->nationality_id=$this->input->post('nationality_id',TRUE);
                $m_customers->date_move_in=date('Y-m-d',strtotime($this->input->post('date_move_in',TRUE)));
                $m_customers->tenant_occupation=$this->input->post('tenant_occupation',TRUE);
                $m_customers->set('date_created','NOW()');
                $m_customers->posted_by_user=$this->session->user_id;

                $m_customers->save();

                $customer_id=$m_customers->last_insert_id();//get last insert id

                $m_photos->customer_id=$customer_id;
                $m_photos->photo_path=$this->input->post('photo_path',TRUE);
                $m_photos->save();


                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=1; //CRUD
                $m_trans->trans_type_id=52; // TRANS TYPE
                $m_trans->trans_log='Created a new customer: '.$this->input->post('contact_name',TRUE);
                $m_trans->save();

                $response['title']='Success!';
                $response['stat']='success';
                $response['msg']='Customer Information successfully created.';
                $response['row_added']=$this->response_rows($customer_id);
                echo json_encode($response);

                break;

            case 'new-create':
                $m_customers=$this->Customers_model;
                $m_photos=$this->Customer_photos_model;

                $m_customers->customer_name=$this->input->post('customer_name',TRUE);
                $m_customers->contact_name=$this->input->post('contact_name',TRUE);
                $m_customers->address=$this->input->post('address',TRUE);
                $m_customers->email_address=$this->input->post('email_address',TRUE);
                $m_customers->customer_type_id=$this->input->post('customer_type_id_create',TRUE);
                $m_customers->customer_account_type_id=$this->input->post('customer_account_type_id',TRUE);
                $m_customers->contact_no=$this->input->post('contact_no',TRUE);
                $m_customers->tin_no=$this->input->post('tin_no',TRUE);
                $m_customers->refcustomertype_id=$this->input->post('refcustomertype_id',TRUE);
                $m_customers->department_id=$this->input->post('department_id',TRUE);
                $m_customers->photo_path=$this->input->post('photo_path',TRUE);
                $m_customers->term=$this->input->post('term',TRUE);
                $m_customers->credit_limit=$this->input->post('credit_limit',TRUE);

                $m_customers->set('date_created','NOW()');
                $m_customers->posted_by_user=$this->session->user_id;

                $m_customers->save();

                $customer_id=$m_customers->last_insert_id();//get last insert id

                $m_photos->customer_id=$customer_id;
                $m_photos->photo_path=$this->input->post('photo_path',TRUE);
                $m_photos->save();


                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=1; //CRUD
                $m_trans->trans_type_id=52; // TRANS TYPE
                $m_trans->trans_log='Created a new customer: '.$this->input->post('contact_name',TRUE);
                $m_trans->save();

                $response['title']='Success!';
                $response['stat']='success';
                $response['msg']='Customer Information successfully created.';
                $response['row_added']=$this->response_rows($customer_id);
                echo json_encode($response);

                break;
            //****************************************************************************************************************
            case 'delete':
                $m_customers=$this->Customers_model;
                $m_photos=$this->Customer_photos_model;

                $m_journal=$this->Journal_info_model;
                $m_sales_order=$this->Sales_order_model;
                $m_invoice=$this->Sales_invoice_model;
                $m_payment=$this->Receivable_payment_model;

                $customer_id=$this->input->post('customer_id',TRUE);

                if(count($m_journal->get_list('is_active=1 AND customer_id='.$customer_id))>0){
                    $response['title'] = 'Cannot delete!';
                    $response['stat'] = 'error';
                    $response['msg'] = 'This customer still has an active transaction in General Journal.';

                    echo json_encode($response);
                    exit;
                }

                else if(count($m_sales_order->get_list('is_active=1 AND customer_id='.$customer_id))>0){
                    $response['title'] = 'Cannot delete!';
                    $response['stat'] = 'error';
                    $response['msg'] = 'This customer still has an active transaction in Sales Order.';

                    echo json_encode($response);
                    exit;
                }

                else if(count($m_invoice->get_list('is_deleted=0 AND customer_id='.$customer_id))>0){
                    $response['title'] = 'Cannot delete!';
                    $response['stat'] = 'error';
                    $response['msg'] = 'This customer still has an active transaction in Sales Invoice.';

                    echo json_encode($response);
                    exit;
                }

                else if(count($m_payment->get_list('is_active=1 AND customer_id='.$customer_id))>0){
                    $response['title'] = 'Cannot delete!';
                    $response['stat'] = 'error';
                    $response['msg'] = 'This customer still has an active transaction in Collection Entry.';

                    echo json_encode($response);
                    exit;
                }

                else {
                    $m_customers->set('date_deleted','NOW()');
                    $m_customers->deleted_by_user=$this->session->user_id;
                    $m_customers->is_deleted=1;
                    
                    if($m_customers->modify($customer_id)){
                        $response['title']='Success!';
                        $response['stat']='success';
                        $response['msg']='Customer Information successfully deleted.';

                        $customer_name = $m_customers->get_list($customer_id,'customer_name');
                        $m_trans=$this->Trans_model;
                        $m_trans->user_id=$this->session->user_id;
                        $m_trans->set('trans_date','NOW()');
                        $m_trans->trans_key_id=3; //CRUD
                        $m_trans->trans_type_id=52; // TRANS TYPE
                        $m_trans->trans_log='Deleted : '.$customer_name[0]->customer_name;
                        $m_trans->save();

                        echo json_encode($response);
                    }
                }

                break;
                
            //****************************************************************************************************************
            case 'update':
                $m_customers=$this->Customers_model;
                $m_photos=$this->Customer_photos_model;

                $customer_id=$this->input->post('customer_id',TRUE);
                $m_customers->customer_name=$this->input->post('customer_name',TRUE);
                $m_customers->contact_name=$this->input->post('contact_name',TRUE);
                $m_customers->address=$this->input->post('address',TRUE);
                $m_customers->email_address=$this->input->post('email_address',TRUE);
                $m_customers->contact_no=$this->input->post('contact_no',TRUE);
                $m_customers->tin_no=$this->input->post('tin_no',TRUE);
                $m_customers->refcustomertype_id=$this->input->post('refcustomertype_id',TRUE);
                $m_customers->department_id=$this->input->post('department_id',TRUE);
                $m_customers->photo_path=$this->input->post('photo_path',TRUE);
                $m_customers->term=$this->input->post('term',TRUE);
                $m_customers->credit_limit=$this->input->post('credit_limit',TRUE);
                $m_customers->customer_type_id=$this->input->post('customer_type_id',TRUE);
                $m_customers->customer_account_type_id=$this->input->post('customer_account_type_id',TRUE);

                $m_customers->spouse_nationality_id=$this->input->post('spouse_nationality_id',TRUE);
                $m_customers->spouse_occupation=$this->input->post('spouse_occupation',TRUE);
                $m_customers->spouse_name=$this->input->post('spouse_name',TRUE);
                $m_customers->tenant_birth_date=date('Y-m-d',strtotime($this->input->post('tenant_birth_date',TRUE)));
                $m_customers->sex_id=$this->input->post('sex_id',TRUE);
                $m_customers->civil_status_id=$this->input->post('civil_status_id',TRUE);
                $m_customers->nationality_id=$this->input->post('nationality_id',TRUE);
                $m_customers->date_move_in=date('Y-m-d',strtotime($this->input->post('date_move_in',TRUE)));
                $m_customers->tenant_occupation=$this->input->post('tenant_occupation',TRUE);

                $m_customers->set('date_modified','NOW()');
                $m_customers->modified_by_user=$this->session->user_id;

                $m_customers->modify($customer_id);

                $m_photos->delete_via_fk($customer_id);
                $m_photos->customer_id=$customer_id;
                $m_photos->photo_path=$this->input->post('photo_path',TRUE);
                $m_photos->save();

                $m_trans=$this->Trans_model;
                $m_trans->user_id=$this->session->user_id;
                $m_trans->set('trans_date','NOW()');
                $m_trans->trans_key_id=2; //CRUD
                $m_trans->trans_type_id=52; // TRANS TYPE
                $m_trans->trans_log='Updated customer: '.$this->input->post('customer_name',TRUE).' ID('.$customer_id.')';
                $m_trans->save();

                $response['title']='Success!';
                $response['stat']='success';
                $response['msg']='Customer Information successfully updated.';
                $response['row_updated']=$this->response_rows($customer_id);
                echo json_encode($response);

                break;

            //****************************************************************************************************************
            case 'upload':
                $allowed = array('png', 'jpg', 'jpeg','bmp');

                $data=array();
                $files=array();
                $directory='assets/img/customer/';

                foreach($_FILES as $file){

                    $server_file_name=uniqid('');
                    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $file_path=$directory.$server_file_name.'.'.$extension;
                    $orig_file_name=$file['name'];

                    if(!in_array(strtolower($extension), $allowed)){
                        $response['title']='Invalid!';
                        $response['stat']='error';
                        $response['msg']='Image is invalid. Please select a valid photo!';
                        die(json_encode($response));
                    }

                    if(move_uploaded_file($file['tmp_name'],$file_path)){
                        $response['title']='Success!';
                        $response['stat']='success';
                        $response['msg']='Image successfully uploaded.';
                        $response['path']=$file_path;
                        echo json_encode($response);
                    }
                }

                break;

            case 'print-masterfile':
                $m_company_info=$this->Company_model;
                $company_info=$m_company_info->get_list();
                $data['company_info']=$company_info[0];
                $data['customers']=$this->Customers_model->get_list('customers.is_active=TRUE AND customers.is_deleted=FALSE',
                    'customers.*,
                    n.nationality_name,
                    ns.nationality_name spouse_nationality_name,
                    civil_Status.civil_status_name,
                    DATE_FORMAT(customers.date_move_in,"%m/%d/%Y") as date_move_in,
                    DATE_FORMAT(customers.tenant_birth_date,"%m/%d/%Y") as tenant_birth_date,
                    sex.sex_name
                    ',
                    array(
                        array('nationality n','n.nationality_id = customers.nationality_id','left'),
                        array('nationality ns','ns.nationality_id = customers.spouse_nationality_id','left'),
                        array('civil_Status','civil_Status.civil_status_id = customers.civil_status_id','left'),
                        array('sex','sex.sex_id = customers.sex_id','left')

                        ),
                    'customers.customer_name ASC'
                    );
                    $this->load->view('template/customer_masterfile_content',$data);

            break;

            case 'export-supplier':

                $excel = $this->excel;

                $m_company_info=$this->Company_model;
                $company_info=$m_company_info->get_list();
                $data['company_info']=$company_info[0];
                $customers=$this->Customers_model->get_list('customers.is_active=TRUE AND customers.is_deleted=FALSE',
                    'customers.*,
                    n.nationality_name,
                    ns.nationality_name spouse_nationality_name,
                    civil_Status.civil_status_name,
                    DATE_FORMAT(customers.date_move_in,"%m/%d/%Y") as date_move_in,
                    DATE_FORMAT(customers.tenant_birth_date,"%m/%d/%Y") as tenant_birth_date,
                    sex.sex_name
                    ',
                    array(
                        array('nationality n','n.nationality_id = customers.nationality_id','left'),
                        array('nationality ns','ns.nationality_id = customers.spouse_nationality_id','left'),
                        array('civil_Status','civil_Status.civil_status_id = customers.civil_status_id','left'),
                        array('sex','sex.sex_id = customers.sex_id','left')

                        ),
                    'customers.customer_name ASC'
                    );
                $excel->setActiveSheetIndex(0);

                $excel->getActiveSheet()->getColumnDimensionByColumn('A1:B1')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimensionByColumn('A2:C2')->setWidth('50');
                $excel->getActiveSheet()->getColumnDimensionByColumn('A3')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimensionByColumn('A4')->setWidth('40');

                //name the worksheet
                $excel->getActiveSheet()->setTitle("Customer Masterfile");
                $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->mergeCells('A1:B1');
                $excel->getActiveSheet()->mergeCells('A2:C2');
                $excel->getActiveSheet()->mergeCells('A3:B3');
                $excel->getActiveSheet()->mergeCells('A4:B4');

                $excel->getActiveSheet()->setCellValue('A1',$company_info[0]->company_name)
                                        ->setCellValue('A2',$company_info[0]->company_address)
                                        ->setCellValue('A3',$company_info[0]->landline.'/'.$company_info[0]->mobile_no)
                                        ->setCellValue('A4',$company_info[0]->email_address);

                $excel->getActiveSheet()->setCellValue('A6','Customer Masterfile')
                                        ->getStyle('A6')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('A7','')
                                        ->getStyle('A7')->getFont()->setItalic(TRUE);
                $excel->getActiveSheet()->setCellValue('A8','')
                                        ->getStyle('A8')->getFont()->setItalic(TRUE);

                $excel->getActiveSheet()->getColumnDimension('A')->setWidth('40');
                $excel->getActiveSheet()->getColumnDimension('B')->setWidth('25');
                $excel->getActiveSheet()->getColumnDimension('C')->setWidth('25');
                $excel->getActiveSheet()->getColumnDimension('D')->setWidth('25');
                $excel->getActiveSheet()->getColumnDimension('E')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimension('F')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimension('G')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimension('H')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimension('I')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimension('J')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimension('K')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimension('L')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimension('M')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimension('N')->setWidth('30');
                $excel->getActiveSheet()->getColumnDimension('O')->setWidth('30');

                 $style_header = array(

                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb'=>'CCFF99'),
                    ),
                    'font' => array(
                        'bold' => true,
                    )
                );


                $excel->getActiveSheet()->getStyle('A9:O9')->applyFromArray( $style_header );

                $excel->getActiveSheet()->setCellValue('A9','Customer Name')
                                        ->getStyle('A9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('B9','Address')
                                        ->getStyle('B9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('C9','Contact Person')
                                        ->getStyle('C9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('D9','Contact No')
                                        ->getStyle('D9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('E9','Email Address')
                                        ->getStyle('E9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('F9','Sex')
                                        ->getStyle('F9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('G9','Birth Date')
                                        ->getStyle('G9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('H9','Civil Status')
                                        ->getStyle('H9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('I9','Nationality')
                                        ->getStyle('I9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('J9','TIN')
                                        ->getStyle('J9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('K9','Date Move In')
                                        ->getStyle('K9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('L9','Occupation')
                                        ->getStyle('L9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('M9','Spouse Name')
                                        ->getStyle('M9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('N9','Spouse Nationality')
                                        ->getStyle('N9')->getFont()->setBold(TRUE);
                $excel->getActiveSheet()->setCellValue('O9','Spouse Occupation')
                                        ->getStyle('O9')->getFont()->setBold(TRUE);


                $i=10;


                foreach ($customers as $customer) {
                    if($customer->date_move_in =='00/00/0000'){ $date_move_in = ''; }else{ $date_move_in = $customer->date_move_in;} ;
                    if($customer->tenant_birth_date =='00/00/0000'){ $tenant_birth_date = ''; }else{ $tenant_birth_date = $customer->tenant_birth_date;} ;
                $excel->getActiveSheet()->setCellValue('A'.$i,$customer->customer_name)
                                        ->setCellValue('B'.$i,$customer->address)
                                        ->setCellValue('C'.$i,$customer->contact_name)
                                        ->setCellValue('D'.$i,$customer->contact_no)
                                        ->setCellValue('E'.$i,$customer->email_address)
                                        ->setCellValue('F'.$i,$customer->sex_name)
                                        ->setCellValue('G'.$i,$tenant_birth_date)
                                        ->setCellValue('H'.$i,$customer->civil_status_name)
                                        ->setCellValue('I'.$i,$customer->nationality_name)
                                        ->setCellValue('J'.$i,$customer->tin_no)
                                        ->setCellValue('K'.$i,$date_move_in)
                                        ->setCellValue('L'.$i,$customer->tenant_occupation)
                                        ->setCellValue('M'.$i,$customer->spouse_name)
                                        ->setCellValue('N'.$i,$customer->spouse_nationality_name)
                                        ->setCellValue('O'.$i,$customer->spouse_occupation);
                $i++;

                }
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Customer Masterfile '.date('M-d-Y',NOW()).'.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
                $objWriter->save('php://output');            
                     
            break;

            case 'receivables':
                $customer_id=$this->input->get('id',TRUE);
                $m_customers=$this->Customers_model;
                $accounts=$this->Soa_settings_model->get_list(null,'soa_account_id');
                $acc = [];
                foreach ($accounts as $account) { $acc[]=$account->soa_account_id; }
                $filter_accounts =  implode(",", $acc);
                $data['receivables']=$m_customers->get_customer_receivable_list($customer_id,$filter_accounts);
                $structured_content=$this->load->view('template/customer_receivable_list',$data,TRUE);
                echo $structured_content;

                break;

        }
    }


    function response_rows($filter){
        return $this->Customers_model->get_list(
            $filter,

            'customers.*,
            DATE_FORMAT(customers.date_move_in,"%m/%d/%Y") as date_move_in,
            DATE_FORMAT(customers.tenant_birth_date,"%m/%d/%Y") as tenant_birth_date,
            departments.department_name,refcustomertype.customer_type,
            customer_account_type.customer_account_type_desc',

            array(
                array('departments','departments.department_id=customers.department_id','left'),
                array('refcustomertype','refcustomertype.refcustomertype_id=customers.refcustomertype_id','left'),
                array('customer_account_type','customer_account_type.customer_account_type_id=customers.customer_account_type_id','left')
            ),
            'customers.customer_name ASC'
        );
    }
}