<?php

namespace Rev\Tests\Unit\Model;

use Rev\Models\ModelModel;

require_once dirname(__FILE__) . "/../BaseTest.php";

class ModelModelTest extends \BaseTest
{
    public function testCreate()
    {
        $ModelModel = new ModelModel();
        $ModelModel->assign([
            'value' => 'test 1',
        ]);

        $ModelModel->create();

        $baseObj = $ModelModel->build();

        $this->assertTrue(is_int($baseObj['id']));
        $this->assertEquals($baseObj['value'], 'test 1');
        $this->assertEquals($baseObj['slug'], 'test-1');
    }

    public function testValidationPresenceOf()
    {
        $ModelModel = new ModelModel();

        $this->assertFalse($ModelModel->save());

        foreach ($ModelModel->getMessages() as $msg) {
            $this->assertTrue(in_array($msg->getMessage(), [
                'Value is required',
            ]));
        }
    }
}
