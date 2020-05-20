<?php

namespace Rev\Models;

use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

use Rev\Utils\GenerateSlug;

/**
 * Class ProjectModel
 * @package Rev\Models
 */
class ProjectModel extends \Phalcon\Mvc\Model
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
    * @var string
    */
    public $slug;
    /**
    * @var int
    */
    public $uploader_id;
    /**
    * @var int
    */
    public $auto_id;
    /**
    * @var string
    */
    public $created_time;

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->setSource('projects');

        $this->belongsTo(
            'uploader_id',
            '\Rev\Models\UploaderModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Uploader']
        );

        $this->belongsTo(
            'auto_id',
            '\Rev\Models\AutoModel',
            'id',
            ['foreignKey' => true, 'alias' => 'Auto']
        );

        $this->hasMany(
            'id',
            '\Rev\Models\ProjectVideosModel',
            'project_id',
            ['foreignKey' => true, 'alias' => 'ProjectVideos']
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

        if ($this->name && !isset($this->slug)) {
            $this->slug = GenerateSlug::getSlug($this->value, new ProjectModel());
        }
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
            'slug' => (string)'/project/' . $this->_uris[$this->type] . $this->slug,
            'created_time' => date('c', strtotime($this->created_time)),
            'auto_id' => (string)$this->auto_id,
            'uploader_id' => (int)$this->uploader_id,
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

        if ($this->Auto) {
            $obj['auto'] = $this->Auto->build();
        }

        $videos = [];
        foreach ($this->ProjectVideos as $ProjectVideo) {
            $videos[] = $ProjectVideo->Video->build();
        }

        $obj['videos'] = $videos;

        return $obj;
    }

    /**
     * Generates a slug.  Removes all special characters, adds dashes for spaces
     *
     * @param $title
     * @return string
     */
    private function generateSlug($title): string
    {
        $slug = trim(strtolower($title));
        $slug = preg_replace("/[^a-z0-9_\s-]/", "", $slug);
        $slug = preg_replace("/[\s-]+/", " ", $slug);
        $slug = preg_replace("/[\s_]/", "-", $slug);

        $l = ProjectModel::find([
            'conditions' => 'slug = :slug:',
            'bind' => [
                'slug' => $slug
            ]
        ]);

        if (count($l) > 0) {
            $slug .= '-' . count($l);
        }

        return $slug;
    }
}
