<?php

namespace Rev\Controllers;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Rev\Models\ModelModel as ModelModel;
use Rev\Models\AutoModel as AutoModel;
use Rev\Models\MakeModel as MakeModel;

class MakeController extends \Phalcon\Mvc\Controller
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
        $Makes = MakeModel::find([
            'order' => 'value ASC'
        ]);

        $paginator = new PaginatorModel(
            [
                'data'  => $Makes,
                'limit' => $_GET['limit'] ?: 100,
                'page'  => $_GET['page'] ?: 1,
            ]
        );

        $page = $paginator->getPaginate();

        $makes = [];
        foreach ($Makes as $Make) {
            $makes[] = $Make->build();
        }

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent([
            'links' => [
                'current' => '/makes?page=' . $page->current,
                'first' => '/makes?page=' . $page->first,
                'last' => '/makes?page=' . $page->last,
                'prev' => '/makes?page=' . $page->previous,
                'next' => '/makes?page=' . $page->next,
            ],
            "count" => count($makes),
            'data' => $makes,
        ]);

        return $this->response;
    }
}
