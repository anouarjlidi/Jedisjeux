<?php

/*
 * This file is part of jdj project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Block;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class BlockType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('title', TextType::class, [
                'label' => 'sylius.ui.title',
                'required' => false,
            ])
            ->add('image', BlockImageType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('imagePosition', ChoiceType::class, [
                'label' => 'app.ui.image_position',
                'required' => true,
                'choices_as_values' => true,
                'choices' => [
                    'app.ui.image_on_the_top_side' => Block::POSITION_TOP,
                    'app.ui.image_on_the_left_side' => Block::POSITION_LEFT,
                    'app.ui.image_on_the_right_side' => Block::POSITION_RIGHT,
                ],
            ])
            ->add('class', ChoiceType::class, [
                'label' => 'app.ui.style',
                'placeholder' => 'app.ui.normal',
                'required' => false,
                'choices_as_values' => true,
                'choices' => [
                    'app.ui.framed' => 'well'
                ],
            ])
            ->add('body', CKEditorType::class, [
                'label' => 'sylius.ui.body',
                'required' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'validation_groups' => $this->validationGroups,
            'cascade_validation' => true,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'single_image_block';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_block';
    }
}
