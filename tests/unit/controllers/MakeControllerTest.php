<?php

namespace Rev\Tests\Unit\Model;

use Rev\Controllers\MakeController;

require_once dirname(__FILE__) . "/../BaseTest.php";

class MakeControllerTest extends \BaseTest
{
    public function testGet()
    {
        $make = $this->createMake();
        $res = (new MakeController())->get($make['id']);

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content->id == $make['id']);
    }

    public function testSearch()
    {
        $make = $this->createMake();
        $res = (new MakeController())->search();

        $content = json_decode($res->getContent());

        $this->assertTrue($res->getStatusCode() === 200);
    }

    public function testNotFoundStatus()
    {
        $res = (new MakeController())->get(999999);

        $this->assertTrue($res->getStatusCode() === 404);
    }

    public function testSearchParamSort()
    {
        // create a couple makes
        $this->createMake([
            'value' => 'aaa',
        ]);
        $this->createMake([
            'value' => 'zzz',
        ]);

        $_GET = [
            'sort' => 'value:desc'
        ];

        $res = (new MakeController())->search();

        $content = json_decode($res->getContent(), true);

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content['data'][0]['value'] == 'zzz');

        $_GET = [
            'sort' => 'value:asc'
        ];

        $res = (new MakeController())->search();

        $content = json_decode($res->getContent(), true);

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content['data'][0]['value'] == 'aaa');
    }

    public function testSearchParamSlug()
    {
        // create a couple makes
        $this->createMake([
            'value' => 'aaa',
        ]);
        $this->createMake([
            'value' => 'zzz',
        ]);

        $_GET = [
            'slug' => 'zzz'
        ];

        $res = (new MakeController())->search();

        $content = json_decode($res->getContent(), true);

        $this->assertTrue($res->getStatusCode() === 200);
        $this->assertTrue($content['data'][0]['slug'] == 'zzz');
    }
}
