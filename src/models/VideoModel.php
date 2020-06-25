<?php

namespace Rev\Models;

use Rev\Utils\GenerateSlug;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Date as DateValidator;

/**
 * Class VideoModel
 *
 * @package  Rev\Models
 * @property UploaderModel Uploader
 * @property VideoAutosModel VideoAutos
 * @property UploadModel PreviewVideo
 */
class VideoModel extends Model
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
     * @var integer
     */
    public $preview_video_upload_id;

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

        $this->hasOne(
            'preview_video_upload_id',
            '\Rev\Models\UploadModel',
            'id',
            ['foreignKey' => true, 'alias' => 'PreviewVideo']
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
     * @return bool
     */
    public function beforeValidation(): bool
    {
        if ($this->featured && ($this->featured == 'true' || $this->featured == '1')) {
            $this->featured = 1;
        } else {
            $this->featured = 0;
        }

        return $this->validation();
    }

    /**
     * @return bool
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add(
            [
                'title',
                'uploader_id',
                'type',
                'published_date',
            ],
            new PresenceOf(
                [
                    'message' => [
                        'title' => "Title is required",
                        'uploader_id' => "Uploader ID is required",
                        'type' => "Type is required",
                        'published_date' => "Published Date is required",
                    ],
                    'cancelOnFail' => true,
                ]
            )
        );

        $validator->add(
            [
                "published_date",
            ],
            new DateValidator(
                [
                    "format" => [
                        "published_date" => "Y-m-d",
                    ],
                    "message" => [
                        "published_date" => "Published Date is invalid",
                    ],
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
            'title' => (string)$this->title,
            'slug' => (string)$this->slug,
            'created_time' => date('c', strtotime($this->created_time)),
            'published_date' => date('Y-m-d', strtotime($this->published_date)),
            'youtube_id' => (string)$this->youtube_id,
            'uploader_id' => (int)$this->uploader_id,
            'type' => (string)$this->_types[$this->type],
            'featured' => (bool)$this->featured,
            'preview_video' => $this->PreviewVideo ? (string)$this->PreviewVideo->filename : null,
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

        $obj['autos'] = [];
        foreach ($this->VideoAutos ?? [] as $VideoAuto) {
            $auto = $VideoAuto->Auto->build();

            $auto['tags'] = [];
            foreach ($VideoAuto->VideoAutoTags as $VideoAutoTags) {
                $auto['tags'][] = $VideoAutoTags->Tag->build();
            }
            $obj['autos'][] = $auto;
        }

        unset($obj['uploader_id']);

        return $obj;
    }
}
