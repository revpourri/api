<?php

namespace Rev\Controllers;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller as PhController;
use Phalcon\Mvc\Model\Query\Builder as QueryBuilder;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

use Rev\Utils\PaginationResponse;

/**
 * Class Controller
 *
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
     * @var array
     */
    public $files = [];

    /**
     *
     */
    public function onConstruct()
    {
        if ($this->request) {
            $this->input = json_decode($this->request->getRawBody(), true) ?? [];

            if ($this->request->hasFiles()) {
                $this->files = $this->request->getUploadedFiles();
            }
        }
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function setInput(array $data): void
    {
        $this->input = $data;
    }

    /**
     * @param array $files
     *
     * @return void
     */
    public function setFile($file): void
    {
        $this->files[] = $file;
    }

    /**
     * @param QueryBuilder $query
     * @param int          $limit
     * @param int          $page
     * @param array|null   $acceptedParams
     *
     * @return array
     */
    public function generatePaginatedData(
        QueryBuilder $query,
        int $limit,
        int $page = 1,
        array $acceptedParams = null
    ): array {
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
     *
     * @return Response
     */
    public function respondSuccess(array $data): Response
    {
        $this->response->setStatusCode(200);
        $this->response->setJsonContent($data);

        return $this->response;
    }

    /**
     * @return Response
     */
    public function respondNoContent(): Response
    {
        $this->response->setStatusCode(204);

        return $this->response;
    }

    /**
     * @param array $errors
     *
     * @return Response
     */
    public function respondBadRequest(array $errors): Response
    {
        $this->response->setStatusCode(400);

        $data = array_map(
            function ($a) {
                return [
                    'field' => $a->getField(),
                    'message' => $a->getMessage(),
                ];
            }, $errors
        );

        $this->response->setJsonContent(
            [
                'errors' => $data
            ]
        );

        return $this->response;
    }

    /**
     * @return Response
     */
    public function respondNotFound(): Response
    {
        $this->response->setStatusCode(404);

        return $this->response;
    }
}
