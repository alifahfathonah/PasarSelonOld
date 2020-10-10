<?php
defined('BASEPATH') or exit('No direct script access allowed');

class References extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getCourierPackageByCourier($id = '')
	{
		$query = $this->db->where('courierId', $id)
			->get('CourierPackage');

		echo json_encode($query->result());
	}

	public function getEscrowBankAccount($id = '')
	{
		$query = $this->db->where('id', $id)
			->order_by('bankName', 'ASC')->get('EscrowBankAccount');

		echo json_encode($query->row());
	}

	public function getEscrowList()
	{
		$query = $this->db->order_by('bankName', 'ASC')->get('EscrowBankAccount');

		echo json_encode($query->result());
	}

	public function getProvinceByCountry($id = '')
	{
		$query = $this->db->where('countryId', $id)
			->order_by('name', 'ASC')->get('Province');

		echo json_encode($query->result());
	}

	public function getCityByProvince($provinceId = '')
	{
		$query = $this->db->where('provinceId', $provinceId)
			->order_by('name', 'ASC')
			->get('City');

		echo json_encode($query->result());
	}

	public function getDistrictByCity($cityId = '', $provinceId = '')
	{
		$query = $this->db->where('cityId', $cityId)
			->where('provinceId', $provinceId)
			->order_by('name', 'ASC')
			->get('District');

		echo json_encode($query->result());
	}

	public function getMarketByDistrict($districtId = '', $cityId = '', $provinceId = '')
	{
		$query = $this->db->where('districtId', $districtId)
			->where('cityId', $cityId)
			->where('provinceId', $provinceId)
			->order_by('name', 'ASC')
			->get('Market');

		echo json_encode($query->result());
	}
	public function getCustomerAddress()
	{
		$query = $this->db->where('customerId', $this->session->userdata('user')->id)
			->order_by('name', 'ASC')
			->get('CustomerAddress');

		echo json_encode($query->result());
	}

	public function getDetailCustomerAddress($CustomerAddressId)
	{
		$query = $this->db->where('id', $CustomerAddressId)
			->order_by('name', 'ASC')
			->get('CustomerAddress');

		echo json_encode($query->row());
	}

	public function getCategoryProductPPOB($providerId)
	{
		$params = [
			'link' => API_DATA . 'ppob/products?categoryId=&providerId=' . $providerId . '&skip=&limit=&sortBy=&sortArrangement=',
			'data' => []
		];

		$api = $this->api->getApiData($params);
		//echo '<pre>';print_r($api);die;
		$result = $api->result;

		echo json_encode($result);
	}

	public function getPPOBProvider($categoryProduct)
	{
		$params = [
			'link' => API_DATA . 'ppob/categories/providers?categoryId=' . $categoryProduct . '',
			'data' => []
		];

		$api = $this->api->getApiData($params);
		//echo '<pre>';print_r($api);die;
		$result = $api->result;

		echo json_encode($result);
	}
}
