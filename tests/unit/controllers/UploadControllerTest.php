<?php

namespace Rev\Tests\Unit\Model;

use Rev\Controllers\UploadController;

require_once dirname(__FILE__) . "/../BaseTest.php";

class UploadControllerTest extends \BaseTest
{
    public function testCreate()
    {
        // Copy test image to tmp
        copy(dirname(__FILE__) . "/../../_data/rev.png", dirname(__FILE__) . "/../../../storage/temp/fileTemp");

        $UploadController = new UploadController();
        $UploadController->setFile(
            new \Phalcon\Http\Request\File(
                [
                'key' => 'file',
                'name' => 'rev.png',
                'tmp' => '/tmp/fileTemp',
                'type' => 'image/png',
                ]
            )
        );
        $res = $UploadController->create();
        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content->filename === 'rev.png');
    }
}
