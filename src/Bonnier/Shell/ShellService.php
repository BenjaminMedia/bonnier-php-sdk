<?php
namespace Bonnier\Shell;

use Bonnier\HttpRequest;

class ShellService {

    protected $username;
    protected $password;

    protected $partial;
    protected $javascriptPosition;

    const SERVICE_URL = 'http://%s/api/v2/external_headers';

    const JS_POSITION_HEADER = 'header';
    const JS_POSITION_FOOTER = 'footer';

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
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
     */
    public function setJavascriptPosition($javascriptPosition) {
        $this->javascriptPosition = $javascriptPosition;
    }

    protected function generateUrl($domain) {
        $url = sprintf(self::SERVICE_URL, $domain);

        if($this->javascriptPosition) {
            $url .= sprintf('?javascript_position=%s', $this->javascriptPosition);
        }

        return $url;
    }

    /**
     * @param $domain
     *
     * @return \Bonnier\Shell\ShellResponse
     */
    public function get($domain) {
        $url = $this->generateUrl($domain);

        $request = new HttpRequest($url);
        $request->setBasicAuth($this->username, $this->password);

        return new ShellResponse($request->execute(true));
    }

}