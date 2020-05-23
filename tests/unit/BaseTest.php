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

    public function createProject()
    {
        $auto = $this->createAuto();
        $uploader = $this->createUploader();
        $arr = (new Rev\Models\ProjectModel())->assign([
            'name' => 'name',
            'auto_id' => $auto['id'],
            'uploader_id' => $uploader['id'],
        ]);
        $arr->create();

        return $arr->toArray();
    }

    public function createAuto()
    {
        $make = $this->createMake();
        $model = $this->createModel();
        $arr = (new Rev\Models\AutoModel())->assign([
            'year' => '2000',
            'make_id' => $make['id'],
            'model_id' => $model['id'],
        ]);
        $arr->create();
        return $arr->toArray();
    }

    public function createVideo()
    {
        $uploader = $this->createUploader();
        $arr = (new Rev\Models\VideoModel())->assign([
            'title' => 'title',
            'youtube_id' => 'youtube_id',
            'uploader_id' => $uploader['id'],
            'published_date' => '2000-01-01',
            'type' => 'review',
        ]);
        $arr->create();
        return $arr->toArray();
    }
}