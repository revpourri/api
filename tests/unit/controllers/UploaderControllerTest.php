<?php

namespace Rev\Tests\Unit\Model;

use Rev\Controllers\UploaderController;

require_once dirname(__FILE__) . "/../BaseTest.php";

class UploaderControllerTest extends \BaseTest
{
    public function testGet()
    {
        $uploader = $this->createUploader();
        $res = (new UploaderController())->get($uploader['id']);

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content->id == $uploader['id']);
    }

    public function testCreate()
    {
        $UploaderController = new UploaderController();
        $UploaderController->setInput([
            'name' => 'name',
            'youtube_id' => 'youtube_id',
        ]);
        $res = $UploaderController->create();
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
    }

    public function testSearch()
    {
        $uploader = $this->createUploader();
        $res = (new UploaderController())->search();

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
    }

    public function testNotFoundStatus()
    {
        $res = (new UploaderController())->get(999999);

        $this->assertTrue($res->getStatusCode() === 404);
    }
}
