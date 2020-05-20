<?php

namespace Rev\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

use Rev\Models\ModelModel as ModelModel;
use Rev\Models\AutoModel as AutoModel;
use Rev\Models\MakeModel as MakeModel;
use Rev\Utils\PaginationResponse;

/**
 * Class MakeController
 * @package Rev\Controllers
 */
class MakeController extends Controller
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
        $Make = MakeModel::findFirstById($id);

        if (!$Make) {
            $this->response->setStatusCode(404);
            return $this->response;
        }

        $this->return = $Make->build();

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent($this->return);

        return $this->response;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function search(): \Phalcon\Http\Response
    {
        $prefix = '/makes';
        $limit = $_GET['limit'] ?: 100;
        $acceptedParams = [
            'sort' => $_GET['sort'],
            'make' => $_GET['make'],
        ];

        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\MakeModel.*')
            ->from('Rev\Models\MakeModel');

        if ($_GET['make']) {
            $query = $query->where('Rev\Models\MakeModel.slug = :make:', [
                'make' => $_GET['make'],
            ]);
        }

        // Handle sorting
        if ($_GET['sort']) {
            $sortBys = explode(',', $_GET['sort']);

            foreach ($sortBys as $sortBy) {
                $sortBy = explode(':', $sortBy);

                // Special cases
                if ($sortBy[0] == 'id') {
                    $sortBy[0] = 'Rev\Models\MakeModel.id';
                }

                $query = $query->orderBy($sortBy[0] . ' ' . $sortBy[1]);
            }
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
