<?php

declare(strict_types=1);

namespace App\Services;

use App\Common\QueryParams;
use App\Common\Service;
use App\Domains\Post\Common\Attachment;
use App\Domains\Post\Common\Image;
use App\Domains\Post\Common\ImageDetail;
use App\Domains\Post\Common\MediaDetail;
use App\Domains\Post\DTO\AttachmentUpdateDto;
use App\Domains\Post\Service\AttachmentService;
use App\Feature\Upload\Contracts\FileUpload;
use App\Feature\Upload\ImageUpload;
use App\Feature\Upload\SlideUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UploadService extends Service
{
    public const PATH = 'uploads';

    public function __construct(
        private AttachmentService $attachmentService
    ) {
    }

    /**
     * Set the instance for File Upload based on the mime type
     *
     * @param string $mimeType
     * @return FileUpload
     */
    private function determineFileUpload(string $mimeType): FileUpload
    {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
            case 'image/webp':
            case 'image/bmp':
            case 'image/svg+xml':
                return new ImageUpload();
            default:
                return new SlideUpload();
        }
    }

    /**
    * Upload file
    *
    * @ticket Feature/DL-4
    *
    * @param UploadedFile $file
    * @return array
    */
    public function upload(UploadedFile $file, int $author): array
    {
        try {

            DB::beginTransaction();
            $mime = $file->getMimeType();
            $fileUpload = $this->determineFileUpload($mime);
            $path = $fileUpload->upload($file, self::PATH);

            if (empty($path)) {
                return $this->uploadResponse(message: 'Upload failed', status: false);
            }

            $url = $fileUpload->downloadUrl($path);
            $title = basename($path);
            $mediaDetail = new MediaDetail($title);
            $size = $file->getSize();
            $attachment = new Attachment($author, $path, $url, $mime, $size, $mediaDetail);

            if ($fileUpload instanceof ImageUpload) {
                $imageDetail = new ImageDetail($title);
                $dimension = $fileUpload->getImageDimension($path);
                $image = new Image($author, $dimension, $imageDetail);
                $this->attachmentService->createImage($attachment, $image);
            } else {
                $this->attachmentService->create($attachment);
            }

            DB::commit();
            return $this->uploadResponse($path, $url, 'Upload success', true);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Upload] An error occurred during upload',
                ['message: ' => $th->getMessage()]
            );

            return $this->uploadResponse(message: 'Upload failed', status: false);
        }
    }

    /**
     * Fetch uploaded file by parameters
     *
     * @param QueryParams $params
     * @return array
     */
    public function get(QueryParams $params): array
    {
        try {
            $result = $this->attachmentService->get($params);
            if ($result->isEmpty()) {
                return $this->dataReponse($result, 'No attachment found', false);
            }
            return $this->dataReponse($result, 'Fetching attachment success', true);
        } catch (\Throwable $th) {
            Log::channel('applog')->error(
                '[Fetching attachment] An error occurred during fetch',
                ['message: ' => $th->getMessage()]
            );
            return $this->dataReponse(new Collection(), 'Fetching attachment failed', false);
        }
    }

    /**
     * Update an attachment details
     *
     * @param AttachmentUpdateDto $params
     * @return array
     */
    public function updateAttachment(AttachmentUpdateDto $params): array
    {
        try {
            DB::beginTransaction();
            $isUpdateSuccess = $this->attachmentService->updateAttachment($params);
            if (!$isUpdateSuccess) {
                DB::rollBack();
                Log::channel('applog')->error(
                    '[Update attachment] An error occurred during update',
                    ['message: ' => "Update not successful"]
                );
                return $this->messageReponse('Update attachment failed', false);
            }
            DB::commit();
            return $this->messageReponse('Update attachment success', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Update attachment] An error occurred during update',
                ['message: ' => $th->getMessage()]
            );
            return $this->messageReponse('Update attachment failed', false);
        }
    }

    /**
     * Delete post and post meta
     *
     * @param integer $id
     * @return array
     */
    public function delete(int $id, mixed $metaKey): array
    {
        try {
            DB::beginTransaction();
            $isDeleted = $this->attachmentService->softDelete($id, $metaKey);
            if (!$isDeleted) {
                DB::rollBack();
                return $this->messageReponse('Delete attachment failed', false);
            }
            DB::commit();
            return $this->messageReponse('Delete attachment success', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Deleting attachment] An error occurred during delete',
                ['message: ' => $th->getMessage()]
            );
            return $this->messageReponse('Delete attachment failed', false);
        }
    }

    /**
     * Helper function for upload response
     *
     * @param string $path
     * @param string $url
     * @param string $message
     * @param boolean $status
     * @return array
     */
    private function uploadResponse(string $path = "", string $url = "", string $message, bool $status): array
    {
        return [
            'path' => $path,
            'url' => $url,
            'message' => $message,
            'status' => $status ? config('constants.STATUS_SUCCESS') : config('constants.STATUS_FAILED')
        ];
    }
}
