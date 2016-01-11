<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Driver_Library {

    public $valid_drivers = array();
	
	public function __construct ($drivers = array())
	{
		$this->valid_drivers = $drivers;
	}

	public function scrape($search)
	{
		$curl_options = array(
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36',
			CURLOPT_AUTOREFERER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => 4
		);
		$output = '';
		while ($search['url'])
		{
			libxml_use_internal_errors(true);
			$page = curl_init($search['url']);
			curl_setopt_array($page, $curl_options);
			$dom = new DOMDocument;
			$dom->loadHTML(curl_exec($page), LIBXML_NOBLANKS | LIBXML_NOWARNING | LIBXML_NOERROR);
			curl_close($page);
			$xpath = new DOMXPath($dom);
			$search['url'] = $this->$search['site_class']->get_next_page_url($xpath);
			foreach ($this->$search['site_class']->get_rows($xpath) as $row)
			{
				$output .= $this->$search['site_class']->get_fields($row);
			}
		}
		return $output;
	}

	public function clean_field ($field)
	{
		$field = str_replace(chr(0xC2) . chr(0xA0), ' ', $field);
		$field = preg_replace('/[\h\v]+/', ' ', $field);
		$field = trim($field);
		$field = ltrim($field, '+-');
		$field = html_entity_decode($field, ENT_QUOTES);
		$field = strip_tags($field);
		$field = str_replace('"', '""', $field);
		return $field;
	}

}