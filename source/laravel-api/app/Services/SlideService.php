<?php

declare(strict_types=1);

namespace App\Services;

use App\Common\QueryParams;
use App\Common\Service;
use App\Domains\Post\Contracts\PostMetaRepository;
use App\Domains\Post\DTO\PostDto;
use App\Domains\Post\DTO\SlideCreateDto;
use App\Domains\Post\Service\SlideService as DomainSlideService;
use App\Domains\Post\Slides\Template;
use App\Feature\Upload\SlideUpload;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Slide Service
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class SlideService extends Service
{
    public function __construct(
        private SlideUpload $slideUpload,
        private DomainSlideService $slideService,
        private PostMetaRepository $postMetaRepository
    ) {
    }

    /**
     * Create slide post and slide_template meta
     *
     * @ticket Feature/DL-4
     *
     * @param SlideCreateDto $slideCreateDto
     * @return array
     */
    public function create(SlideCreateDto $slideCreateDto): array
    {
        try {
            DB::beginTransaction();
            $this->slideService->create($slideCreateDto);
            DB::commit();
            return $this->messageReponse('Create slide success', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Create Slide] An error occurred during insert',
                ['message: ' => $th->getMessage()]
            );
            return $this->messageReponse('Create slide failed', false);
        }
    }

    /**
     * Get slide post and slide_template meta
     *
     * @ticket Feature/DL-4
     *
     * @param QueryParams $params
     * @return array
     */
    public function get(QueryParams $params): array
    {
        try {
            $result = $this->slideService->get($params);
            if ($result->isEmpty()) {
                return $this->dataReponse($result, 'No slides found', false);
            }
            return $this->dataReponse($result, 'Fetching slides success', true);
        } catch (\Throwable $th) {
            Log::channel('applog')->error(
                '[Fetching slides] An error occurred during fetch',
                ['message: ' => $th->getMessage()]
            );
            return $this->dataReponse(new Collection(), 'Fetching slides failed', false);
        }
    }

    /**
     * Get slide post and slide_template meta
     *
     * @ticket Feature/DL-4
     *
     * @param QueryParams $params
     * @param integer $id
     * @return array
     */
    public function getWithPagination(QueryParams $params): array
    {
        try {
            $result = $this->slideService->getWithPagination($params);
            if ($result->isEmpty()) {
                return $this->dataReponse($result, 'No slides found', false);
            }
            return $this->dataReponse($result, 'Fetching slides success', true);

        } catch (\Throwable $th) {
            Log::channel('applog')->error(
                '[Fetching slides] An error occurred during fetch',
                ['message: ' => $th->getMessage()]
            );
            return $this->dataReponse(new Collection(), 'Fetching slides failed', false);
        }
    }

    /**
     * Update slide post and slide_template meta
     *
     * @ticket Feature/DL-4
     *
     * @param integer $id
     * @param PostDto $postDto
     * @param Template $template
     * @return array
     */
    public function update(int $id, PostDto $postDto, Template $template): array
    {
        try {
            DB::beginTransaction();
            $this->slideService->update($id, $postDto, $template);
            DB::commit();
            return $this->messageReponse('Update slide success', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                "[Update Slide] An error occurred updating {$id}",
                ['message: ' => $th->getMessage()]
            );
            return $this->messageReponse('Update slide failed', false);
        }
    }

    /**
     * Delte slide post and slide_template meta
     *
     * @param integer $id
     * @return array
     */
    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();
            $isDeleted = $this->slideService->delete($id);

            if (!$isDeleted) {
                DB::rollBack();
                return $this->messageReponse("Delete failed {$id}", false);
            }
            DB::commit();
            return $this->messageReponse("Delete success {$id}", true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                "[Delete Slide] An error occurred deleting {$id}",
                ['message: ' => $th->getMessage()]
            );
            return $this->messageReponse("Delete failed {$id}", false);
        }
    }


    /**
     * Get the download url of slide
     *
     * @ticket Feature/DL-4
     *
     * @param string $path
     * @return array
     */
    public function download(string $path): array
    {
        try {
            $downloadUrl = $this->slideUpload->downloadUrl($path);
            if (empty($downloadUrl)) {
                return $this->urlReponse('', 'Slide download url not found', false);
            }

            return $this->urlReponse('', 'Slide download url found', true);

        } catch (\Throwable $th) {
            Log::channel('applog')->error(
                '[Slide Download Url] An error occurred getting the Slide download url',
                ['message: ' => $th->getMessage()]
            );

            return $this->urlReponse('', 'Slide download url not found', false);
        }
    }
}
