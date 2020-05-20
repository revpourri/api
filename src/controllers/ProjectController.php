<?php

namespace Rev\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

use Rev\Utils\PaginationResponse;
use Rev\Models\ProjectModel;

/**
 * Class ProjectController
 * @package Rev\Controllers
 */
class ProjectController extends Controller
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
        $Project = ProjectModel::findFirstById($id);

        if (!$Project) {
            $this->response->setStatusCode(404);
            return $this->response;
        }

        $this->return = $Project->build();

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

        $Project = new ProjectModel();
        
        if (!$Project->save($input)) {
            $msgs = $Project->getMessages();
            $this->return['message'] = $msgs[0]->getMessage();
            $this->response->setJsonContent($this->return);

            return $this->response;
        }

        $this->return = $Project->build();

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent($this->return);

        return $this->response;
    }

    /**
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function update(int $id): \Phalcon\Http\Response
    {
        $Project = ProjectModel::findFirstById($id);

        $input = $this->request->getJsonRawBody(true);
        
        if (!$Project->save($input)) {
            $msgs = $Project->getMessages();
            $this->return['message'] = $msgs[0]->getMessage();
            $this->response->setJsonContent($this->return);

            return $this->response;
        }

        $this->return = $Project->build();

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent($this->return);

        return $this->response;
    }

    /**
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function delete(int $id): \Phalcon\Http\Response
    {
        $Project = ProjectModel::findFirstById($id);

        $Project->delete();

        $this->response->setStatusCode(204);

        return $this->response;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function search(): \Phalcon\Http\Response
    {
        $prefix = '/projects';
        $limit = $_GET['limit'] ?: 10;
        $acceptedParams = [
            'sort' => $_GET['sort'],
            'slug' => $_GET['slug'],
            'make' => $_GET['make'],
            'model' => $_GET['model'],
        ];

        // Build
        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\ProjectModel.*')
            ->from('Rev\Models\ProjectModel')
            ->join('Rev\Models\AutoModel', 'Rev\Models\AutoModel.id = Rev\Models\ProjectModel.auto_id')
            ->join('Rev\Models\MakeModel', 'Rev\Models\AutoModel.make_id = Rev\Models\MakeModel.id')
            ->join('Rev\Models\ModelModel', 'Rev\Models\AutoModel.model_id = Rev\Models\ModelModel.id');

        if ($_GET['make'] && $_GET['model']) {
            $query = $query->where("Rev\Models\MakeModel.slug = :makeslug: AND Rev\Models\ModelModel.slug = :modelslug:", [
                'makeslug' => $_GET['make'],
                'modelslug' => $_GET['model'],
            ]);
        } elseif ($_GET['make']) {
            $query = $query->where("Rev\Models\MakeModel.slug = :slug:", [
                'slug' => $_GET['make'],
            ]);
        }

        if ($_GET['slug']) {
            $query = $query->where('Rev\Models\ProjectModel.slug = :slug:', [
                'slug' => $_GET['slug'],
            ]);
        }

        $query = $query->groupBy('Rev\Models\ProjectModel.id');

        // Handle sorting
        if ($_GET['sort']) {
            $sortBys = explode(',', $_GET['sort']);

            foreach ($sortBys as $sortBy) {
                $sortBy = explode(':', $sortBy);

                // Special cases
                if ($sortBy[0] == 'id') {
                    $sortBy[0] = 'Rev\Models\ProjectModel.id';
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
