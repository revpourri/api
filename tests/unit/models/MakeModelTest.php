<?php

namespace Rev\Tests\Unit\Model;

use Rev\Models\MakeModel;

require_once dirname(__FILE__) . "/../BaseTest.php";

class MakeModelTest extends \BaseTest
{
    public function testCreate()
    {
        $MakeModel = new MakeModel();
        $MakeModel->assign([
            'value' => 'test 1',
        ]);

        $MakeModel->create();

        $baseObj = $MakeModel->build();

        $this->assertTrue(is_int($baseObj['id']));
        $this->assertEquals($baseObj['value'], 'test 1');
        $this->assertEquals($baseObj['slug'], 'test-1');
    }

    public function testValidationPresenceOf()
    {
        $MakeModel = new MakeModel();

        $this->assertFalse($MakeModel->save());

        foreach ($MakeModel->getMessages() as $msg) {
            $this->assertTrue(in_array($msg->getMessage(), [
                'Value is required',
            ]));
        }
    }
}
