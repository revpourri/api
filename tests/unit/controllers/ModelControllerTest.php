<?php

namespace Rev\Tests\Unit\Model;

use Rev\Controllers\ModelController;

require_once dirname(__FILE__) . "/../BaseTest.php";

class ModelControllerTest extends \BaseTest
{
    public function testGet()
    {
        $model = $this->createModel();
        $res = (new ModelController())->get($model['id']);

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content->id == $model['id']);
    }

    public function testSearch()
    {
        $model = $this->createModel();
        $res = (new ModelController())->search();

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
    }

    public function testNotFoundStatus()
    {
        $res = (new ModelController())->get(999999);

        $this->assertTrue($res->getStatusCode() === 404);
    }
}
