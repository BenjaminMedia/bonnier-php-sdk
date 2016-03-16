<?php

use Bonnier\Shell\ServiceShell;

class ServiceShellTest extends PHPUnit_Framework_TestCase  {

	const SHELL_USER = '3edea4c6cd45eb69c816f776924a6e26b3baac48b40a80bf515d1357c45db44a';
	const SHELL_SECRET = 'ee21b3981ef62ef3c5534dda3437b51354471a31e82f60674d39ff35179ac100';

	public function testGetShell() {

		$service = new ServiceShell(self::SHELL_USER, self::SHELL_SECRET);

		$shell = $service->get('woman.dk');
		$shellNoBanners = $service->withoutBanners()->get('woman.dk');
		$shellNoBannersJsPosition = $service->withoutBanners()
											->setJavascriptPosition(ServiceShell::JS_POSITION_FOOTER)
											->get('woman.dk');

		$this->assertNotEmpty($shell->getHead());
		$this->assertNotEmpty($shell->getHeader());
		$this->assertNotEmpty($shell->getLogos());
		$this->assertNotEmpty($shell->getStartTag());
		$this->assertNotEmpty($shell->getEndTag());
		$this->assertNotEmpty($shell->getBody());
		$this->assertNotEmpty($shell->getBanners());
		$this->assertNotEmpty($shell->getFooter());

		$this->assertEquals(
			'http://woman.dk/api/v2/external_headers',
			$shell->getRequestUri()
		);

		$this->assertEquals(
			'http://woman.dk/api/v2/external_headers?without_banners=true',
			$shellNoBanners->getRequestUri()
		);

		$this->assertEquals(
			'http://woman.dk/api/v2/external_headers?javascript_position=footer&without_banners=true',
			$shellNoBannersJsPosition->getRequestUri()
		);
	}

    public function testGetShellFullUrl() {

        $service = new ServiceShell(self::SHELL_USER, self::SHELL_SECRET);

        $url = 'http://woman.dk/api/v2/external_headers?without_banners=true';

        $shellNoBanners = $service->get($url);

        $this->assertNotEmpty($shellNoBanners->getHead());
        $this->assertNotEmpty($shellNoBanners->getHeader());
        $this->assertNotEmpty($shellNoBanners->getLogos());
        $this->assertNotEmpty($shellNoBanners->getStartTag());
        $this->assertNotEmpty($shellNoBanners->getEndTag());
        $this->assertNotEmpty($shellNoBanners->getBody());
        $this->assertNotEmpty($shellNoBanners->getBanners());
        $this->assertNotEmpty($shellNoBanners->getFooter());

        $this->assertEquals($url, $shellNoBanners->getRequestUri());

    }
}
