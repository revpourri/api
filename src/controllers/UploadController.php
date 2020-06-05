<?php

namespace Rev\Controllers;

use Phalcon\Http\Response;

use Rev\Models\UploadModel;

/**
 * Class UploaderController
 *
 * @package Rev\Controllers
 */
class UploadController extends Controller
{
    /**
     * @var string
     */
    public $prefix = '/upload';

    /**
     * @return Response
     */
    public function create(): Response
    {
        $file = $this->files[0] ?? null;

        if (!$file) {
            return $this->respondBadRequest([]);
        }

        $filename = strtolower($file->getName());

        $file->moveTo(
            $this->config->app->root . $this->config->app->uploads . $filename
        );

        $Upload = (new UploadModel())->assign(
            [
                'filename' => $filename,
            ]
        );

        if (!$Upload->create()) {
            return $this->respondBadRequest($Upload->getMessages());
        }

        return $this->respondSuccess($Upload->build());
    }
}
