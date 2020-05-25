<?php

namespace Rev\Controllers;

use Rev\Models\UploaderModel;

/**
 * Class UploaderController
 * @package Rev\Controllers
 */
class UploaderController extends Controller
{
    /**
     * @var string
     */
    public $prefix = '/uploaders';

    /**
     * @param int $id
     * @return mixed
     */
    public function get(int $id)
    {
        $Uploader = UploaderModel::findFirstById($id);

        if (!$Uploader) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($Uploader->build());
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function create(): \Phalcon\Http\Response
    {
        $Uploader = (new UploaderModel())->assign(
            $this->input,
            [
                'name',
                'youtube_id',
            ]
        );

        if (!$Uploader->create()) {
            return $this->respondBadRequest($Uploader->getMessages());
        }

        return $this->respondSuccess($Uploader->build());
    }

    /**
     * @return \Phalcon\Http\Response
     */
    public function search(): \Phalcon\Http\Response
    {
        $limit = $_GET['limit'] ?? 10;
        $acceptedParams = [
            'sort' => $_GET['sort'] ?? null,
            'name' => $_GET['name'] ?? null,
        ];

        // Build
        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\UploaderModel.*')
            ->from('Rev\Models\UploaderModel');

        if (isset($_GET['name'])) {
            $query = $query->where('Rev\Models\UploaderModel.name = :name:', [
                'name' => $_GET['name'],
            ]);
        }

        // Handle sorting
        if (isset($_GET['sort'])) {
            $sortBys = explode(',', $_GET['sort']);

            foreach ($sortBys as $sortBy) {
                $sortBy = explode(':', $sortBy);

                // Special cases
                if ($sortBy[0] == 'id') {
                    $sortBy[0] = 'Rev\Models\UploaderModel.id';
                }

                $query = $query->orderBy($sortBy[0] . ' ' . $sortBy[1]);
            }
        }

        $data = $this->generatePaginatedData($query, $limit, $_GET['page'] ?? 1, $acceptedParams);

        return $this->respondSuccess($data);
    }
}
