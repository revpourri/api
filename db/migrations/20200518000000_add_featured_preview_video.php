<?php

use Phinx\Migration\AbstractMigration;

class AddFeaturedPreviewVideo extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('videos', ['signed' => false]);
        $table->addColumn('featured', 'boolean', ['after' => 'type'])
            ->addColumn('preview_video_upload_id', 'integer',
                [
                    'after' => 'featured',
                    'signed' => false,
                    'null' => true
                ]
            )->addForeignKey(
                'preview_video_upload_id', 'uploads', 'id',
                [
                    'delete'=> 'RESTRICT',
                    'update'=> 'RESTRICT'
                ]
            )
            ->save();
    }
}
