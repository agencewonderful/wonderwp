<?php

namespace WonderWp\Framework\CPT;

use WonderWp\Framework\AbstractPlugin\AbstractPluginFrontendController;
use WonderWp\Framework\HttpFoundation\Request;
use WonderWp\Framework\Repository\PostRepository;
use WonderWp\Framework\Service\ServiceInterface;

class CustomPostTypePublicController extends AbstractPluginFrontendController
{
    protected $customPostType;

    /**
     * @return mixed
     */
    public function getCustomPostType()
    {
        return $this->customPostType;
    }

    /**
     * @param mixed $customPostType
     *
     * @return static
     */
    public function setCustomPostType($customPostType)
    {
        $this->customPostType = $customPostType;

        return $this;
    }

    public function defaultAction(array $attributes = [])
    {
        return $this->listAction($attributes);
    }

    public function listAction(array $attributes = [])
    {
        $request = Request::getInstance();
        $page    = (int)$request->get('pageno', 1);
        $perPage = $this->manager->getConfig('per_page', 10);

        $filterService = $this->manager->getService('filters');
        $criterias     = !empty($filterService) ? $filterService->prepareCriterias($request->request->all(), $attributes) : [];

        /** @var PostRepository $repository */
        $repository = $this->manager->getService(ServiceInterface::REPOSITORY_SERVICE_NAME);

        $posts = !empty($repository) ? $repository->findBy($criterias, null, ($page * $perPage) - $perPage, $perPage) : [];

        $view = !empty($attributes['vue']) ? $attributes['vue'] : 'list';

        $viewParams = [
            'posts'      => $posts,
            'attributes' => $attributes,
        ];

        return $this->renderView($view, $this->filterViewParams($viewParams));
    }

    protected function filterViewParams($viewParams)
    {
        return $viewParams;
    }
}
