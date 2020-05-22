<?php

namespace Rev\Tests\Unit\Model;

use Rev\Models\AutoModel;

require_once dirname(__FILE__) . "/../BaseTest.php";

class AutoModelTest extends \BaseTest
{
    public function testCreate()
    {
        $make = $this->createMake();
        $model = $this->createModel();
        $AutoModel = new AutoModel();
        $AutoModel->assign([
            'year' => '2000',
            'make_id' => $make['id'],
            'model_id' => $model['id'],
        ]);

        $AutoModel->create();

        $baseObj = $AutoModel->build();

        $this->assertTrue(is_int($baseObj['id']));
        $this->assertEquals($baseObj['make']['id'], $make['id']);
        $this->assertEquals($baseObj['model']['id'], $model['id']);
    }

    public function testValidationPresenceOf()
    {
        $AutoModel = new AutoModel();

        $this->assertFalse($AutoModel->save());

        foreach ($AutoModel->getMessages() as $msg) {
            $this->assertTrue(in_array($msg->getMessage(), [
                'Model ID is required',
                'Make ID is required',
                'Year is required',
            ]));
        }
    }

    public function testValidationDate()
    {
        $make = $this->createMake();
        $model = $this->createModel();
        $AutoModel = new AutoModel();
        $AutoModel->assign([
            'year' => '2000z',
            'make_id' => $make['id'],
            'model_id' => $model['id'],
        ]);

        $this->assertFalse($AutoModel->save());

        foreach ($AutoModel->getMessages() as $msg) {
            $this->assertTrue(in_array($msg->getMessage(), [
                'Year is invalid',
            ]));
        }
    }
}
