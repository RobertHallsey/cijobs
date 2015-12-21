<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site_template extends CI_Driver {

	public function scrape($search)
	{
		$output = '';
		$url = $search['url'];
		while ($url)
		{
			$dom = $this->get_page($url);
			$xpath = new DOMXPath($dom);
			// find next page
			// your code goes here
			// $url = next page or '' 
			// extract rows from page
			// your code goes here
			// $elements = $xpath->query('your-rows');
			// or load $elements by some other means
			foreach ($elements as $element)
			{
				$output .= $this->extract_row($element);
			}
		}
		return $output;
	}
	
	public function extract_row($row)
	{
		$fields = array(
			// 'field_1' => '',
			// 'field_2' => '',
			// 'field_3' => '',
			// whatever fields you want
		);
		$dom = new DOMDocument;
		$dom->appendChild($dom->importNode($row, true));
		$xpath = new DOMXPath($dom);
		// your code goes here
		// extract data from row and
		// assign it to $fields array
		$line = '"' . implode('","', $fields) . '"' . "\r\n";
		return ($line);
	}
}
