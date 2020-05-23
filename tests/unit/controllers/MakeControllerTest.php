<?php

namespace Rev\Tests\Unit\Model;

use Rev\Controllers\MakeController;

require_once dirname(__FILE__) . "/../BaseTest.php";

class MakeControllerTest extends \BaseTest
{
    public function testGet()
    {
        $make = $this->createMake();
        $res = (new MakeController())->get($make['id']);

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content->id == $make['id']);
    }

    public function testSearch()
    {
        $make = $this->createMake();
        $res = (new MakeController())->search();

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
    }

    public function testNotFoundStatus()
    {
        $res = (new MakeController())->get(999999);

        $this->assertTrue($res->getStatusCode() === 404);
    }
}
