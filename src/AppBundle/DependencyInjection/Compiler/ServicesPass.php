<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DependencyInjection\Compiler;

use AppBundle\EventListener\PasswordUpdaterListener;
use AppBundle\EventListener\RequestLocaleSetter;
use AppBundle\Factory\ProductFactory;
use AppBundle\Feed\FeedDumpService;
use Sylius\Component\Review\Factory\ReviewFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ServicesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->processFactories($container);
        $this->processFormTypes($container);
        $this->processListeners($container);

        $container->setAlias('sylius.context.customer', 'app.context.customer');

        $contextLocaleCompositeDefinition = $container->getDefinition("sylius.context.locale.composite");
        $contextLocaleCompositeDefinition->setDecoratedService(null);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function processFactories(ContainerBuilder $container)
    {
        $topicFactoryDefinition = $container->getDefinition('app.factory.topic');
        $topicFactoryDefinition
            ->addMethodCall('setCustomerContext', [new Reference('app.context.customer')])
            ->addMethodCall('setGamePlayRepository', [new Reference('app.repository.game_play')])
            ->addMethodCall('setPostFactory', [new Reference('app.factory.post')]);

        $postFactoryDefinition = $container->getDefinition('app.factory.post');
        $postFactoryDefinition
            ->addMethodCall('setCustomerContext', [new Reference('app.context.customer')]);

        $notificationFactoryDefinition = $container->getDefinition('app.factory.notification');
        $notificationFactoryDefinition
            ->addMethodCall('setRouter', [new Reference('router')])
            ->addMethodCall('setTranslator', [new Reference('translator.default')]);

        $gamePlayFactoryDefinition = $container->getDefinition('app.factory.game_play');
        $gamePlayFactoryDefinition
            ->addMethodCall('setProductRepository', [new Reference('sylius.repository.product')])
            ->addMethodCall('setCustomerContext', [new Reference('app.context.customer')]);

        $productFactoryDefinition = $container->getDefinition('sylius.custom_factory.product');
        $productFactoryDefinition
            ->setClass(ProductFactory::class)
            ->addMethodCall('setPersonRepository', [new Reference('app.repository.person')])
            ->addMethodCall('setProductVariantImageFactory', [new Reference('app.factory.product_variant_image')])
            ->addMethodCall('setSlugGenerator', [new Reference('sylius.generator.slug')]);

        $articleFactoryDefinition = $container->getDefinition('app.factory.article');
        $articleFactoryDefinition
            ->addMethodCall('setProductRepository', [new Reference('sylius.repository.product')])
            ->addMethodCall('setCustomerContext', [new Reference('app.context.customer')])
            ->addMethodCall('setBlockFactory', [new Reference('app.factory.block')]);

        $contactRequestFactoryDefinition = $container->getDefinition('app.factory.contact_request');
        $contactRequestFactoryDefinition
            ->addMethodCall('setCustomerContext', [new Reference('app.context.customer')]);

        $stringBlockFactoryDefinition = $container->getDefinition('app.factory.string_block');
        $stringBlockFactoryDefinition
            ->addArgument(new Reference('doctrine_phpcr.odm.document_manager'))
            ->addArgument(new Parameter('cmf_block.persistence.phpcr.block_basepath'));

        $productListFactoryDefinition = $container->getDefinition('app.factory.product_list');
        $productListFactoryDefinition
            ->addArgument(new Reference('app.context.customer'))
            ->addArgument(new Reference('translator.default'));


//        $subject = "article";
//
//        $factory = $container->findDefinition('sylius.factory.'.$subject.'_review');
//        $reviewFactoryDefinition = new Definition(ReviewFactory::class);
//
//        $reviewFactory = $container->setDefinition(sprintf('sylius.factory.'.$subject.'_review'), $reviewFactoryDefinition);
//        $reviewFactory->addArgument($factory);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function processFormTypes(ContainerBuilder $container)
    {
        $articleFormTypeDefinition = $container->getDefinition('app.form.type.article');
        $articleFormTypeDefinition
            ->addMethodCall('setManager', [new Reference('doctrine.orm.entity_manager')]);

        $gamePlayFormTypeDefinition = $container->getDefinition('app.form.type.game_play');
        $gamePlayFormTypeDefinition
            ->addMethodCall('setManager', [new Reference('doctrine.orm.entity_manager')]);

        $topicFormTypeDefinition = $container->getDefinition('app.form.type.topic');
        $topicFormTypeDefinition
            ->addMethodCall('setAuthorizationChecker', [new Reference('security.authorization_checker')]);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processListeners(ContainerBuilder $container)
    {
        $listenerPasswordUpdaterDefinition = $container->getDefinition('sylius.listener.password_updater');
        $listenerPasswordUpdaterDefinition
            ->setClass(PasswordUpdaterListener::class);
        $listenerPasswordUpdaterDefinition->addTag('kernel.event_listener', [
            'event' => 'sylius.customer.pre_update',
            'method' => 'customerUpdateEvent'
        ]);
    }
}
