<?php

namespace Modules\Pages\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Pages\Models\Page;
use Modules\Pages\Repositories\PagesRepository;
use Modules\Pages\Services\PagesService;

/**
 *
 */
class PagesController extends Controller
{
    /**
     * PagesController constructor.
     * @param \Modules\Pages\Repositories\PagesRepository $pagesRepository
     * @param \Modules\Pages\Services\PagesService $pagesService
     */
    public function __construct(
        protected PagesRepository $pagesRepository,
        protected PagesService $pagesService
    )
    {
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        $categoriesWithPages = $this->pagesRepository->getCategories();

        return new JsonResponse([
            'categories' => $categoriesWithPages,
        ]);
    }

    /**
     * @param string $alias
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPage(string $alias): JsonResponse
    {
        $page = $this->pagesRepository->getPage($alias);
        if (!$page instanceof Page) {
            return new JsonResponse([
                'message' => 'Страница не найдена',
            ], 404);
        }

        $page = $this->pagesService->formatPage($page);

        return new JsonResponse([
            'page' => $page
        ]);
    }
}