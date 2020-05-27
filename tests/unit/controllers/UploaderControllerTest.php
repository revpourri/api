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

    public function testCreateError()
    {
        $UploaderController = new UploaderController();
        $UploaderController->setInput([
            'name' => null,
            'youtube_id' => null,
        ]);
        $res = $UploaderController->create();
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 400);
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

    public function testSearchParamSort()
    {
        // create a couple uploaders
        $this->createUploader([
            'name' => 'aaa',
            'youtube_id' => '1',
        ]);
        $this->createUploader([
            'name' => 'zzz',
            'youtube_id' => '1',
        ]);

        $_GET = [
            'sort' => 'name:desc'
        ];

        $res = (new UploaderController())->search();

        $content = json_decode($res->getContent(), true);

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content['data'][0]['name'] == 'zzz');

        $_GET = [
            'sort' => 'name:asc'
        ];

        $res = (new UploaderController())->search();

        $content = json_decode($res->getContent(), true);

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content['data'][0]['name'] == 'aaa');
    }

    public function testSearchParamName()
    {
        // create a couple uploaders
        $this->createUploader([
            'name' => 'aaa',
            'youtube_id' => '1',
        ]);
        $this->createUploader([
            'name' => 'zzz',
            'youtube_id' => '1',
        ]);

        $_GET = [
            'name' => 'zzz'
        ];

        $res = (new UploaderController())->search();

        $content = json_decode($res->getContent(), true);

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content['data'][0]['name'] == 'zzz');
    }
}
