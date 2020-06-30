<?php

namespace Rev\Controllers;

use Phalcon\Http\Response;

use Rev\Models\ProjectVideosModel;
use Rev\Models\VideoModel;
use Rev\Models\VideoAutosModel;
use Rev\Utils\PaginationSort;

/**
 * Class VideoController
 *
 * @package Rev\Controllers
 */
class VideoController extends Controller
{
    /**
     * @var string
     */
    public $prefix = '/videos';

    /**
     * @param int $id
     *
     * @return Response
     */
    public function get(int $id): Response
    {
        if (!$Video = VideoModel::findFirstById($id)) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($Video->build());
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $Video = (new VideoModel())->assign(
            $this->input, [
                'title',
                'youtube_id',
                'uploader_id',
                'published_date',
                'type',
                'featured',
                'preview_video_upload_id',
            ]
        );

        if (!$Video->create()) {
            return $this->respondBadRequest($Video->getMessages());
        }

        if (isset($this->input['autos'])) {
            foreach ($this->input['autos'] as $auto) {
                $VideoAuto = (new VideoAutosModel())->assign(
                    [
                        'video_id' => $Video->id,
                        'auto_id' => $auto['id'],
                    ]
                );
                $VideoAuto->save();
            }
        }

        // If project_id is passed, add to project
        if (isset($this->input['project_id'])) {
            $lastSortOrder = ProjectVideosModel::find(
                [
                    'conditions' => 'project_id = :project_id:',
                    'bind' => [
                        'project_id' => $this->input['project_id']
                    ],
                    'order_by' => 'sort_order DESC',
                    'limit' => 1,
                ]
            );

            $sortOrder = (count($lastSortOrder) > 0) ? $lastSortOrder->sort_order + 1 : 1;

            (new ProjectVideosModel())->save(
                [
                    'video_id' => $Video->id,
                    'project_id' => $this->input['project_id'],
                    'sort_order' => $sortOrder,
                ]
            );
        }

        return $this->respondSuccess($Video->build());
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function update(int $id): Response
    {
        if (!$Video = VideoModel::findFirstById($id)) {
            return $this->respondNotFound();
        }

        if (isset($this->input['preview_video_upload_id']) && empty($this->input['preview_video_upload_id'])) {
            unset($this->input['preview_video_upload_id']);
        }

        $Video->assign(
            $this->input, [
                'title',
                'youtube_id',
                'uploader_id',
                'published_date',
                'type',
                'featured',
                'preview_video_upload_id',
            ]
        );

        if (!$Video->update()) {
            return $this->respondBadRequest($Video->getMessages());
        }

        if (isset($this->input['autos'])) {
            // if current auto is not in body, remove
            foreach ($Video->VideoAutos as $VideoAuto) {
                if (array_search((integer)$VideoAuto->auto_id, array_column($this->input['autos'], 'id')) === false) {
                    $VideoAuto->delete();
                }
            }

            foreach ($this->input['autos'] as $auto) {
                if (!$VideoAuto = VideoAutosModel::findFirst([
                    'conditions' => 'video_id = :video_id: AND auto_id = :auto_id:',
                    'bind' => [
                        'video_id' => $Video->id,
                        'auto_id' => $auto['id'],
                    ],
                ])) {
                    $VideoAuto = (new VideoAutosModel())->assign(
                        [
                            'video_id' => $Video->id,
                            'auto_id' => $auto['id'],
                        ]
                    );
                    $VideoAuto->save();
                }
            }
        }

        return $this->respondSuccess($Video->build());
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function delete(int $id): Response
    {
        if (!$Video = VideoModel::findFirstById($id)) {
            return $this->respondNotFound();
        }

        $Video->delete();

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
            'slug' => $_GET['slug'] ?? null,
            'make' => $_GET['make'] ?? null,
            'model' => $_GET['model'] ?? null,
            'type' => $_GET['type'] ?? null,
            'featured' => $_GET['featured'] ?? null,
        ];

        // Build
        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\VideoModel.*')
            ->from('Rev\Models\VideoModel')
            ->leftJoin('Rev\Models\VideoAutosModel', 'Rev\Models\VideoModel.id = Rev\Models\VideoAutosModel.video_id')
            ->leftJoin('Rev\Models\AutoModel', 'Rev\Models\AutoModel.id = Rev\Models\VideoAutosModel.auto_id')
            ->leftJoin('Rev\Models\MakeModel', 'Rev\Models\AutoModel.make_id = Rev\Models\MakeModel.id')
            ->leftJoin('Rev\Models\ModelModel', 'Rev\Models\AutoModel.model_id = Rev\Models\ModelModel.id');

        if (isset($_GET['make']) && isset($_GET['model'])) {
            $query = $query->where(
                "Rev\Models\MakeModel.slug = :makeSlug: AND Rev\Models\ModelModel.slug = :modelSlug:", [
                    'makeSlug' => $_GET['make'],
                    'modelSlug' => $_GET['model'],
                ]
            );
        } elseif (isset($_GET['make'])) {
            $query = $query->where(
                'Rev\Models\MakeModel.slug = :slug:', [
                    'slug' => $_GET['make'],
                ]
            );
        }

        if (isset($_GET['type'])) {
            $query = $query->andWhere(
                'Rev\Models\VideoModel.type = :type:', [
                    'type' => $_GET['type'],
                ]
            );
        }

        if (isset($_GET['slug'])) {
            $query = $query->andWhere(
                'Rev\Models\VideoModel.slug = :slug:', [
                    'slug' => $_GET['slug'],
                ]
            );
        }

        if (isset($_GET['uploader_id'])) {
            $query = $query->andWhere(
                'Rev\Models\VideoModel.uploader_id = :uploader_id:', [
                    'uploader_id' => $_GET['uploader_id'],
                ]
            );
        }

        if (isset($_GET['featured'])) {
            $query = $query->andWhere(
                'Rev\Models\VideoModel.featured = :featured:', [
                    'featured' => (in_array($_GET['featured'], ['true', '1'])) ? 1 : 0,
                ]
            );
        }

        $query = $query->groupBy('Rev\Models\VideoModel.id');

        $query = PaginationSort::sort($query, $_GET['sort'] ?? '', 'Rev\Models\VideoModel.id');

        $data = $this->generatePaginatedData($query, $limit, $_GET['page'] ?? 1, $acceptedParams);

        return $this->respondSuccess($data);
    }
}
