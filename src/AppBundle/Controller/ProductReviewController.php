<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 28/04/2016
 * Time: 13:42
 */

namespace AppBundle\Controller;

use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductReviewController extends ResourceController
{
    /**
     * @param Request $request
     * 
     * @return Response
     */
    public function showRatingAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);
        $resource = $this->singleResourceProvider->get($configuration, $this->repository);

        if (null !== $resource) {
            $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $resource);
        }

        $view = View::create($resource);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::SHOW))
                ->setTemplateVar($this->metadata->getName())
                ->setData([
                    'metadata' => $this->metadata,
                    'resource' => $resource,
                    $this->metadata->getName() => $resource,
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }
}