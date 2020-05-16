<?php

namespace Rev\Controllers;

/**
 * Class UploaderController
 * @package Rev\Controllers
 */
class UploaderController extends \Phalcon\Mvc\Controller
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
        $Uploader = \Rev\Models\UploaderModel::findFirstById($id);

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

        $Uploader = new \Rev\Models\UploaderModel();
        
        if (!$Uploader->save($input)) {
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
        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\UploaderModel.*')
            ->from('Rev\Models\UploaderModel');

        $res = $query->getQuery()->execute();
        $uploaders = [];
        foreach ($res as $l) {
            $Uploader = \Rev\Models\UploaderModel::findFirstById($l->id);
            $uploaders[] = $Uploader->build();
        }

        $this->response->setStatusCode($this->code);
        $this->response->setJsonContent($uploaders);

        return $this->response;
    }
}
