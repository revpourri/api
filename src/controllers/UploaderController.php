<?php

namespace Rev\Controllers;

use Phalcon\Http\Response;

use Rev\Models\UploaderModel;
use Rev\Utils\PaginationSort;

/**
 * Class UploaderController
 *
 * @package Rev\Controllers
 */
class UploaderController extends Controller
{
    /**
     * @var string
     */
    public $prefix = '/uploaders';

    /**
     * @param  int $id
     * @return mixed
     */
    public function get(int $id)
    {
        if (!$Uploader = UploaderModel::findFirstById($id)) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($Uploader->build());
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $Uploader = (new UploaderModel())->assign(
            $this->input,
            [
                'name',
                'youtube_id',
                'avatar_upload_id',
            ]
        );

        if (!$Uploader->create()) {
            return $this->respondBadRequest($Uploader->getMessages());
        }

        return $this->respondSuccess($Uploader->build());
    }

    /**
     * @param  int $id
     *
     * @return Response
     */
    public function update(int $id): Response
    {
        if (!$Uploader = UploaderModel::findFirstById($id)) {
            return $this->respondNotFound();
        }

        $Uploader->assign(
            $this->input,
            [
                'name',
                'youtube_id',
                'avatar_upload_id',
            ]
        );

        if (!$Uploader->update()) {
            return $this->respondBadRequest($Uploader->getMessages());
        }

        return $this->respondSuccess($Uploader->build());
    }

    /**
     * @return Response
     */
    public function search(): Response
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
            $query = $query->where(
                'Rev\Models\UploaderModel.name = :name:', [
                'name' => $_GET['name'],
                ]
            );
        }

        $query = PaginationSort::sort($query, $_GET['sort'] ?? '', 'Rev\Models\UploaderModel.id');

        $data = $this->generatePaginatedData($query, $limit, $_GET['page'] ?? 1, $acceptedParams);

        return $this->respondSuccess($data);
    }
}
