<?php

namespace Rev\Controllers;

use Phalcon\Mvc\Controller;
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
     * @return mixed
     */
    public function create(): \Phalcon\Http\Response
    {
        $input = $this->request->getJsonRawBody(true);

        $Make = MakeModel::findFirstById($input['make_id']);
        $Model = ModelModel::findFirstById($input['model_id']);

        if (!$Model) {
            $Model = new ModelModel();
            $Model->save([
                'value' => $input['model'],
                'slug' => strtolower($input['model']),
            ]);
        }

        $Auto = new AutoModel();
        if (!$Auto->save([
            'year' => $input['year'],
            'model_id' => $Model->id,
            'make_id' => $Make->id,
        ])) {
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
     * @param $id
     * @return mixed
     */
    public function update($id): \Phalcon\Http\Response
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
     * @param $id
     * @return mixed
     */
    public function delete($id): \Phalcon\Http\Response
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
     * @return mixed
     */
    public function search(): \Phalcon\Http\Response
    {
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

        $res = $query->getQuery()->execute();
        $autos = [];
        foreach ($res as $l) {
            $autos[] = (\Rev\Models\AutoModel::findFirstById($l->id))->build();
        }

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent($autos);

        return $this->response;
    }
}
