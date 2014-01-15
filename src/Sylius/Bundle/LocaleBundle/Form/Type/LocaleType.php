<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\LocaleBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Locale type.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class LocaleType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'locale', array(
                'label'       => 'sylius.form.locale.code',
                'empty_value' => 'sylius.form.locale.select_code',
            ))
            ->add('currency', 'currency', array(
                'label'       => 'sylius.form.locale.currency',
                'empty_value' => 'sylius.form.locale.select_currency',
            ))
            ->add('enabled', 'checkbox', array(
                'label'    => 'sylius.form.locale.enabled',
                'required' => false,
            ))
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_locale';
    }
}
