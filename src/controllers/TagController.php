<?php

namespace Rev\Controllers;

use Phalcon\Http\Response;
use Rev\Models\AutoModel;
use Rev\Models\MakeModel;
use Rev\Models\ModelModel;
use Rev\Models\TagModel;
use Rev\Models\VideoAutoTagsModel;
use Rev\Utils\PaginationSort;

/**
 * Class TagController
 *
 * @package Rev\Controllers
 */
class TagController extends Controller
{
    /**
     * @var string
     */
    public $prefix = '/tags';

    /**
     * @param int $videoAutoId
     * @return Response
     */
    public function addToVideoAuto(int $videoAutoId): Response
    {
        // if it already exists, just return ok
        if ($Tag = TagModel::findFirstByValue($this->input['value'] ?? null)) {
            if ($VideoAutoTag = VideoAutoTagsModel::findFirst([
                'conditions' => 'video_auto_id = :video_auto_id: AND tag_id = :tag_id:',
                'bind' => [
                    'video_auto_id' => $videoAutoId,
                    'tag_id' => $Tag->id,
                ]
            ])) {
                return $this->respondSuccess($Tag->build());
            }
        }
        else { // Tag doesn't exist, create it
            $Tag = (new TagModel())->assign([
                'value' => $this->input['value']
            ]);
            if (!$Tag->create()) {
                return $this->respondBadRequest($Tag->getMessages());
            }
        }

        $VideoAutoTag = (new VideoAutoTagsModel)->assign([
            'video_auto_id' => $videoAutoId,
            'tag_id' => $Tag->id,
        ]);
        $VideoAutoTag->create();

        return $this->respondSuccess($Tag->build());
    }

    /**
     * @param int $videoAutoId
     * @param int $tagId
     * @return Response
     */
    public function deleteFromVideoAuto(int $videoAutoId, int $tagId): Response
    {
        $VideoAutoTag = VideoAutoTagsModel::findFirst([
            'conditions' => 'video_auto_id = :video_auto_id: AND tag_id = :tag_id:',
            'bind' => [
                'video_auto_id' => $videoAutoId,
                'tag_id' => $tagId,
            ]
        ]);

        if (!$VideoAutoTag) {
            return $this->respondNotFound();
        }

        $VideoAutoTag->delete();

        return $this->respondNoContent();
    }

    /**
     * @param int $videoAutoId
     * @return Response
     */
    public function deleteAllFromVideoAuto(int $videoAutoId): Response
    {
        $VideoAutoTags = VideoAutoTagsModel::find([
            'conditions' => 'video_auto_id = :video_auto_id:',
            'bind' => [
                'video_auto_id' => $videoAutoId,
            ]
        ]);

        foreach ($VideoAutoTags as $VideoAutoTag) {
            $VideoAutoTag->delete();
        }

        return $this->respondNoContent();
    }
}
