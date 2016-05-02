<?php

use Bonnier\Admin\ServiceOAuth;
use GuzzleHttp\Exception\ClientException;

class ServiceOauthTest extends PHPUnit_Framework_TestCase  {

	protected $redirectUri = 'http://phpsdk.local?redirect=/some/magic/url';
	protected $userRole = 'subscribers';
	protected $authorizeUri = '/api/oauth/authorize';


	public function testGetUserRoles() {

		$service = new ServiceOAuth(getenv('USER_AUTH_APP_ID'), getenv('USER_AUTH_SECRET'), getenv('USER_AUTH_ENDPOINT'));

		$userRoles = $service->getUserRoleList();

		foreach ($userRoles as $userRole) {
			$this->assertArrayHasKey('system_key', $userRole);
			$this->assertArrayHasKey('name', $userRole);
			$this->assertArrayHasKey('locale', $userRole);
		}
	}

	public function testGetUserReturnsNullIfNotLoggedIn() {

		$service = new ServiceOAuth(getenv('USER_AUTH_APP_ID'), getenv('USER_AUTH_SECRET'), getenv('USER_AUTH_ENDPOINT'));

		$user = $service->getUser();

		$this->assertNull($user);
	}


	public function testGetLoginUrl() {

		$service = new ServiceOAuth(getenv('USER_AUTH_APP_ID'), getenv('USER_AUTH_SECRET'), getenv('USER_AUTH_ENDPOINT'));

		$loginUrl = $service->getLoginUrl($this->redirectUri, $this->userRole);

		$parsedLoginUrl = parse_url($loginUrl);
		$parsedParams = [];

		// parse the query parameters and save them in $parsedParams
		parse_str($parsedLoginUrl['query'], $parsedParams);

		// Assert that the redirect url was build correctly
		$this->assertTrue(strpos(getenv('USER_AUTH_ENDPOINT'), $parsedLoginUrl['host']) !== false);
		$this->assertEquals($this->authorizeUri, $parsedLoginUrl['path']);
		$this->assertEquals($this->redirectUri, $parsedParams['redirect_uri']);
		$this->assertEquals($this->userRole, $parsedParams['accessible_for']);
	}

	public function testSetGrantTokenThrowsErrorOnBadCode() {

		$service = new ServiceOAuth(getenv('USER_AUTH_APP_ID'), getenv('USER_AUTH_SECRET'), getenv('USER_AUTH_ENDPOINT'));

		try {
			$service->setGrantToken($this->redirectUri, 'bad_code');
		} catch (ClientException $e) {
			$this->assertEquals(401, $e->getCode());
		}
	}
}
