<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Common\Column;
use App\Common\Controller;
use App\Common\QueryParams;
use App\Domains\Post\Slides\Slides;
use App\Http\Requests\Api\SlideCreateRequest;
use App\Http\Requests\Api\SlideQueryParamsRequest;
use App\Http\Requests\Api\SlideUpdateRequest;
use App\Services\SlideService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * [API] Slide Controller
 *
 * API Controller for Slide
 *
 * @ticket Feature/DL-4
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class SlideController extends Controller
{
    public function __construct(private SlideService $slideService)
    {
    }

    /**
     * Create slide request
     *
     * @ticket Feature/DL-4
     *
     * @param SlideCreateRequest $request
     * @return JsonResponse
     */
    public function create(SlideCreateRequest $request): JsonResponse
    {
        $result = $this->slideService->create($request->toCreateSlide());
        return $this->response($result);
    }

    /**
     * Get slide create request
     *
     * @ticket Feature/DL-4
     *
     * @param SlideQueryParamsRequest $request
     * @return JsonResponse
     */
    public function get(SlideQueryParamsRequest $request): JsonResponse
    {
        $columns = $request->getColumns();
        $columns->add(new Column('type', '=', Slides::TYPE));

        $params = new QueryParams(
            $columns,
            $request->getFields(),
            $request->input('page', 1),
            $request->input('per_page', 10),
            "=",
            $request->getOrder()
        );
        $result = $this->slideService->get($params);
        return $this->response($result);
    }

    /**
     * Update slide request
     *
     * @ticket Feature/DL-4
     *
     * @param SlideUpdateRequest $request
     * @return JsonResponse
     */
    public function update(SlideUpdateRequest $request): JsonResponse
    {
        $id = (int) $request->input('id');
        $result = $this->slideService->update($id, $request->toPostDto(), $request->getTemplate());
        return $this->response($result);
    }

    /**
     * Delete slide request
     *
     * @ticket Feature/DL-4
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = (int) $request->route('id');
        $result = $this->slideService->delete($id);
        return $this->response($result);
    }
}
