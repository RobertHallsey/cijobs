<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Searches_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_searches()
	{
		return $this->db->query(
'SELECT
	searches.id AS id,
	searches.name AS name,
	searches.url AS url,
	sites.id AS site_id,
	sites.name AS site_name,
	sites.class AS site_class
FROM searches
INNER JOIN sites ON
	searches.site_id = sites.id
;'
		)->result_array();
	}

	public function get_search($search_id)
	{
		$query = $this->db->query(
'SELECT
	searches.name AS name,
	searches.url AS url,
	sites.id AS site_id,
	sites.name AS site_name,
	sites.class AS site_class
FROM searches
INNER JOIN sites ON
	searches.site_id = sites.id
WHERE searches.id = ' . $search_id . '
;'
		)->result_array();
		return $query[0];
	}
	
	public function add_search($data)
	{
		$this->db->insert('searches', $data);
		return $this->db->insert_id();
	}
	
	public function update_search($data)
	{
		$this->db->update('searches', array_slice($data, 1), array_slice($data, 0, 1));
	}

	public function delete_search($data)
	{
		$this->db->delete('searches', $data);
	}

	public function get_sites()
	{
		return $this->db->query(
'SELECT
	sites.id AS id,
	sites.name AS name,
	sites.class AS class
FROM sites
;'
		)->result_array();
	}

	public function get_drivers()
	{
		return $this->db->query(
'SELECT
	sites.class AS class
FROM sites
;'
		)->result_array();
	}
	
}
