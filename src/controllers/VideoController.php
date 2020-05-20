<?php

namespace Rev\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

use Rev\Utils\PaginationResponse;
use Rev\Models\VideoModel;

/**
 * Class VideoController
 * @package Rev\Controllers
 */
class VideoController extends Controller
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
        $Video = VideoModel::findFirstById($id);

        if (!$Video) {
            $this->response->setStatusCode(404);
            return $this->response;
        }

        $this->return = $Video->build();

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

        $Video = new VideoModel();

        if (!$Video->save($input)) {
            $msgs = $Video->getMessages();
            $this->return['message'] = $msgs[0]->getMessage();
            $this->response->setJsonContent($this->return);

            return $this->response;
        }

        foreach ($input['autos'] as $auto) {
            $VideoAuto = new \Rev\Models\VideoAutosModel();
            $VideoAuto->save([
                'video_id' => $Video->id,
                'auto_id' => $auto['id'],
            ]);
        }

        // If project_id is passed, add to project
        if ($input['project_id']) {
            $lastSortOrder = \Rev\Models\ProjectVideosModel::find([
                'conditions' => 'project_id = :project_id:',
                'bind' => [
                    'project_id' => $input['project_id']
                ],
                'order_by' => 'sort_order DESC',
                'limit' => 1,
            ]);

            $sortOrder = ($lastSortOrder) ? $lastSortOrder->sort_order + 1 : 1;

            $ProjectVideo = new \Rev\Models\ProjectVideosModel();
            $ProjectVideo->save([
                'video_id' => $Video->id,
                'project_id' => $input['project_id'],
                'sort_order' => $sortOrder,
            ]);
        }

        $this->return = $Video->build();

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
        $Video = VideoModel::findFirstById($id);

        $input = $this->request->getJsonRawBody(true);
        
        if (!$Video->save($input)) {
            $msgs = $Video->getMessages();
            $this->return['message'] = $msgs[0]->getMessage();
            $this->response->setJsonContent($this->return);

            return $this->response;
        }

        $this->return = $Video->build();

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
        $Video = VideoModel::findFirstById($id);

        $Video->delete();

        $this->response->setStatusCode(204);

        return $this->response;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function search(): \Phalcon\Http\Response
    {
        $prefix = '/videos';
        $limit = $_GET['limit'] ?: 10;
        $acceptedParams = [
            'sort' => $_GET['sort'],
            'slug' => $_GET['slug'],
            'make' => $_GET['make'],
            'model' => $_GET['model'],
            'type' => $_GET['type'],
            'featured' => $_GET['featured'],
        ];

        // Build
        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\VideoModel.*')
            ->from('Rev\Models\VideoModel')
            ->join('Rev\Models\VideoAutosModel', 'Rev\Models\VideoModel.id = Rev\Models\VideoAutosModel.video_id')
            ->join('Rev\Models\AutoModel', 'Rev\Models\AutoModel.id = Rev\Models\VideoAutosModel.auto_id')
            ->join('Rev\Models\MakeModel', 'Rev\Models\AutoModel.make_id = Rev\Models\MakeModel.id')
            ->join('Rev\Models\ModelModel', 'Rev\Models\AutoModel.model_id = Rev\Models\ModelModel.id');

        if ($_GET['make'] && $_GET['model']) {
            $query = $query->where("Rev\Models\MakeModel.slug = :makeslug: AND Rev\Models\ModelModel.slug = :modelslug:", [
                'makeslug' => $_GET['make'],
                'modelslug' => $_GET['model'],
            ]);
        } elseif ($_GET['make']) {
            $query = $query->where('Rev\Models\MakeModel.slug = :slug:', [
                'slug' => $_GET['make'],
            ]);
        }

        if ($_GET['type']) {
            $query = $query->andWhere('Rev\Models\VideoModel.type = :type:', [
                'type' => $_GET['type'],
            ]);
        }

        if ($_GET['slug']) {
            $query = $query->where('Rev\Models\VideoModel.slug = :slug:', [
                'slug' => $_GET['slug'],
            ]);
        }

        if ($_GET['featured']) {
            $query = $query->where('Rev\Models\VideoModel.featured = :featured:', [
                'featured' => (in_array($_GET['featured'], ['true', '1'])) ? 1 : 0,
            ]);
        }

        $query = $query->groupBy('Rev\Models\VideoModel.id');

        // Handle sorting
        if ($_GET['sort']) {
            $sortBys = explode(',', $_GET['sort']);

            foreach ($sortBys as $sortBy) {
                $sortBy = explode(':', $sortBy);

                // Special cases
                if ($sortBy[0] == 'id') {
                    $sortBy[0] = 'Rev\Models\VideoModel.id';
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
