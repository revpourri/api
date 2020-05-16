<?php

namespace Rev\Controllers;

use Rev\Models\ModelModel as ModelModel;
use Rev\Models\AutoModel as AutoModel;
use Rev\Models\MakeModel as MakeModel;

/**
 * Class ModelController
 * @package Rev\Controllers
 */
class ModelController extends \Phalcon\Mvc\Controller
{
    /**
     * @var int
     */
    protected $code = 200;
    /**
     * @var array
     */
    protected $return = [];

    /**
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id): \Phalcon\Http\Response
    {
        $Model = ModelModel::findFirstById($id);

        $this->return = $Model->build();

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent($this->return);

        return $this->response;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function search(): \Phalcon\Http\Response
    {
        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\ModelModel.*')
            ->from('Rev\Models\ModelModel')
            ->join('Rev\Models\AutoModel', 'Rev\Models\AutoModel.model_id = Rev\Models\ModelModel.id')
            ->join('Rev\Models\MakeModel', 'Rev\Models\AutoModel.make_id = Rev\Models\MakeModel.id')
            ->orderBy('Rev\Models\ModelModel.slug')
            ->groupBy('Rev\Models\ModelModel.id');

        if ($_GET['make']) {
            $query = $query->where("Rev\Models\MakeModel.slug = :slug:", [
                'slug' => $_GET['make'],
            ]);
        }

        $res = $query->getQuery()->execute();
        $models = [];
        foreach ($res as $l) {
            // If model already exists, skip.  Grouping above caused SQL 1055 error
            if (array_search($l->slug, array_column($models, 'slug'))) {
                continue;
            }

            $Model = ModelModel::findFirstById($l->id);
            $models[] = $Model->build();
        }

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent([
            'links' => [],
            "count" => count($models),
            'data' => $models,
        ]);

        return $this->response;
    }
}
