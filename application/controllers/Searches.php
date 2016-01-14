<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Searches extends CI_Controller {

protected $site_list = array();
protected $searches = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('download');
		$this->load->library('form_validation');
		$this->load->library('table');
		$this->load->model('searches_model');
		$this->site_list = $this->searches_model->get_sites();
		$this->searches = $this->searches_model->get_searches();
		$this->load->driver('sites', array_column($this->site_list, 'driver'));
	}

	public function index()
	{
		$this->form_validation->set_rules('search[name]', 'Search Name', 'required');
		$this->form_validation->set_rules('search[site_id]', 'Site', 'required');
		$this->form_validation->set_rules('search[url]', 'Search URL', 'required');
		if ($this->form_validation->run())
		{
			$data = $this->input->post('search', TRUE);
			$search_id = $this->searches_model->add_search($data);
			redirect();
		}
		$data = array(
			'subview' => 'search_add_view',
			'sites' => $this->site_list,
			'searches' => $this->searches
		);
		$this->load->view('searches_view', $data);
	}

	public function edit($search_id)
	{
		$search = $this->searches_model->get_search($search_id);
		$this->form_validation->set_rules('search[name]', 'Search Name', 'required');
		$this->form_validation->set_rules('search[site_id]', 'Site', 'required');
		$this->form_validation->set_rules('search[url]', 'Search URL', 'required');
		if ($this->form_validation->run())
		{
			$data = $this->input->post('search', TRUE);
			$this->searches_model->update_search($search_id, $data);
			redirect();
		}
		$data = array(
			'subview' => 'search_edit_view',
			'sites' => $this->site_list,
			'searches' => $this->searches,
			'search' => $search
		);
		$this->load->view('searches_view', $data);
	}
	
	public function delete($search_id)
	{
		$search = $this->searches_model->get_search($search_id);
		$search['id'] = $search_id;
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$data = array('id' => $search_id);
			$this->searches_model->delete_search($data);
			redirect();
		}
		$data = array(
			'subview' => 'search_delete_view',
			'sites' => $this->site_list,
			'searches' => $this->searches,
			'search' => $search
		);
		$this->load->view('searches_view', $data);
	}
	
	public function execute($search_id)
	{
		$search = $this->searches_model->get_search($search_id);
		$this->form_validation->set_rules('search[url]', 'Search URL', 'required');
		if ($this->form_validation->run())
		{
			$search['url'] = $this->input->post('search[url]', true);
			$output = $this->sites->scrape($search['url'], $search['driver']);
			force_download($search['name'] . '.csv', $output);
		}
		$data = array(
			'subview' => 'search_execute_view',
			'sites' => $this->site_list,
			'searches' => $this->searches,
			'search' => $search
		);
		$this->load->view('searches_view', $data);
	}

}

