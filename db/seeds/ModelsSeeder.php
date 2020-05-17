<?php


use Phinx\Seed\AbstractSeed;

class ModelsSeeder extends AbstractSeed
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'value' => 'S2000',
                'slug' => 's2000',
            ],
            [
                'id' => 2,
                'value' => 'Supra',
                'slug' => 'supra',
            ],
            [
                'id' => 3,
                'value' => 'RX-8',
                'slug' => 'rx-8',
            ],
        ];

        $table = $this->table('models');
        $table->insert($data)
            ->save();
    }
}
