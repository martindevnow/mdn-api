<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $_headers = [];

    public function setUp() {
        parent::setUp();
        $this->_headers = [];
        $this->addHeader('HTTP_X-Requested-With', 'XMLHttpRequest');
    }

    protected function addHeader($key, $val) {
        $this->_headers[$key] = $val;
    }

    protected function callPost($url, $data) {
        return $this->withHeaders($this->_headers)
            ->json('POST', $url, $data);
    }

    protected function callGet($url) {
        return $this->withHeaders($this->_headers)
            ->json('GET', $url);
    }

    protected function callPatch($url, $data) {
        return $this->withHeaders($this->_headers)
            ->json('PATCH', $url, $data);
    }

    protected function callDelete($url) {
        return $this->withHeaders($this->_headers)
            ->json('DELETE', $url);
    }
}
