<?php

namespace Rev\Tests\Unit\Model;

use Rev\Controllers\AutoController;

require_once dirname(__FILE__) . "/../BaseTest.php";

class AutoControllerTest extends \BaseTest
{
    public function testGet()
    {
        $auto = $this->createAuto();
        $res = (new AutoController())->get($auto['id']);

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content->id == $auto['id']);
    }

    public function testCreate()
    {
        $make = $this->createMake();
        $model = $this->createModel();

        $AutoController = new AutoController();
        $AutoController->setInput([
            'year' => '1999',
            'make_id' => $make['id'],
            'model_id' => $model['id'],
        ]);
        $res = $AutoController->create();
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertEquals($content->make->id, $make['id']);
        $this->assertEquals($content->model->id, $model['id']);
    }

    public function testCreateError()
    {
        $AutoController = new AutoController();
        $AutoController->setInput([
            'year' => null,
        ]);
        $res = $AutoController->create();
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 400);
    }

    public function testUpdate()
    {
        $auto = $this->createAuto();

        $AutoController = new AutoController();
        $AutoController->setInput([
            'year' => '1990',
        ]);
        $res = $AutoController->update($auto['id']);
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertEquals($content->year, '1990');
    }

    public function testDelete()
    {
        $auto = $this->createAuto();
        $res = (new AutoController())->delete($auto['id']);

        $this->assertTrue($res->getStatusCode() === 204);
    }

    public function testSearch()
    {
        $auto = $this->createAuto();
        $res = (new AutoController())->search();

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
    }

    public function testNotFoundStatus()
    {
        $res = (new AutoController())->get(999999);

        $this->assertTrue($res->getStatusCode() === 404);

        $res = (new AutoController())->update(999999);

        $this->assertTrue($res->getStatusCode() === 404);

        $res = (new AutoController())->delete(999999);

        $this->assertTrue($res->getStatusCode() === 404);
    }
}
