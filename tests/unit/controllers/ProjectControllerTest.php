<?php

namespace Rev\Tests\Unit\Model;

use Rev\Controllers\ProjectController;

require_once dirname(__FILE__) . "/../BaseTest.php";

class ProjectControllerTest extends \BaseTest
{
    public function testGet()
    {
        $project = $this->createProject();
        $res = (new ProjectController())->get($project['id']);

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content->id == $project['id']);
    }

    public function testCreate()
    {
        $uploader = $this->createUploader();
        $auto = $this->createAuto();

        $ProjectController = new ProjectController();
        $ProjectController->setInput([
            'name' => 'name',
            'auto_id' => $auto['id'],
            'uploader_id' => $uploader['id'],
        ]);
        $res = $ProjectController->create();
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertEquals($content->name, 'name');
    }

    public function testUpdate()
    {
        $project = $this->createProject();

        $ProjectController = new ProjectController();
        $ProjectController->setInput([
            'name' => 'new name',
        ]);
        $res = $ProjectController->update($project['id']);
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertEquals($content->name, 'new name');
    }

    public function testDelete()
    {
        $project = $this->createProject();
        $res = (new ProjectController())->delete($project['id']);

        $this->assertTrue($res->getStatusCode() === 204);
    }

    public function testSearch()
    {
        $project = $this->createProject();
        $res = (new ProjectController())->search();

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
    }

    public function testNotFoundStatus()
    {
        $res = (new ProjectController())->get(999999);

        $this->assertTrue($res->getStatusCode() === 404);

        $res = (new ProjectController())->update(999999);

        $this->assertTrue($res->getStatusCode() === 404);

        $res = (new ProjectController())->delete(999999);

        $this->assertTrue($res->getStatusCode() === 404);
    }
}
