<?php

namespace Rev\Tests\Unit\Model;

use Rev\Models\TagModel;

require_once dirname(__FILE__) . "/../BaseTest.php";

class TagModelTest extends \BaseTest
{
    public function testCreate()
    {
        $TagModel = new TagModel();
        $TagModel->assign(
            [
            'value' => 'test',
            ]
        );

        $TagModel->create();

        $baseObj = $TagModel->build();

        $this->assertTrue(is_int($baseObj['id']));
        $this->assertEquals($baseObj['value'], 'test');
    }

    public function testValidationPresenceOf()
    {
        $TagModel = new TagModel();

        $this->assertFalse($TagModel->save());

        foreach ($TagModel->getMessages() as $msg) {
            $this->assertTrue(
                in_array(
                    $msg->getMessage(), [
                    'Value is required',
                    ]
                )
            );
        }
    }
}
