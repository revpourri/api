<?php


use Phinx\Seed\AbstractSeed;

class MakesSeeder extends AbstractSeed
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'value' => 'Honda',
                'slug' => 'honda',
            ],
            [
                'id' => 2,
                'value' => 'Toyota',
                'slug' => 'toyota',
            ],
            [
                'id' => 3,
                'value' => 'Mazda',
                'slug' => 'mazda',
            ],
        ];

        $table = $this->table('makes');
        $table->insert($data)
            ->save();
    }
}
