<?php

namespace Rev\Controllers;

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
     * @var string
     */
    public $prefix = '/autos';

    /**
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id): \Phalcon\Http\Response
    {
        $Auto = AutoModel::findFirstById($id);

        if (!$Auto) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($Auto->build());
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function create(): \Phalcon\Http\Response
    {
        $Make = MakeModel::findFirstById($this->input['make_id']);

        $Model = null;
        if ($this->input['model_id']) {
            $Model = ModelModel::findFirstById($this->input['model_id']);
        } elseif ($this->input['model']) {
            $Model = ModelModel::findFirstByValue($this->input['model']);
        }

        if (!$Model) {
            $Model = (new ModelModel())->assign([
                'value' => $this->input['model']
            ]);

            if (!$Model->create()) {
                return $this->respondBadRequest($Model->getMessages());
            }
        }

        $Auto = (new AutoModel())->assign([
            'year' => $this->input['year'],
            'model_id' => $Model->id,
            'make_id' => $Make->id,
        ]);
        if (!$Auto->create()) {
            return $this->respondBadRequest($Auto->getMessages());
        }

        return $this->respondSuccess($Auto->build());
    }

    /**
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function update(int $id): \Phalcon\Http\Response
    {
        $Auto = AutoModel::findFirstById($id);

        if (!$Auto) {
            return $this->respondNotFound();
        }
        
        if (!$Auto->save($this->input)) {
            return $this->respondBadRequest($Auto->getMessages());
        }

        return $this->respondSuccess($Auto->build());
    }

    /**
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function delete(int $id): \Phalcon\Http\Response
    {
        $Auto = AutoModel::findFirstById($id);

        if (!$Auto) {
            return $this->respondNotFound();
        }

        $Auto->delete();

        return $this->respondNoContent();
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function search(): \Phalcon\Http\Response
    {
        $limit = $_GET['limit'] ?? 10;
        $acceptedParams = [
            'sort' => $_GET['sort'] ?? null,
            'make' => $_GET['make'] ?? null,
            'model' => $_GET['model'] ?? null,
        ];

        // Build
        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\AutoModel.*')
            ->from('Rev\Models\AutoModel')
            ->join('Rev\Models\MakeModel', 'Rev\Models\AutoModel.make_id = Rev\Models\MakeModel.id')
            ->join('Rev\Models\ModelModel', 'Rev\Models\AutoModel.model_id = Rev\Models\ModelModel.id');

        if (isset($_GET['make']) && isset($_GET['model'])) {
            $query = $query->where("Rev\Models\MakeModel.slug LIKE :make_slug: AND Rev\Models\ModelModel.slug LIKE :model_slug:", [
                'make_slug' => $_GET['make'] . '%',
                'model_slug' => $_GET['model'] . '%',
            ]);
        } elseif (isset($_GET['make'])) {
            $query = $query->where('Rev\Models\MakeModel.slug LIKE :slug:', [
                'slug' => $_GET['make'] . '%',
            ]);
        }

        // Handle sorting
        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            $sortBys = explode(',', $_GET['sort']);

            foreach ($sortBys as $sortBy) {
                $sortBy = explode(':', $sortBy);

                // Special cases
                if ($sortBy[0] == 'id') {
                    $sortBy[0] = 'Rev\Models\AutoModel.id';
                }

                $query = $query->orderBy($sortBy[0] . ' ' . $sortBy[1]);
            }
        }

        $data = $this->generatePaginatedData($query, $limit, $_GET['page'] ?? 1, $acceptedParams);

        return $this->respondSuccess($data);
    }
}
