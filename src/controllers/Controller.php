<?php

namespace Rev\Controllers;

use Phalcon\Mvc\Controller as PhController;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

use Rev\Utils\PaginationResponse;

/**
 * Class Controller
 * @package Rev\Controllers
 */
class Controller extends PhController
{
    /**
     * @var string
     */
    protected $prefix;
    /**
     * @var array
     */
    public $input = [];

    /**
     *
     */
    public function onConstruct()
    {
        if ($this->request && $this->request->getRawBody()) {
            $this->input = $this->request->getJsonRawBody(true);
        }
    }

    /**
     * @param array $data
     */
    public function setInput(array $data): void
    {
        $this->input = $data;
    }

    /**
     * @param \Phalcon\Mvc\Model\Query\Builder $query
     * @param int $limit
     * @param int $page
     * @param array|null $acceptedParams
     * @return array
     */
    public function generatePaginatedData(
        \Phalcon\Mvc\Model\Query\Builder $query,
        int $limit,
        int $page = 1,
        array $acceptedParams = null
    ): array
    {
        $paginator = (new Paginator(
            [
                'builder'  => $query,
                'limit' => $limit,
                'page'  => $page,
            ]
        ))->paginate();

        $data = [];
        foreach ($paginator->getItems() as $l) {
            $data[] = $l->build();
        }

        return PaginationResponse::getResponse($this->prefix, $paginator, $limit, $acceptedParams, $data);
    }

    /**
     * @param array $data
     * @return \Phalcon\Http\Response
     */
    public function respondSuccess(array $data): \Phalcon\Http\Response
    {
        $this->response->setStatusCode(200);
        $this->response->setJsonContent($data);

        return $this->response;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function respondNoContent(): \Phalcon\Http\Response
    {
        $this->response->setStatusCode(204);

        return $this->response;
    }

    /**
     * @param array $errors
     * @return \Phalcon\Http\Response
     */
    public function respondBadRequest(array $errors): \Phalcon\Http\Response
    {
        $this->response->setStatusCode(400);

        $this->response->setJsonContent([
            'errors' => $errors
        ]);

        return $this->response;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function respondNotFound(): \Phalcon\Http\Response
    {
        $this->response->setStatusCode(404);

        return $this->response;
    }
}
