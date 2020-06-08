<?php

namespace Rev\Controllers;

use Phalcon\Http\Response;
use Rev\Models\AutoModel;
use Rev\Models\MakeModel;
use Rev\Models\ModelModel;
use Rev\Utils\PaginationSort;

/**
 * Class AutoController
 *
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
     *
     * @return Response
     */
    public function get(int $id): Response
    {
        if (!$Auto = AutoModel::findFirstById($id)) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($Auto->build());
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $Make = MakeModel::findFirstById($this->input['make_id'] ?? null);

        $Model = null;
        if ($this->input['model_id'] ?? null) {
            $Model = ModelModel::findFirstById($this->input['model_id']);
        } elseif ($this->input['model'] ?? null) {
            $Model = ModelModel::findFirstByValue($this->input['model']);
        }

        if (!$Model) {
            $Model = (new ModelModel())->assign(
                [
                    'value' => $this->input['model'] ?? null
                ]
            );

            if (!$Model->create()) {
                return $this->respondBadRequest($Model->getMessages());
            }
        }

        $Auto = (new AutoModel())->assign(
            [
                'year' => $this->input['year'],
                'model_id' => $Model->id,
                'make_id' => $Make->id,
            ]
        );
        if (!$Auto->create()) {
            return $this->respondBadRequest($Auto->getMessages());
        }

        return $this->respondSuccess($Auto->build());
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function update(int $id): Response
    {
        if (!$Auto = AutoModel::findFirstById($id)) {
            return $this->respondNotFound();
        }

        $Auto->assign(
            [
                'year' => $this->input['year']
            ]
        );

        if (!$Auto->update($this->input)) {
            return $this->respondBadRequest($Auto->getMessages());
        }

        return $this->respondSuccess($Auto->build());
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function delete(int $id): Response
    {
        $Auto = AutoModel::findFirstById($id);

        if (!$Auto) {
            return $this->respondNotFound();
        }

        $Auto->delete();

        return $this->respondNoContent();
    }

    /**
     * @return Response
     */
    public function search(): Response
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
            $query = $query->where(
                "Rev\Models\MakeModel.slug LIKE :make_slug: AND Rev\Models\ModelModel.slug LIKE :model_slug:", [
                    'make_slug' => $_GET['make'] . '%',
                    'model_slug' => $_GET['model'] . '%',
                ]
            );
        } elseif (isset($_GET['make'])) {
            $query = $query->where(
                'Rev\Models\MakeModel.slug LIKE :slug:', [
                    'slug' => $_GET['make'] . '%',
                ]
            );
        }

        $query = PaginationSort::sort($query, $_GET['sort'] ?? '', 'Rev\Models\AutoModel.id');

        $data = $this->generatePaginatedData($query, $limit, $_GET['page'] ?? 1, $acceptedParams);

        return $this->respondSuccess($data);
    }
}
