<?php

namespace Rev\Controllers;

use Rev\Models\MakeModel as MakeModel;

/**
 * Class MakeController
 * @package Rev\Controllers
 */
class MakeController extends Controller
{
    /**
     * @var string
     */
    public $prefix = '/makes';

    /**
     * @param int $id
     * @return \Phalcon\Http\Response
     */
    public function get(int $id): \Phalcon\Http\Response
    {
        $Make = MakeModel::findFirstById($id);

        if (!$Make) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($Make->build());
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function search(): \Phalcon\Http\Response
    {
        $limit = isset($_GET['limit']) ?: 100;
        $acceptedParams = [
            'sort' => $_GET['sort'] ?? null,
            'make' => $_GET['make'] ?? null,
        ];

        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\MakeModel.*')
            ->from('Rev\Models\MakeModel');

        if (isset($_GET['make'])) {
            $query = $query->where('Rev\Models\MakeModel.slug = :make:', [
                'make' => $_GET['make'],
            ]);
        }

        // Handle sorting
        if (isset($_GET['sort'])) {
            $sortBys = explode(',', $_GET['sort']);

            foreach ($sortBys as $sortBy) {
                $sortBy = explode(':', $sortBy);

                // Special cases
                if ($sortBy[0] == 'id') {
                    $sortBy[0] = 'Rev\Models\MakeModel.id';
                }

                $query = $query->orderBy($sortBy[0] . ' ' . $sortBy[1]);
            }
        }

        $data = $this->generatePaginatedData($query, $limit, $_GET['page'] ?? 1, $acceptedParams);

        return $this->respondSuccess($data);
    }
}
