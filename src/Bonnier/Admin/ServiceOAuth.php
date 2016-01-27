<?php
namespace Bonnier\Admin;

use GuzzleHttp\Client;
use Bonnier\ServiceException;

class ServiceOAuth extends Client {

	const SERVICE_URL = 'https://bonnier-admin.herokuapp.com/';
	/** @var null|string Overrides self::SERVICE_URL */
	private $serviceEndpoint = null;

	private $accessToken, $appId, $appSecret, $user;

	public function __construct($appId, $appSecret, $serviceEndpoint = null) {
		$this->appId = $appId;
		$this->appSecret = $appSecret;
		$this->serviceEndpoint = $serviceEndpoint;

		parent::__construct(['base_uri' => $this->getServiceUrl()]);
	}

	/**
	 * @return string
	 */
	public function getAppId() {
		return $this->appId;
	}

	/**
	 * @param string $appId
	 */
	public function setAppId($appId) {
		$this->appId = $appId;
	}

	/**
	 * @return string
	 */
	public function getAppSecret() {
		return $this->appSecret;
	}

	/**
	 * @param string $appSecret
	 */
	public function setAppSecret($appSecret) {
		$this->appSecret = $appSecret;
	}

	/**
	 * Get the currently active access_token
	 *
	 * @return mixed
	 */
	public function getAccessToken() {
		return $this->accessToken;
	}

	/**
	 * Set the access token
	 *
	 * @param $token
	 */
	public function setAccessToken($token) {
		$this->accessToken = $token;
	}

	/**
	 * Sets grant token and thereby provides a valid access_token
	 *
	 * @param $redirectUrl
	 * @param $code
	 * @throws ServiceException
	 */
	public function setGrantToken($redirectUrl, $code) {
		$data = array('client_id' => $this->appId,
			'client_secret' => $this->appSecret,
			'code' => $code,
			'grant_type' => 'authorization_code',
			'redirect_uri' => $redirectUrl);

		$response = $this->post('oauth/token', ['form_params' => $data]);
		$response = json_decode($response->getBody()->getContents(), true);

		if(isset($response['error'])) {
			throw new ServiceException($response['error_description']);
		}

		if(!isset($response['access_token'])) {
			throw new ServiceException('Failed to get valid access_token');
		}

		$this->accessToken = $response['access_token'];
	}

	/**
	 * Get the currently signed in user.
	 *
	 * @return mixed
	 * @throws ServiceException
	 */
	public function getUser() {
		if ($this->user !== null) {
			return $this->user;
		}

		if($this->accessToken) {

			$response = $this->get('api/users/current.json', ['headers' => [
				'Authorization' => 'Bearer ' . $this->accessToken
			]]);

			if($response->getStatusCode() == 200) {
				$this->user = json_decode($response->getBody()->getContents());
			}
		}

		return $this->user;
	}

	/**
	 * Get login url
	 *
	 * @param string $redirectUri
	 * @param string $code
	 * @return string
	 */
	public function getLoginUrl($redirectUri = '', $code = '') {
		$params = array('client_id' => $this->appId, 'redirect_uri' => $redirectUri, 'response_type' => 'code');
		return $this->getUrl('oauth/authorize', $params);
	}

	/**
	 * Generates url with parameters
	 *
	 * @param $url
	 * @param array $params
	 * @return string
	 */
	protected function getUrl($url, $params = array()) {
		$url = rtrim($this->getServiceUrl(), '/') . '/' . $url;

		if($params) {
			$url .= '?' . http_build_query($params);
		}

		return $url;
	}

	/**
	 * Get service url
	 *
	 * @return string
	 */
	public function getServiceUrl() {
		if(is_null($this->serviceEndpoint)) {
			return self::SERVICE_URL;
		}
		return $this->serviceEndpoint;
	}

}
