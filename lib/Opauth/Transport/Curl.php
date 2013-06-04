<?php
/**
 * Opauth
 * Multi-provider authentication framework for PHP
 *
 * @copyright    Copyright © 2013 U-Zyn Chua (http://uzyn.com)
 * @link         http://opauth.org
 * @license      MIT License
 */
namespace Opauth\Transport;
use Opauth\Transport\Base;
use \Exception;

/**
 * Opauth HttpClient
 * Very simple httpclient using file_get_contents or curl
 *
 * @package      Opauth
 */
class Curl extends Base {


	/**
	 * Makes a request using curl
	 *
	 * @param string $url
	 * @param array $options
	 * @return string Response body
	 */
	protected function request($url, $options = array()) {
		if (!function_exists('curl_init')) {
			throw new Exception('Curl not supported, try using other http_client_method such as file');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		if (!empty($options['http']['method']) && strtoupper($options['http']['method']) === 'POST') {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $options['http']['content']);
		}
		$content = curl_exec($ch);
		curl_close($ch);
		list($headers, $content) = explode("\r\n\r\n", $content, 2);
		$this->responseHeaders = $headers;
		return $content;
	}

}