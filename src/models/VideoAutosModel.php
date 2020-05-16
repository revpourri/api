<?php

namespace Rev\Models;

use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class VideoAutosModel
 * @package Rev\Models
 */
class VideoAutosModel extends \Phalcon\Mvc\Model
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
    public $auto_id;

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('video_autos');

        $this->belongsTo(
            'video_id',
            '\Rev\Models\VideoModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Video']
        );

        $this->belongsTo(
            'auto_id',
            '\Rev\Models\AutoModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Auto']
        );

        $this->hasMany(
            'id',
            '\Rev\Models\VideoAutoTagsModel',
            'video_auto_id',
            ['foreignKey' => true, 'alias' => 'VideoAutoTags']
        );
    }

    /**
     * @return bool
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add(
            'video_id',
            new PresenceOf([
                'message' => "Video ID is required"
            ])
        );

        $validator->add(
            'auto_id',
            new PresenceOf([
                'message' => "Auto ID is required"
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
            'video_id' => (int)$this->video_id,
            'auto_id' => (int)$this->auto_id,
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
