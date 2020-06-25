<?php

use Phinx\Migration\AbstractMigration;

class AddTags extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('tags', ['signed' => false]);
        $table->addColumn('value', 'string', ['null' => false])
            ->save();

        $table = $this->table('video_auto_tags', ['signed' => false]);
        $table->addColumn('video_auto_id', 'integer',
                [
                    'signed' => false,
                    'null' => true
                ]
            )
            ->addColumn('tag_id', 'integer',
                [
                    'signed' => false,
                    'null' => true
                ]
            )
            ->addForeignKey(
                'video_auto_id', 'video_autos', 'id',
                [
                    'delete'=> 'RESTRICT',
                    'update'=> 'RESTRICT'
                ]
            )
            ->addForeignKey(
                'tag_id', 'tags', 'id',
                [
                    'delete'=> 'RESTRICT',
                    'update'=> 'RESTRICT'
                ]
            )
            ->save();
    }
}
