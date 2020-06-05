<?php

namespace Rev\Models;

use Phalcon\Mvc\Model;

/**
 * Class UploaderModel
 *
 * @package Rev\Models
 */
class UploadModel extends Model
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $filename;
    /**
     * @var string
     */
    public $created_time;

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('uploads');
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
     * @return array
     */
    public function baseObj(): array
    {
        return [
            'id' => (int)$this->id,
            'filename' => (string)$this->filename,
            'created_time' => date('c', strtotime($this->created_time)),
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
