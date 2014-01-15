<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Settings;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use Sylius\Bundle\SettingsBundle\Schema\SchemaInterface;
use Sylius\Bundle\SettingsBundle\Schema\SettingsBuilderInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * General settings schema.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class GeneralSettingsSchema implements SchemaInterface
{
    /**
     * @var array
     */
    protected $defaults;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @param array            $defaults
     * @param ObjectRepository $repository
     */
    public function __construct(array $defaults = array(), ObjectRepository $repository)
    {
        $this->defaults = $defaults;
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildSettings(SettingsBuilderInterface $builder)
    {
        $builder
            ->setDefaults(array_merge(array(
                'title'            => 'Sylius - Modern ecommerce for Symfony2',
                'meta_keywords'    => 'symfony, sylius, ecommerce, webshop, shopping cart',
                'meta_description' => 'Sylius is modern ecommerce solution for PHP. Based on the Symfony2 framework.',
            ), $this->defaults))
            ->setAllowedTypes(array(
                'title'            => array('string'),
                'meta_keywords'    => array('string'),
                'meta_description' => array('string'),
                'locale'           => array('string', 'integer'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('title', 'text', array(
                'label'       => 'sylius.form.settings.general.title',
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('meta_keywords', 'text', array(
                'label'       => 'sylius.form.settings.general.meta_keywords',
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('meta_description', 'textarea', array(
                'label'       => 'sylius.form.settings.general.meta_description',
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('locale', 'entity', array(
                'label'         => 'sylius.form.settings.general.locale',
                'empty_value'   => 'sylius.form.settings.general.choose_locale',
                'class'         => 'Sylius\Bundle\CoreBundle\Model\Locale',
                'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('l')
                            ->where('l.enabled = 1');
                    },
                'constraints'   => array(
                    new NotBlank(),
                )
            ))
        ;

        $repository = $this->repository;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($repository) {
            $settings = $event->getData();

            if (null !== $settings['locale']) {
                $settings['locale'] = $repository->findOneBy(array('id' => $settings['locale']));
            }
        });
        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) use ($repository) {
            $settings = $event->getData();

            if (is_object($settings['locale'])) {
                $settings['locale'] = $settings['locale']->getId();
            }
        });
    }
}
