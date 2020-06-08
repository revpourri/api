<?php

namespace Rev\Controllers;

use Phalcon\Http\Response;

use Rev\Models\MakeModel as MakeModel;
use Rev\Utils\PaginationSort;

/**
 * Class MakeController
 *
 * @package Rev\Controllers
 */
class MakeController extends Controller
{
    /**
     * @var string
     */
    public $prefix = '/makes';

    /**
     * @param  int $id
     * @return Response
     */
    public function get(int $id): Response
    {
        if (!$Make = MakeModel::findFirstById($id)) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($Make->build());
    }

    /**
     * @return Response
     */
    public function search(): Response
    {
        $limit = $_GET['limit'] ?? 100;
        $acceptedParams = [
            'sort' => $_GET['sort'] ?? null,
            'slug' => $_GET['slug'] ?? null,
        ];

        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\MakeModel.*')
            ->from('Rev\Models\MakeModel');

        if (isset($_GET['slug'])) {
            $query = $query->where(
                'Rev\Models\MakeModel.slug = :slug:', [
                'slug' => $_GET['slug'],
                ]
            );
        }

        $query = PaginationSort::sort($query, $_GET['sort'] ?? '', 'Rev\Models\MakeModel.id');

        $data = $this->generatePaginatedData($query, $limit, $_GET['page'] ?? 1, $acceptedParams);

        return $this->respondSuccess($data);
    }
}
