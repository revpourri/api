<?php

namespace Rev\Models;

use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

use Rev\Utils\GenerateSlug;

/**
 * Class ModelModel
 * @package Rev\Models
 */
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
     * @return void
     */
    public function beforeValidationOnCreate(): void
    {
        if ($this->value && !isset($this->slug)) {
            $this->slug = GenerateSlug::getSlug($this->value, new ModelModel());
        }
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
