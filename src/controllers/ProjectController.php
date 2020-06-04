<?php

namespace Rev\Controllers;

use Phalcon\Http\Response;

use Rev\Models\ProjectModel;

/**
 * Class ProjectController
 *
 * @package Rev\Controllers
 */
class ProjectController extends Controller
{
    /**
     * @var string
     */
    public $prefix = '/projects';

    /**
     * @param  int $id
     * @return Response
     */
    public function get(int $id): Response
    {
        if (!$Project = ProjectModel::findFirstById($id)) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($Project->build());
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $Project = (new ProjectModel())->assign(
            $this->input,
            [
                'name',
                'uploader_id',
                'auto_id',
            ]
        );
        
        if (!$Project->create()) {
            return $this->respondBadRequest($Project->getMessages());
        }

        return $this->respondSuccess($Project->build());
    }

    /**
     * @param  int $id
     * @return Response
     */
    public function update(int $id): Response
    {
        if (!$Project = ProjectModel::findFirstById($id)) {
            return $this->respondNotFound();
        }

        $Project->assign(
            $this->input,
            [
                'name',
                'uploader_id',
                'auto_id',
            ]
        );

        if (!$Project->update()) {
            return $this->respondBadRequest($Project->getMessages());
        }

        return $this->respondSuccess($Project->build());
    }

    /**
     * @param  int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        if (!$Project = ProjectModel::findFirstById($id)) {
            return $this->respondNotFound();
        }

        $Project->delete();

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
        ];

        // Build
        $query = $this->modelsManager->createBuilder()
            ->columns('Rev\Models\ProjectModel.*')
            ->from('Rev\Models\ProjectModel')
            ->join('Rev\Models\AutoModel', 'Rev\Models\AutoModel.id = Rev\Models\ProjectModel.auto_id')
            ->join('Rev\Models\MakeModel', 'Rev\Models\AutoModel.make_id = Rev\Models\MakeModel.id')
            ->join('Rev\Models\ModelModel', 'Rev\Models\AutoModel.model_id = Rev\Models\ModelModel.id');

        if (isset($_GET['make']) && isset($_GET['model'])) {
            $query = $query->where(
                "Rev\Models\MakeModel.slug = :makeSlug: AND Rev\Models\ModelModel.slug = :modelSlug:", [
                'makeSlug' => $_GET['make'],
                'modelSlug' => $_GET['model'],
                ]
            );
        } elseif (isset($_GET['make'])) {
            $query = $query->where(
                "Rev\Models\MakeModel.slug = :slug:", [
                'slug' => $_GET['make'],
                ]
            );
        }

        if (isset($_GET['slug'])) {
            $query = $query->where(
                'Rev\Models\ProjectModel.slug = :slug:', [
                'slug' => $_GET['slug'],
                ]
            );
        }

        $query = $query->groupBy('Rev\Models\ProjectModel.id');

        // Handle sorting
        if (isset($_GET['sort'])) {
            $sortBys = explode(',', $_GET['sort']);

            foreach ($sortBys as $sortBy) {
                $sortBy = explode(':', $sortBy);

                // Special cases
                if ($sortBy[0] == 'id') {
                    $sortBy[0] = 'Rev\Models\ProjectModel.id';
                }

                $query = $query->orderBy($sortBy[0] . ' ' . $sortBy[1]);
            }
        }

        $data = $this->generatePaginatedData($query, $limit, $_GET['page'] ?? 1, $acceptedParams);

        return $this->respondSuccess($data);
    }
}
