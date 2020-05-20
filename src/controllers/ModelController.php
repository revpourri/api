<?php

namespace Rev\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

use Rev\Utils\PaginationResponse;
use Rev\Models\ModelModel as ModelModel;

/**
 * Class ModelController
 * @package Rev\Controllers
 */
class ModelController extends Controller
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
        $prefix = '/models';
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

        // Pagination
        $page = (new Paginator(
            [
                'builder'  => $query,
                'limit' => $limit,
                'page'  => $_GET['page'] ?: 1,
            ]
        ))->paginate();

        $data = [];
        foreach ($page->getItems() as $l) {
            $data[] = $l->build();
        }

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent(PaginationResponse::getResponse($prefix, $page, $limit, $acceptedParams, $data));

        return $this->response;
    }
}
