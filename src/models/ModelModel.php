<?php

namespace Rev\Models;

use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class ModelModel extends \Phalcon\Mvc\Model
{
    /**
    * @var int
    */
    public $id;
    /**
    * @var string
    */
    public $value;
    /**
    * @var string
    */
    public $slug;

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('models');

        $this->hasMany(
            'id',
            '\Rev\Models\AutosModel',
            'video_id',
            ['foreignKey' => true, 'alias' => 'Autos']
        );
    }

    /**
     * @return bool
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add(
            'value',
            new PresenceOf([
                'message' => "Model is required"
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
            'value' => (string)$this->value,
            'slug' => (string)$this->slug,
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

        return $this->baseObj();
    }
}
