<?php

namespace Rev\Controllers;

use Rev\Models\ModelModel as ModelModel;

/**
 * Class ModelController
 * @package Rev\Controllers
 */
class ModelController extends Controller
{
    /**
     * @var string
     */
    public $prefix = '/models';

    /**
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id): \Phalcon\Http\Response
    {
        $Model = ModelModel::findFirstById($id);

        if (!$Model) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($Model->build());
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function search(): \Phalcon\Http\Response
    {
        $limit = $_GET['limit'] ?: 100;
        $acceptedParams = [
            'sort' => $_GET['sort'],
            'make' => $_GET['make'],
            'model' => $_GET['model'],
        ];

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
        if ($_GET['model']) {
            $query = $query->where("Rev\Models\MakeModel.slug = :slug:", [
                'slug' => $_GET['model'],
            ]);
        }

        $data = $this->generatePaginatedData($query, $limit, $_GET['page'] ?? 1, $acceptedParams);

        return $this->respondSuccess($data);
    }
}
