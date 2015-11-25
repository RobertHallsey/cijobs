<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Searches extends CI_Controller {

protected $sites = array();
protected $searches = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('download');
		$this->load->library('form_validation');
		$this->load->library('table');
		$this->load->model('searches_model');
		$this->sites = $this->searches_model->get_sites();
		$this->searches = $this->searches_model->get_searches();
	}

	public function index()
	{
		$this->form_validation->set_rules('search[name]', 'Search Name', 'required');
		$this->form_validation->set_rules('search[site_id]', 'Job Site', 'required');
		$this->form_validation->set_rules('search[url]', 'Search URL', 'required');
		if ($this->form_validation->run())
		{
			$data = $this->input->post('search', TRUE);
			$search_id = $this->searches_model->add_search($data);
			redirect('searches');
		}
		$data = array(
			'subview' => 'search_add_view',
			'sites' => $this->sites,
			'searches' => $this->searches
		);
		$this->load->view('searches_view', $data);
	}

	public function edit($search_id)
	{
		$search = $this->searches_model->get_search($search_id);
		$search['id'] = $search_id;
		$this->form_validation->set_rules('search[name]', 'Search Name', 'required');
		$this->form_validation->set_rules('search[site_id]', 'Job Site', 'required');
		$this->form_validation->set_rules('search[url]', 'Search URL', 'required');
		if ($this->form_validation->run())
		{
			$search = $this->input->post('search', TRUE);
			$data = array(
				'id' => $search_id,
				'name' => $search['name'],
				'site_id' => $search['site_id'],
				'url' => $search['url']
			);
			$this->searches_model->update_search($data);
			redirect('searches');
		}
		$data = array(
			'subview' => 'search_edit_view',
			'sites' => $this->sites,
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
			redirect('searches');
		}
		$data = array(
			'subview' => 'search_delete_view',
			'sites' => $this->sites,
			'searches' => $this->searches,
			'search' => $search
		);
		$this->load->view('searches_view', $data);
	}
	
	public function execute($search_id)
	{
		$search = $this->searches_model->get_search($search_id);
		$search['id'] = $search_id;
		$this->load->library($search['site_class']);
		$output = $this->$search['site_class']->scrape($search);
		force_download($search['name'] . '.csv', $output);
		redirect('searches');
	}

}

