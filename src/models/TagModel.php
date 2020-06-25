<?php

namespace Rev\Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Date as DateValidator;

/**
 * Class TagModel
 *
 * @package  Rev\Models
 */
class TagModel extends Model
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
     * Initialize
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('tags');
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
                'value',
            ],
            new PresenceOf(
                [
                    'message' => [
                        'value' => "Value is required",
                    ],
                    'cancelOnFail' => true,
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * BaseObj
     *
     * @return array
     */
    public function baseObj(): array
    {
        return [
            'id' => (int)$this->id,
            'value' => (string)$this->value,
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
