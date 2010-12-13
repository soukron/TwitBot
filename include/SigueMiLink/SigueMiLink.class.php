<?php
/**
 * SigueMiLink.class.php - API interface to http://smlk.es
 *
 * This class implements, using http://smlk.es API, functions to
 * create shorturls and check shoturls destination.
 *
 * @author      Soukron <soukron@gmbros.net>
 * @version     1.0
 * @copyright   Copyright (c) 2009-2010 Soukron (soukron@gmbros.net)
 * @license     http://www.gnu.org/licenses/gpl.html     GPL License
 */

class SMLK {
	/**
	 * getDestination - Gets the destination of a given shorturl
	 *
	 * This function retrieves the destination of a shorturl created
	 * with http://smlk.es
	 *
	 * @param	string link		short url
	 * @return	string
	 */ 
	function getDestination($link = NULL) {
		if (!$link) { return NULL; }
		
		return json_decode($this->apiCall("get_destination", array("link" => $link)));
	}

	/**
	 * createLink - Creates a shorturl to a long destination
	 *
	 * It calls to http://smlk.es API to create a short url
	 *
	 * @param        string destination	Destination
	 * @return       array
	 */
	function createLink($destination = NULL) {
		if (!$destination) { return NULL; }
		
		$data = $this->apiCall("create", array("destination" => urlencode($destination)));

		return json_decode($data);
	}

	/**
	 * apiCall - Makes an API call
	 *
	 * Makes API calls to http://smlk.es using CURL
	 *
	 * @param	string method	Method invoked
	 * @param	array options	Options needed
	 * @return	array
	 */
	protected function apiCall($method, $options) {
		$curl_handle = curl_init();
		$api_url = "http://smlk.es/api/".$method."/";
		
		if (count($options) > 0) {
			$api_url .= '?' . http_build_query($options);
		}

		curl_setopt($curl_handle, CURLOPT_URL, $api_url);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Expect:'));
		$data = curl_exec($curl_handle);
		curl_close($curl_handle);
		return $data;
	}
}
?>