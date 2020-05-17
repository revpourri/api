<?php


use Phinx\Seed\AbstractSeed;

class AutosSeeder extends AbstractSeed
{
    public function getDependencies()
    {
        return [
            'MakesSeeder',
            'ModelsSeeder',
        ];
    }

    public function run()
    {
        $data = [
            [
                'id' => 1,
                'year' => 2001,
                'make_id' => 1,
                'model_id' => 1,
            ],
            [
                'id' => 2,
                'year' => 1996,
                'make_id' => 2,
                'model_id' => 2,
            ],
            [
                'id' => 3,
                'year' => 2006,
                'make_id' => 3,
                'model_id' => 3,
            ],
        ];

        $table = $this->table('autos');
        $table->insert($data)
            ->save();
    }
}
