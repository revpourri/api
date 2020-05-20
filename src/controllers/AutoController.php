<?php

namespace Rev\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

use Rev\Utils\PaginationResponse;
use Rev\Models\AutoModel;
use Rev\Models\MakeModel;
use Rev\Models\ModelModel;

/**
 * Class AutoController
 * @package Rev\Controllers
 */
class AutoController extends Controller
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
        $Auto = AutoModel::findFirstById($id);

        if (!$Auto) {
            $this->response->setStatusCode(404);
            return $this->response;
        }

        $this->return = $Auto->build();

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

        $Make = MakeModel::findFirstById($input['make_id']);

        $Model = null;
        if ($input['model_id']) {
            $Model = ModelModel::findFirstById($input['model_id']);
        } else if ($input['model']) {
            $Model = ModelModel::findFirstByValue($input['model']);
        }

        if (!$Model) {
            $Model = (new ModelModel())->assign([
                'value' => $input['model']
            ]);

            if (!$Model->create()) {
                $msgs = $Model->getMessages();
                $this->return['message'] = $msgs[0]->getMessage();
                $this->response->setJsonContent($this->return);

                return $this->response;
            }
        }

        $Auto = (new AutoModel())->assign([
            'year' => $input['year'],
            'model_id' => $Model->id,
            'make_id' => $Make->id,
        ]);
        if (!$Auto->create()) {
            $msgs = $Auto->getMessages();
            $this->return['message'] = $msgs[0]->getMessage();
            $this->response->setJsonContent($this->return);

            return $this->response;
        }

        $this->return = $Auto->build();

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
        $Auto = AutoModel::findFirstById($id);
        
        if (!$Auto->save($this->request->getJsonRawBody(true))) {
            $messages = $Auto->getMessages();
            $this->return['message'] = $messages[0]->getMessage();
            $this->response->setJsonContent($this->return);

            return $this->response;
        }

        $this->return = $Auto->build();

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
        $Auto = AutoModel::findFirstById($id);

        if (!$Auto) {
            $this->response->setStatusCode(404);
            return $this->response;
        }

        $Auto->delete();

        $this->response->setStatusCode(204);

        return $this->response;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function search(): \Phalcon\Http\Response
    {
        $prefix = '/autos';
        $limit = $_GET['limit'] ?: 10;
        $acceptedParams = [
            'sort' => $_GET['sort'],
            'make' => $_GET['make'],
            'model' => $_GET['model'],
        ];

        // Build
        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\AutoModel.*')
            ->from('Rev\Models\AutoModel')
            ->join('Rev\Models\MakeModel', 'Rev\Models\AutoModel.make_id = Rev\Models\MakeModel.id')
            ->join('Rev\Models\ModelModel', 'Rev\Models\AutoModel.model_id = Rev\Models\ModelModel.id');

        if ($_GET['make'] && $_GET['model']) {
            $query = $query->where("Rev\Models\MakeModel.slug LIKE :make_slug: AND Rev\Models\ModelModel.slug LIKE :model_slug:", [
                'make_slug' => $_GET['make'] . '%',
                'model_slug' => $_GET['model'] . '%',
            ]);
        } elseif ($_GET['make']) {
            $query = $query->where('Rev\Models\MakeModel.slug LIKE :slug:', [
                'slug' => $_GET['make'] . '%',
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
