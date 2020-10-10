<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Api', 'Master', 'Tanggal', 'pagination', 'Uuid'));
        /*default breadcrumb*/
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');

        $this->load->model('Profile_model', 'profile');

        /*define varibel for customer id from session*/
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;

        /*cek session logged in*/
        if (!($this->session->userdata('logged_in'))) {
            redirect(base_url() . 'Login');
        }
    }

    public function index()
    {
        /*breadcrumbs active*/
        $data = array();
        $this->breadcrumbs->push('Profile', get_class($this) . '/' . __FUNCTION__ . '/');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['title'] = 'Profil - Halal Shopping';

        $profile = $this->profile->getProfileCustomerWithApi();
        $data['profile2'] = $this->profile->getUserTabel();
        $data['bankList'] = $this->db->get('Bank')->result();
        //        echo '<pre>';print_r($profile);die;
        $data['profile'] = $profile->result;
        //echo '<pre>';print_r($data);die;
        //        $data['customer'] = $profile->data->customer;
        //        $data['address'] = $data['customer']->addresses;
        //        $data['bankAccount'] = $data['customer']->bankAccounts;
        $this->template->load($data, 'profile');
    }

    public function ajax_list_bank_account()
    {
        $list = $this->profile->get_datatables_bank_account();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $listdata) {
            $no++;
            $row = array();
            $row[] = '<div class="center">' . $no . '</div>';
            $row[] = $listdata->id;
            $row[] = $listdata->accountName;
            $row[] = $listdata->accountNo;
            $row[] = $listdata->bankName;
            $row[] = $listdata->location;

            $data[] = $row;
        }

        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : 0,
            "recordsTotal" => $this->profile->count_all_bank_account(),
            "recordsFiltered" => $this->profile->count_filtered_bank_account(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function simpan()
    {
        $alias = $this->input->post('alias');
        $birthday = $this->input->post('thn') . '-' . $this->input->post('bln') . '-' . $this->input->post('tgl');
        $simpan = $this->profile->simpan_biodata($alias, $birthday);
        if ($simpan) {
            redirect($this->agent->referrer() . '#alert');
        } else {
            redirect($this->agent->referrer());
        }
    }

    public function DeleteAddress($id)
    {
        //        $this->db->delete('CustomerAddress', array('id'=>$id));
        $params = [
            'link'   => API_DATA . 'Customers/' . $this->customerId . '/address/' . $id . '?access_token=' . $this->session->userdata('token'),
            'data'  => [],
            'method' => 'DELETE'
        ];
        $data = $this->api->getApiData($params);
        echo json_encode($data);
    }

    /*public function DeleteBankAccount($id) {
        $this->db->delete('CustomerBankAccount', array('id'=>$id));
        redirect(get_class($this));
    }*/

    public function editProfile()
    {
        $alias = $this->input->post('alias');
        $params = [
            'link'   => API_DATA . 'Customers/' . $this->customerId . '/profile?access_token=' . $this->session->userdata('token'),
            'data'  => ['alias' => $alias],
            'method' => 'PUT'
        ];

        $data = $this->api->getApiData($params);
        //print_r($data);die;
        echo json_encode($data);
        //        echo http_build_query($params);
    }

    public function edit_address($id)
    {
        echo json_encode($this->db->get_where('CustomerAddress', array('id' => $id))->row());
    }

    public function edit_address_simpan()
    {
        $id = $this->input->post('addressId');
        $data = [
            'name' => $this->input->post('name'),
            'recipientName' => $this->input->post('recipientName'),
            'recipientPhone' => $this->input->post('recipientPhone'),
            'address' => $this->input->post('address'),
            'countryId' => $this->input->post('countryId'),
            'provinceId' => $this->input->post('provinceId'),
            'cityId' => $this->input->post('cityId'),
            'districtId' => $this->input->post('districtId'),
            'zipCode' => $this->input->post('zipCode')
        ];

        $params = [
            'link'   => API_DATA . 'Customers/' . $this->session->userdata('user')->id . '/address/' . $id . '?access_token=' . $this->session->userdata('token'),
            'data'  => $data,
            'method' => 'PUT'
        ];

        $return = $this->api->getApiData($params);

        echo json_encode($return);
    }

    /*public function ajax_process_address()
    {
        $data = array(
                'firstName' => $this->input->post('firstName'),
                'lastName' => $this->input->post('lastName'),
                'gender' => $this->input->post('gender'),
                'address' => $this->input->post('address'),
                'dob' => $this->input->post('dob'),
            );
        $insert = $this->menu->save($data);
        echo json_encode(array("status" => TRUE));
    }*/

    public function edit_bank_account($id)
    {
        echo json_encode($this->db->get_where('CustomerBankAccount', array('id' => $id))->row());
    }

    public function ajax_process_bank_account()
    {
        //print_r($_POST);die;
        $kategori = $this->input->post('form-bank-kategori') ? $this->input->post('form-bank-kategori') : 0;
        $api = null;

        $bankAccountId = $this->input->post('bankAccountId') ? $this->input->post('bankAccountId') : 0;

        if ($kategori == 'edit') {
            // UPDATE BANK ACCOUNT
            $data = array(
                'bankId' => $this->input->post('bankId'),
                //                'bankName' => $this->templates_model->getFieldName('Bank', 'id', 'name', $this->input->post('bankId')),
                'location' => $this->input->post('location'),
                'accountNo' => $this->input->post('accountNo'),
                'accountName' => $this->input->post('accountName'),
            );
            $params = [
                'link'   => API_DATA . 'Customers/' . $this->customerId . '/bank-account/' . $bankAccountId . '?access_token=' . $this->session->userdata('token'),
                'data'  => $data,
                'method' => 'PUT'
            ];

            //            echo json_encode($params);
            $api = $this->api->getApiData($params);
        } else {
            // ADD BANK ACCOUNT
            $data = array(
                'bankId' => $this->input->post('bankId'),
                'bankName' => $this->templates_model->getFieldName('Bank', 'id', 'name', $this->input->post('bankId')),
                'location' => $this->input->post('location'),
                'accountNo' => $this->input->post('accountNo'),
                'accountName' => $this->input->post('accountName'),
            );
            $data['id'] = $this->uuid->v4();
            $params = [
                'link'   => API_DATA . 'Customers/' . $this->customerId . '/bank-account?access_token=' . $this->session->userdata('token'),
                'data'  => $data,
            ];

            $api = $this->api->getApiData($params);
        }

        echo json_encode($api);
    }

    public function DeleteBankAccount($id)
    {
        $this->db->delete('CustomerBankAccount', array('id' => $id));
        echo json_encode(array("status" => TRUE));
    }

    public function getBankAccountList()
    {
        $params = [
            'link'   => API_DATA . 'Customers/' . $this->customerId . '/bank-accounts?limit=&skip=&order=&lastUpdated=&access_token=' . $this->session->userdata('token'),
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);

        $x['data'] = [];
        $no = 1;
        if (isset($data->result)) {
            foreach ($data->result as $row) {
                $getData = [
                    'no' => $no,
                    'bank_id' => $row->bankId,
                    'account_name' => $row->accountName,
                    'account_no' => $row->accountNo,
                    'bank_name' => $row->bankName,
                    'location' => $row->location,
                    'aksi' => '<button type="button" id="btn" class="btn label-info btn-edit-bank" data-id="' . $row->id . '"><i class="fa fa-edit"></i></button> <button type="button" id="btnDelete" class="btn btn-danger btn-delete-bank" data-id="' . $row->id . '"><i class="fa fa-times"></i></button>'
                ];
                array_push($x['data'], $getData);
                $no++;
            }
        } else {
            $getData = [
                'no' => '', 'bank_id' => '', 'account_name' => '', 'account_no' => '', 'bank_name' => '', 'location' => '', 'aksi' => ''
            ];
            array_push($x['data'], $getData);
        }

        $no = 0;

        echo json_encode($x);
    }

    public function getAddressList()
    {
        $params = [
            'link'   => API_DATA . 'Customers/' . $this->customerId . '/address?skip=&limit=&access_token=' . $this->session->userdata('token'),
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);

        $x['data'] = [];
        $no = 1;
        //echo '<pre>'; print_r($data->result);
        if (isset($data->result)) {
            foreach ($data->result as $row) {
                $getData = [
                    'no' => $no,
                    'id' => $row->id,
                    'nama_penerima' => ucwords($row->recipientName),
                    'phone' => $row->recipientPhone,
                    'alamat_lengkap' => $row->address . ', ' . $row->districtName . ', ' . $row->provinceName . ', ' . $row->zipCode,
                    'aksi' => '<button type="button" id="btn" class="btn label-info btn-edit-address" data-id="' . $row->id . '"><i class="fa fa-edit"></i></button> <button type="button" id="btnDelete" class="btn btn-danger btn-delete-address" data-id="' . $row->id . '"><i class="fa fa-times"></i></button>',
                    'keterangan' => $row->name,
                ];
                array_push($x['data'], $getData);
                $no++;
            }
        } else {
            $getData = [
                'no' => '', 'id' => '', 'nama_penerima' => '', 'phone' => '', 'alamat_lengkap' => '', 'aksi' => '', 'keterangan' => '',
            ];

            array_push($x['data'], $getData);
        }

        $no = 0;

        echo json_encode($x);
    }

    public function addAddress()
    {
        $data = [
            'name' => $this->input->post('name'),
            'recipientName' => $this->input->post('recipientName'),
            'recipientPhone' => $this->input->post('recipientPhone'),
            'address' => $this->input->post('address'),
            'countryId' => $this->input->post('countryId'),
            'provinceId' => $this->input->post('provinceId'),
            'cityId' => $this->input->post('cityId'),
            'districtId' => $this->input->post('districtId'),
            'zipCode' => $this->input->post('zipCode')
        ];
        //print_r($data);die;

        $params = [
            'link'   => API_DATA . 'Customers/' . $this->session->userdata('user')->id . '/address?access_token=' . $this->session->userdata('token'),
            'data'  => $data
        ];

        $return = $this->api->getApiData($params);
        /*print_r($return);die;*/

        echo json_encode($return);
    }
}
