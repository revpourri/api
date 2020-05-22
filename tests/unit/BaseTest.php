<?php

class BaseTest extends \Codeception\Test\Unit
{
    public function createUploader()
    {
        $arr = (new Rev\Models\UploaderModel())->assign([
            'name' => 'Uploader',
            'youtube_id' => 'youtube_id',
        ]);
        $arr->create();
        return $arr->toArray();
    }

    public function createMake()
    {
        $arr = (new Rev\Models\MakeModel())->assign([
            'value' => 'Make',
        ]);
        $arr->create();
        return $arr->toArray();
    }

    public function createModel()
    {
        $arr = (new Rev\Models\ModelModel())->assign([
            'value' => 'Model',
        ]);
        $arr->create();
        return $arr->toArray();
    }
}