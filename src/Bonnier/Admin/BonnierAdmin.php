<?php
namespace Bonnier\Admin;

use Bonnier\HttpResponse;
use Bonnier\RESTBase;

abstract class BonnierAdmin extends RESTBase {

	const SERVICE_URL = 'https://bonnier-admin.herokuapp.com/';
	const SIGNED_REQUEST_ALGORITHM = 'HMAC-SHA256';

	private $accessToken, $appId, $appSecret, $user, $signedRequest;

	public function __construct($appId, $appSecret) {
		parent::__construct();

		$this->appId = $appId;
		$this->appSecret = $appSecret;
	}

	protected function onResponseCreate(HttpResponse $response) {
		return $response;
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

	public function getUser() {
		if ($this->user !== null) {
			// we've already determined this and cached the value.
			return $this->user;
		}

		if(isset($_COOKIE['bonnier_ticket'])) {

		}
	}

	public function getLoginUrl($redirectUri = '', $code = '') {
		$params = array('client_id' => $this->accessToken, 'redirect_uri' => $redirectUri, 'code' => $code);
		return $this->getUrl('oauth/authorize', $params);
	}

	public function getUrl($url, $params = array()) {
		$url = rtrim($this->getServiceUrl(), '/') . '/' . $url;

		if($params) {
			$url .= '?' . http_build_query($params);
		}

		return $url;
	}

	protected function getServiceUrl() {
		return self::SERVICE_URL;
	}

	/**
	 * @param string|null $url
	 * @param string $method
	 * @param array|NULL $data
	 * @throws ServiceException
	 * @return HttpResponse
	 */
	public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL) {
		// TODO: get access token via persistent data (cookie, session etc)
		//$this->getAccessTokenFromPersistentData();

		/*if(!$this->accessToken) {
			$this->getAccessToken();
		}*/

		return parent::api($url, $method, $data);
	}

}