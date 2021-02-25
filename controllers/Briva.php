<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Briva extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('api');
	}

	public function create()
	{
		return json_encode($this->api->create(),JSON_PRETTY_PRINT);
	}

	public function get()
	{
		return json_encode($this->api->getData(),JSON_PRETTY_PRINT);
	}

	public function status()
	{
		return json_encode($this->api->getStatus(),JSON_PRETTY_PRINT);
	}

	public function updateStatus()
	{
		return json_encode($this->api->updateBayar(),JSON_PRETTY_PRINT);
	}

	public function updateVa()
	{
		return json_encode($this->api->updateVa(),JSON_PRETTY_PRINT);
	}


	public function delete()
	{
		return json_encode($this->api->delete(),JSON_PRETTY_PRINT);
	}


	public function reportDate()
	{
		return json_encode($this->api->getReportDate(),JSON_PRETTY_PRINT);
	}

	public function report_datetime()
	{
		return json_encode($this->api->getReportDateTime(),JSON_PRETTY_PRINT);
	}


	





	






}
