<?php

namespace Rev\Models;

use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Date as DateValidator;

/**
 * Class AutoModel
 * @package Rev\Models
 */
class AutoModel extends \Phalcon\Mvc\Model
{
    /**
    * @var int
    */
    public $id;
    /**
    * @var string
    */
    public $year;
    /**
    * @var int
    */
    public $make_id;
    /**
    * @var int
    */
    public $model_id;

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('autos');

        $this->belongsTo(
            'make_id',
            '\Rev\Models\MakeModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Make']
        );

        $this->belongsTo(
            'model_id',
            '\Rev\Models\ModelModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Model']
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
     * @return bool
     */
    public function validation(): bool
    {
        $validator = new Validation();

        $validator->add(
            [
                'model_id',
                'make_id',
                'year',
            ],
            new PresenceOf(
                [
                    'message' => [
                        'model_id' => "Model ID is required",
                        'make_id' => "Make ID is required",
                        'year' => "Year is required",
                    ],
                    'cancelOnFail' => true,
                ]
            )
        );

        $validator->add(
            [
                "year",
            ],
            new DateValidator(
                [
                    "format" => [
                        "year" => "Y",
                    ],
                    "message" => [
                        "year" => "Year is invalid",
                    ],
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
            'year' => (string)$this->year,
            'model_id' => (int)$this->model_id,
            'make_id' => (int)$this->make_id,
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

        $obj['make'] = $this->Make->build();
        $obj['model'] = $this->Model->build();

        unset($obj['make_id']);
        unset($obj['model_id']);

        return $obj;
    }
}
