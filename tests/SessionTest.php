<?php
use PHPUnit\Framework\TestCase;
use HRMS\Core\Session;

class SessionTest extends TestCase {
    private $session;
    protected function setUp(): void {
        Session::enableTestMode();
        $this->session = Session::getInstance();
    }

    public function testGetandSetSessionValue() {

        $this->session->set('user_id', 123);

        $this->assertEquals(123, $this->session->get('user_id'));
    }

}
