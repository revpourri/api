<?php

namespace Rev\Models;

use Rev\Utils\GenerateSlug;

use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class VideoModel
 * @package Rev\Models
 */
class VideoModel extends \Phalcon\Mvc\Model
{
    /**
    * @var int
    */
    public $id;
    /**
    * @var string
    */
    public $title;
    /**
    * @var string
    */
    public $slug;
    /**
    * @var string
    */
    public $youtube_id;
    /**
    * @var int
    */
    public $uploader_id;
    /**
    * @var string
    */
    public $created_time;
    /**
    * @var string
    */
    public $published_date;
    /**
    * @var int
    */
    public $type;
    /**
    * @var bool
    */
    public $featured;
    /**
    * @var string
    */
    public $preview_video;

    /**
    * @var array
    */
    protected $_types = [
        '1' => 'review',
        '2' => 'project',
    ];

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('videos');

        $this->belongsTo(
            'uploader_id',
            '\Rev\Models\UploaderModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Uploader']
        );

        $this->hasMany(
            'id',
            '\Rev\Models\VideoAutosModel',
            'video_id',
            ['foreignKey' => true, 'alias' => 'VideoAutos']
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

        if ($this->title && !isset($this->slug)) {
            $this->slug = GenerateSlug::getSlug($this->title, new VideoModel());
        }

        if (!is_int($this->type)) {
            $this->type = array_search($this->type, $this->_types);
        }
    }

    /**
     * @return void
     */
    public function beforeValidation(): void
    {
        if ($this->featured && ($this->featured == 'true' || $this->featured == '1')) {
            $this->featured = 1;
        } else {
            $this->featured = 0;
        }
    }

    /**
     * @return bool
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add(
            'title',
            new PresenceOf([
                'message' => "Title is required"
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
            'title' => (string)$this->title,
            'slug' => (string)$this->slug,
            'created_time' => date('c', strtotime($this->created_time)),
            'published_date' => date('Y-m-d', strtotime($this->published_date)),
            'youtube_id' => (string)$this->youtube_id,
            'uploader_id' => (int)$this->uploader_id,
            'type' => (string)$this->_types[$this->type],
            'featured' => (bool)$this->featured,
            'preview_video' => (string)$this->preview_video,
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

        if ($this->Uploader) {
            $obj['uploader'] = $this->Uploader->build();
        }

        $obj['autos'] = [];
        foreach ($this->VideoAutos as $VideoAuto) {
            $auto = $VideoAuto->Auto->build();

            $obj['autos'][] = $auto;
        }

        return $obj;
    }
}
