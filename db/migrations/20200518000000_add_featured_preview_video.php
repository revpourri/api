<?php

use Phinx\Migration\AbstractMigration;

class AddFeaturedPreviewVideo extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('videos', ['signed' => false]);
        $table->addColumn('featured', 'boolean', ['after' => 'type'])
            ->addColumn('preview_video', 'string', ['after' => 'featured', 'null' => true])
            ->save();
    }
}
