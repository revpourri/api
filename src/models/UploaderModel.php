<?php

namespace Rev\Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class UploaderModel
 *
 * @package Rev\Models
 */
class UploaderModel extends Model
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
     * @var int
     */
    public $youtube_id;
    /**
     * @var string
     */
    public $avatar;
    /**
     * @var string
     */
    public $created_time;

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('uploaders');

        $this->hasMany(
            'id',
            '\Rev\Models\VideoModel',
            'uploader_id',
            ['foreignKey' => true, 'alias' => 'Videos']
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
    }

    /**
     * @return bool
     */
    public function beforeValidation(): bool
    {
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
                'name',
                'youtube_id',
            ],
            new PresenceOf(
                [
                    'message' => [
                        'name' => "Name is required",
                        'youtube_id' => "Youtube ID is required",
                    ],
                    'cancelOnFail' => true,
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
            'youtube_id' => (string)$this->youtube_id,
            'avatar' => (string)$this->avatar,
        ];
    }

    /**
     * Build
     *
     * @return array
     */
    public function build(): array
    {
        if (!$this->id) {
            return [];
        }

        return $this->baseObj();
    }
}
