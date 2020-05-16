<?php

namespace Rev\Controllers;

/**
 * Class ProjectController
 * @package Rev\Controllers
 */
class ProjectController extends \Phalcon\Mvc\Controller
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
        $Project = \Rev\Models\ProjectModel::findFirstById($id);

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

        $Project = new \Rev\Models\ProjectModel();
        
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
     * @param $id
     * @return \Phalcon\Http\Response
     */
    public function update($id): \Phalcon\Http\Response
    {
        $Project = \Rev\Models\ProjectModel::findFirstById($id);

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
     * @param $id
     * @return \Phalcon\Http\Response
     */
    public function delete($id): \Phalcon\Http\Response
    {
        $Project = \Rev\Models\ProjectModel::findFirstById($id);

        $Project->delete();

        $this->response->setStatusCode(204);

        return $this->response;
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function search(): \Phalcon\Http\Response
    {
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

        $res = $query->getQuery()->execute();
        $projects = [];
        foreach ($res as $l) {
            $Projects = \Rev\Models\ProjectModel::findFirstById($l->id);
            $projects[] = $Projects->build();
        }

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent([
            'links' => [],
            "count" => count($projects),
            'data' => $projects,
        ]);

        return $this->response;
    }
}
