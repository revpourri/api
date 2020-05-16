<?php

namespace Rev\Models;

use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class UploaderModel extends \Phalcon\Mvc\Model
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
     * @return bool
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add(
            'name',
            new PresenceOf([
                'message' => "Name is required"
            ])
        );

        $validator->add(
            'youtube_id',
            new PresenceOf([
                'message' => "Youtube ID is required"
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

        $obj = $this->baseObj();

        return $obj;
    }
}
