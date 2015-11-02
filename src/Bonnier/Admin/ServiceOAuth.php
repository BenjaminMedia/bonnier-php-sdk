<?php
namespace Bonnier\Admin;

use Pecee\Http\HttpResponse;
use Pecee\Http\RestBase;
use Bonnier\ServiceException;

class ServiceOAuth extends RestBase {

	const SERVICE_URL = 'https://bonnier-admin.herokuapp.com/';

	private $accessToken, $appId, $appSecret, $user;

	public function __construct($appId, $appSecret) {
		parent::__construct();

		$this->appId = $appId;
		$this->appSecret = $appSecret;
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

		$response = json_decode($this->api('oauth/token', self::METHOD_POST, $data)->getResponse(), true);

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
			$this->httpRequest->addHeader('Authorization: Bearer ' . $this->accessToken);
			/* @var $response HttpResponse */
			$response = $this->api('api/users/current.json');

			if($response->getStatusCode() == 200) {
				$this->user = json_decode($response->getResponse());
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
		return self::SERVICE_URL;
	}

}