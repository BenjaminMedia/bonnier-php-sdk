<?php
namespace Bonnier\Shell;

use GuzzleHttp\Client;

class ServiceShell extends Client{

    protected $username;
    protected $password;

    protected $partial;
    protected $javascriptPosition;
    protected $withoutBanners = false;

    const SERVICE_URL = 'http://%s/api/v2/external_headers';

    const JS_POSITION_HEADER = 'header';
    const JS_POSITION_FOOTER = 'footer';
    const WITHOUT_BANNERS = 'without_banners=true';
    const WITHOUT_ADS = 'without_ads=true';

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
        parent::__construct(['auth' => [$username, $password]]);
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function getPartial() {
        return $this->partial;
    }

    /**
     * @param bool $partial
     */
    public function setPartial($partial) {
        $this->partial = $partial;
    }

    /**
     * @return string
     */
    public function getJavascriptPosition() {
        return $this->javascriptPosition;
    }

    /**
     * @param string $javascriptPosition
     * @return $this
     */
    public function setJavascriptPosition($javascriptPosition) {
        $this->javascriptPosition = $javascriptPosition;
        return $this;
    }

    protected function generateUrl($domain) {

        if(filter_var($domain, FILTER_VALIDATE_URL)) {
            return $domain;
        }

        $url = sprintf(self::SERVICE_URL, $domain);

        if($this->javascriptPosition) {
            $url .= sprintf('?javascript_position=%s', $this->javascriptPosition);
        }

        if($this->withoutBanners) {

            $withoutBannerParam = $this->getWithoutBannersParam($url);

            if (strpos($url, '?')) {
                $url .= '&' . $withoutBannerParam;
            }else {
                $url .= '?' . $withoutBannerParam;
            }
        }

        return $url;
    }

    /**
     * Disable banners
     *
     * @return $this
     */
    public function withoutBanners() {
        $this->withoutBanners = true;
        return $this;
    }

    /**
     * @param String $uri domain for the shell i.e. woman.dk or full api URI ie. http://woman.dk/api/v2/external_headers
     *
     * @return \Bonnier\Shell\ShellResponse
     */
    public function get($uri) {

        $requestUrl = $this->generateUrl($uri);

        $request = parent::get($requestUrl);

        return new ShellResponse($request, $requestUrl);
    }

    private function getWithoutBannersParam($url) {
        return strpos($url, 'v3') ? self::WITHOUT_ADS : self::WITHOUT_BANNERS;
    }

}