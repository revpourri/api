<?php

namespace Rev\Models;

use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class ProjectVideosModel extends \Phalcon\Mvc\Model
{
    /**
    * @var int
    */
    public $id;
    /**
    * @var int
    */
    public $video_id;
    /**
    * @var int
    */
    public $project_id;

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('project_videos');

        $this->belongsTo(
            'video_id',
            '\Rev\Models\VideoModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Video']
        );

        $this->belongsTo(
            'project_id',
            '\Rev\Models\ProjectModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Project']
        );
    }

    /**
     * @return bool
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add(
            'project_id',
            new PresenceOf([
                'message' => "Project ID is required"
            ])
        );

        $validator->add(
            'video_id',
            new PresenceOf([
                'message' => "Video ID is required"
            ])
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
            'project_id' => (int)$this->project_id,
            'video_id' => (int)$this->video_id,
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

        return $obj;
    }
}
