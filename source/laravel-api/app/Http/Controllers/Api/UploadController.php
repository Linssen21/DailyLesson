<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Common\Controller;
use App\Common\QueryParams;
use App\Http\Requests\Api\AttachmentDeleteRequest;
use App\Http\Requests\Api\AttachmentUpdateRequest;
use App\Http\Requests\Api\QueryParamsRequest;
use App\Http\Requests\Api\UploadRequest;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;

/**
 * [API] Upload Controller
 *
 * API Controller for Upload
 *
 * @ticket Feature/DL-4
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class UploadController extends Controller
{
    public function __construct(private UploadService $uploadServices)
    {
    }

    /**
     * Create upload request
     *
     * @ticket Feature/DL-4
     *
     * @param UploadRequest $request
     * @return JsonResponse
     */
    public function create(UploadRequest $request): JsonResponse
    {
        $attachment = $request->file('attachment');
        $author = (int) $request->attributes->get('admin_id');
        $result = $this->uploadServices->upload($attachment, $author);
        return $this->response($result);
    }

    /**
     * Get upload request
     *
     * @ticket Feature/DL-4
     *
     * @param QueryParamsRequest $request
     * @return JsonResponse
     */
    public function get(QueryParamsRequest $request): JsonResponse
    {
        $columns = $request->getColumns();
        $fields =  $request->input('fields', ['*']);
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        $order = $request->getOrder();
        $params = new QueryParams($columns, $fields, $page, $perPage, "=", $order);
        $result = $this->uploadServices->get($params);
        return $this->response($result);
    }

    /**
     * Update attachment request
     *
     * @ticket Feature/DL-4
     *
     * @param AttachmentUpdateRequest $request
     * @return JsonResponse
     */
    public function update(AttachmentUpdateRequest $request): JsonResponse
    {
        $result = $this->uploadServices->updateAttachment($request->getAttachment());
        return $this->response($result);
    }

    /**
     * Delete attachment request
     *
     * @ticket Feature/DL-4
     *
     * @param AttachmentDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(AttachmentDeleteRequest $request): JsonResponse
    {
        $id = (int) $request->input('id');
        $result = $this->uploadServices->delete($id, $request->input('meta'));
        return $this->response($result);
    }

}
