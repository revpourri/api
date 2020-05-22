<?php

namespace Rev\Tests\Unit\Model;

use Rev\Models\UploaderModel;

require_once dirname(__FILE__) . "/../BaseTest.php";

class UploaderModelTest extends \BaseTest
{
    public function testCreate()
    {
        $UploaderModel = (new UploaderModel())->assign([
            'name' => 'test',
            'youtube_id' => 'test',
        ]);

        $UploaderModel->create();

        $baseObj = $UploaderModel->build();

        $this->assertTrue(is_int($baseObj['id']));
        $this->assertEquals($baseObj['name'], 'test');
        $this->assertEquals($baseObj['youtube_id'], 'test');
    }

    public function testValidationPresenceOf()
    {
        $UploaderModel = new UploaderModel();

        $this->assertFalse($UploaderModel->save());

        foreach ($UploaderModel->getMessages() as $msg) {
            $this->assertTrue(in_array($msg->getMessage(), [
                'Name is required',
                'Youtube ID is required',
            ]));
        }
    }
}
