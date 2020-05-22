<?php

namespace Rev\Tests\Unit\Model;

use Rev\Models\VideoModel;

require_once dirname(__FILE__) . "/../BaseTest.php";

class VideoModelTest extends \BaseTest
{
    public function testCreate()
    {
        $uploader = $this->createUploader();
        $VideoModel = (new VideoModel())->assign([
            'title' => 'test',
            'youtube_id' => 'test',
            'uploader_id' => $uploader['id'],
            'published_date' => '2020-01-01',
            'type' => 'review',
        ]);

        $VideoModel->create();

        $baseObj = $VideoModel->build();

        $this->assertTrue(is_int($baseObj['id']));
        $this->assertEquals($baseObj['title'], 'test');
        $this->assertEquals($baseObj['youtube_id'], 'test');
        $this->assertEquals($baseObj['uploader_id'], $uploader['id']);
        $this->assertEquals($baseObj['published_date'], '2020-01-01');
    }

    public function testValidationPresenceOf()
    {
        $VideoModel = (new VideoModel());

        $this->assertFalse($VideoModel->save());

        foreach ($VideoModel->getMessages() as $msg) {
            $this->assertTrue(in_array($msg->getMessage(), [
                'Title is required',
                'Uploader ID is required',
                'Type is required',
                'Published Date is required',
            ]));
        }
    }

    public function testValidationConstraints()
    {
        // Uploader does not exist
        $VideoModel = (new VideoModel())->assign([
            'title' => 'title',
            'type' => 'review',
            'published_date' => '2020-01-01',
            'uploader_id' => 999999,
        ]);

        $this->assertFalse($VideoModel->save());

        foreach ($VideoModel->getMessages() as $msg) {
            $this->assertEquals($msg->getMessage(), "Value of field \"uploader_id\" does not exist on referenced table");
        }
    }

    public function testValidationDate()
    {
        $uploader = $this->createUploader();
        // Valid Published Date
        $VideoModel = (new VideoModel())->assign([
            'title' => 'title',
            'type' => 'review',
            'published_date' => '2020-13-01',
            'uploader_id' => $uploader['id'],
        ]);

        $this->assertFalse($VideoModel->save());

        foreach ($VideoModel->getMessages() as $msg) {
            $this->assertEquals($msg->getMessage(), "Published Date is invalid");
        }
    }
}
