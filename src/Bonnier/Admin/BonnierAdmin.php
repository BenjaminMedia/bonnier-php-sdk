<?php
namespace Bonnier\Admin;

use Bonnier\HttpRequest;
use Bonnier\HttpResponse;
use Bonnier\RESTBase;

class BonnierAdmin extends RESTBase {

	const SERVICE_URL = 'https://bonnier-admin.herokuapp.com/';

	private $accessToken, $appId, $appSecret, $user;

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

	public function getAccessToken() {
		if(!$this->accessToken) {
			$params = array(
				'grant_type' => 'client_credentials',
				'client_id' => $this->appId,
				'client_secret' => $this->appSecret
			);

			$response = $this->api('oauth/token', self::METHOD_POST, $params);

			if($response->getStatusCode() == 200) {
				$result = json_decode($response->getResponse());

				if($result->access_token) {
					$this->accessToken = $result->access_token;
				}
			}
		}
		return $this->accessToken;
	}

	public function setAccessToken($token) {
		return $this->accessToken = $token;
	}

	public function getUser() {
		if ($this->user !== null) {
			return $this->user;
		}

		if($this->accessToken) {
			$this->request->addHeader('Authorization: Bearer ' . $this->accessToken);
			$response = $this->api('api/users/current.json');

			die(var_dump($response->getResponse()));

			if($response->getStatusCode() == 200) {
				$result = json_decode($response->getResponse());
				die(var_dump($response->getResponse()));
			}
		}

		return $this->user;
	}

	public function getLoginUrl($redirectUri = '', $code = '') {
		$params = array('client_id' => $this->appId, 'redirect_uri' => $redirectUri, 'response_type' => 'code');
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
	 *
	public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL) {
		$this->request->setTimeout(1000);
		if(!$this->accessToken) {
			$this->getAccessToken();
		}

		return parent::api($url, $method, $data);
	}*/

}