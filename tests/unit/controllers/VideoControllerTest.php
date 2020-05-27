<?php

namespace Rev\Tests\Unit\Model;

use Rev\Controllers\VideoController;

require_once dirname(__FILE__) . "/../BaseTest.php";

class VideoControllerTest extends \BaseTest
{
    public function testGet()
    {
        $video = $this->createVideo();
        $res = (new VideoController())->get($video['id']);

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content->id == $video['id']);
    }

    public function testCreate()
    {
        $uploader = $this->createUploader();

        $VideoController = new VideoController();
        $VideoController->setInput([
            'title' => 'title',
            'youtube_id' => 'youtube_id',
            'uploader_id' => $uploader['id'],
            'published_date' => '2000-01-01',
            'type' => 'review',
        ]);
        $res = $VideoController->create();
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertEquals($content->title, 'title');

        // Create video, add to project
        $project = $this->createProject();
        $VideoController = new VideoController();
        $VideoController->setInput([
            'title' => 'title2',
            'youtube_id' => 'youtube_id2',
            'uploader_id' => $uploader['id'],
            'published_date' => '2000-01-01',
            'type' => 'project',
            'project_id' => $project['id'],
        ]);
        $res = $VideoController->create();
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
    }

    public function testCreateError()
    {
        $VideoController = new VideoController();
        $VideoController->setInput([
            'title' => null,
        ]);
        $res = $VideoController->create();
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 400);
    }

    public function testUpdate()
    {
        $video = $this->createVideo();

        $VideoController = new VideoController();
        $VideoController->setInput([
            'title' => 'new title',
        ]);
        $res = $VideoController->update($video['id']);
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertEquals($content->title, 'new title');
    }

    public function testDelete()
    {
        $video = $this->createVideo();
        $res = (new VideoController())->delete($video['id']);

        $this->assertTrue($res->getStatusCode() === 204);
    }

    public function testSearch()
    {
        $video = $this->createVideo();
        $res = (new VideoController())->search();

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
    }

    public function testNotFoundStatus()
    {
        $res = (new VideoController())->get(999999);

        $this->assertTrue($res->getStatusCode() === 404);

        $res = (new VideoController())->update(999999);

        $this->assertTrue($res->getStatusCode() === 404);

        $res = (new VideoController())->delete(999999);

        $this->assertTrue($res->getStatusCode() === 404);
    }
}
