<?php

use Phinx\Migration\AbstractMigration;

class Setup extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('videos', ['signed' => false]);
        $table->addColumn('title', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('youtube_id', 'string')
            ->addColumn('uploader_id', 'integer', ['signed' => false])
            ->addColumn('type', 'integer', ['signed' => false])
            ->addColumn('created_time', 'datetime')
            ->addColumn('published_date', 'date')
            ->create();

        $table = $this->table('uploaders', ['signed' => false]);
        $table->addColumn('name', 'string')
            ->addColumn('youtube_id', 'string')
            ->addColumn('avatar', 'string', ['null' => true])
            ->addColumn('created_time', 'datetime')
            ->create();

        $table = $this->table('models', ['signed' => false]);
        $table->addColumn('value', 'string')
            ->addColumn('slug', 'string')
            ->create();

        $table = $this->table('makes', ['signed' => false]);
        $table->addColumn('value', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('logo', 'string')
            ->create();

        $table = $this->table('autos', ['signed' => false]);
        $table->addColumn('year', 'year')
            ->addColumn('make_id', 'integer', ['signed' => false])
            ->addColumn('model_id', 'integer', ['signed' => false])
            ->create();

        $table = $this->table('projects', ['signed' => false]);
        $table->addColumn('name', 'string')
            ->addColumn('uploader_id', 'integer', ['signed' => false])
            ->addColumn('auto_id', 'integer', ['signed' => false])
            ->addColumn('created_time', 'datetime')
            ->create();

        $table = $this->table('video_autos', ['signed' => false]);
        $table->addColumn('video_id', 'integer', ['signed' => false])
            ->addColumn('auto_id', 'integer', ['signed' => false])
            ->create();

        $table = $this->table('project_videos', ['signed' => false]);
        $table->addColumn('project_id', 'integer', ['signed' => false])
            ->addColumn('video_id', 'integer', ['signed' => false])
            ->create();

        $refTable = $this->table('videos');
        $refTable->addForeignKey('uploader_id', 'uploaders', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->save();

        $refTable = $this->table('autos');
        $refTable->addForeignKey('make_id', 'makes', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('model_id', 'models', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->save();

        $refTable = $this->table('video_autos');
        $refTable->addForeignKey('video_id', 'videos', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('auto_id', 'autos', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->save();

        $refTable = $this->table('project_videos');
        $refTable->addForeignKey('video_id', 'videos', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->addForeignKey('project_id', 'projects', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
            ->save();
    }
}
