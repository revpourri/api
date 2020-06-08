<?php

namespace Rev\Controllers;

use Phalcon\Http\Response;

use Rev\Models\ModelModel as ModelModel;
use Rev\Utils\PaginationSort;

/**
 * Class ModelController
 *
 * @package Rev\Controllers
 */
class ModelController extends Controller
{
    /**
     * @var string
     */
    public $prefix = '/models';

    /**
     * @param  int $id
     * @return Response
     */
    public function get(int $id): Response
    {
        $Model = ModelModel::findFirstById($id);

        if (!$Model) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($Model->build());
    }

    /**
     * @return Response
     */
    public function search(): Response
    {
        $limit = isset($_GET['limit']) ?: 100;
        $acceptedParams = [
            'sort' => $_GET['sort'] ?? null,
            'make' => $_GET['make'] ?? null,
            'model' => $_GET['model'] ?? null,
        ];

        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\ModelModel.*')
            ->from('Rev\Models\ModelModel')
            ->join('Rev\Models\AutoModel', 'Rev\Models\AutoModel.model_id = Rev\Models\ModelModel.id')
            ->join('Rev\Models\MakeModel', 'Rev\Models\AutoModel.make_id = Rev\Models\MakeModel.id')
            ->orderBy('Rev\Models\ModelModel.slug')
            ->groupBy('Rev\Models\ModelModel.id');

        if (isset($_GET['make'])) {
            $query = $query->where(
                "Rev\Models\MakeModel.slug = :slug:", [
                'slug' => $_GET['make'],
                ]
            );
        }
        if (isset($_GET['model'])) {
            $query = $query->where(
                "Rev\Models\MakeModel.slug = :slug:", [
                'slug' => $_GET['model'],
                ]
            );
        }

        $query = PaginationSort::sort($query, $_GET['sort'] ?? '', 'Rev\Models\ModelModel.id');

        $data = $this->generatePaginatedData($query, $limit, $_GET['page'] ?? 1, $acceptedParams);

        return $this->respondSuccess($data);
    }
}
