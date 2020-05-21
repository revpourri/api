<?php

namespace Rev\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

use Rev\Models\UploaderModel;
use Rev\Utils\PaginationResponse;

/**
 * Class UploaderController
 * @package Rev\Controllers
 */
class UploaderController extends Controller
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
     * @return mixed
     */
    public function get(int $id)
    {
        $Uploader = UploaderModel::findFirstById($id);

        if (!$Uploader) {
            $this->response->setStatusCode(404);
            return $this->response;
        }

        $this->return = $Uploader->build();

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent($this->return);

        return $this->response;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function create(): \Phalcon\Http\Response
    {
        $input = $this->request->getJsonRawBody(true);

        $Uploader = (new UploaderModel())->assign(
            $input,
            [
                'name',
                'youtube_id',
            ]
        );

        if (!$Uploader->create()) {
            $msgs = $Uploader->getMessages();
            $this->return['message'] = $msgs[0]->getMessage();
            $this->response->setJsonContent($this->return);

            return $this->response;
        }

        $this->return = $Uploader->build();

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent($this->return);

        return $this->response;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function search(): \Phalcon\Http\Response
    {
        $prefix = '/uploaders';
        $limit = $_GET['limit'] ?: 10;
        $acceptedParams = [
            'sort' => $_GET['sort'],
            'name' => $_GET['name'],
        ];

        // Build
        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\UploaderModel.*')
            ->from('Rev\Models\UploaderModel');

        if ($_GET['name']) {
            $query = $query->where('Rev\Models\UploaderModel.name = :slug:', [
                'name' => $_GET['name'],
            ]);
        }

        // Handle sorting
        if ($_GET['sort']) {
            $sortBys = explode(',', $_GET['sort']);

            foreach ($sortBys as $sortBy) {
                $sortBy = explode(':', $sortBy);

                // Special cases
                if ($sortBy[0] == 'id') {
                    $sortBy[0] = 'Rev\Models\UploaderModel.id';
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
