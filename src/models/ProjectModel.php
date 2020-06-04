<?php

namespace Rev\Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

use Rev\Utils\GenerateSlug;

/**
 * Class ProjectModel
 *
 * @package  Rev\Models
 * @property AutoModel Auto
 * @property UploaderModel Uploader
 */
class ProjectModel extends Model
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $slug;
    /**
     * @var int
     */
    public $uploader_id;
    /**
     * @var int
     */
    public $auto_id;
    /**
     * @var string
     */
    public $created_time;

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('projects');

        $this->belongsTo(
            'uploader_id',
            '\Rev\Models\UploaderModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Uploader']
        );

        $this->belongsTo(
            'auto_id',
            '\Rev\Models\AutoModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Auto']
        );

        $this->hasMany(
            'id',
            '\Rev\Models\ProjectVideosModel',
            'project_id',
            ['foreignKey' => true, 'alias' => 'ProjectVideos']
        );
    }

    /**
     * @return void
     */
    public function beforeValidationOnCreate(): void
    {
        if (!isset($this->created_time)) {
            $this->created_time = date('Y-m-d H:i:s', time());
        }

        if ($this->name && !isset($this->slug)) {
            $this->slug = GenerateSlug::getSlug($this->name, new ProjectModel());
        }
    }

    /**
     * @return bool
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add(
            'name',
            new PresenceOf(
                [
                'message' => "Name is required"
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * @return array
     */
    public function baseObj(): array
    {
        return [
            'id' => (int)$this->id,
            'name' => (string)$this->name,
            'slug' => (string)$this->slug,
            'created_time' => date('c', strtotime($this->created_time)),
            'auto_id' => (string)$this->auto_id,
            'uploader_id' => (int)$this->uploader_id,
        ];
    }

    /**
     * @return array
     */
    public function build(): array
    {
        if (!$this->id) {
            return [];
        }

        $obj = $this->baseObj();

        $obj['uploader'] = $this->Uploader ? $this->Uploader->build() : null;

        $obj['auto'] = $this->Auto ? $this->Auto->build() : null;

        $obj['videos'] = [];
        foreach ($this->ProjectVideos as $ProjectVideo) {
            $obj['videos'][] = $ProjectVideo->Video->build();
        }

        unset($obj['uploader_id']);

        return $obj;
    }
}
