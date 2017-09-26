<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 29/10/2015
 * Time: 16:32
 */

namespace AppBundle\Controller;

use AppBundle\Repository\PersonRepository;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\ProductReviewRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @Route("/counter")
 */
class CounterController extends Controller
{
    /**
     * @Route("/", name="app_counter_index")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('partial/counter/_index.html.twig', array(
            'productCount' => $this->getProductRepository()->findNbResults(),
            'personCount' => $this->getPersonRepository()->findNbResults(),
            'userCount' => $this->getUserRepository()->findNbResults(),
            'productReviewCount' => $this->getProductReviewRepository()->findNbResults(),
        ));
    }

    /**
     * @return ProductRepository
     */
    protected function getProductRepository()
    {
        return $this->get('sylius.repository.product');
    }

    /**
     * @return PersonRepository
     */
    protected function getPersonRepository()
    {
        return $this->get('app.repository.person');
    }

    /**
     * @return UserRepository
     */
    protected function getUserRepository()
    {
        return $this->get('sylius.repository.app_user');
    }

    /**
     * @return ProductReviewRepository
     */
    protected function getProductReviewRepository()
    {
        return $this->get('sylius.repository.product_review');
    }
}