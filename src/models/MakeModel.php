<?php

namespace Rev\Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

use Rev\Utils\GenerateSlug;

/**
 * Class MakeModel
 *
 * @package Rev\Models
 */
class MakeModel extends Model
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
     * @var string
     */
    public $logo;

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('makes');

        $this->hasMany(
            'id',
            '\Rev\Models\AutoModel',
            'video_id',
            ['foreignKey' => true, 'alias' => 'Autos']
        );
    }

    /**
     * @return bool
     */
    public function beforeValidation(): bool
    {
        return $this->validation();
    }

    /**
     * @return void
     */
    public function beforeValidationOnCreate(): void
    {
        if ($this->value && !isset($this->slug)) {
            $this->slug = GenerateSlug::getSlug($this->value, new MakeModel());
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
            new PresenceOf(
                [
                'message' => "Value is required"
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
            'value' => (string)$this->value,
            'slug' => (string)$this->slug,
            'logo' => (string)$this->logo,
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
