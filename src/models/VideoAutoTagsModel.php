<?php

namespace Rev\Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class VideoAutoTagsModel
 *
 * @package  Rev\Models
 */
class VideoAutoTagsModel extends Model
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $video_auto_id;
    /**
     * @var int
     */
    public $tag_id;

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('video_auto_tags');

        $this->belongsTo(
            'video_auto_id',
            '\Rev\Models\VideoAutosModel',
            'id',
            ['foreignKey' => true, 'alias' => 'VideoAutos']
        );

        $this->belongsTo(
            'tag_id',
            '\Rev\Models\TagModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Tag']
        );
    }

    /**
     * @return bool
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add(
            'video_auto_id',
            new PresenceOf(
                [
                'message' => "Video Auto ID is required"
                ]
            )
        );

        $validator->add(
            'tag_id',
            new PresenceOf(
                [
                'message' => "Tag ID is required"
                ]
            )
        );

        return $this->validate($validator);
    }
}
