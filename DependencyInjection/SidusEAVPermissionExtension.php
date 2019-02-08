<?php
/*
 * This file is part of the Sidus/EAVPermissionBundle package.
 *
 * Copyright (c) 2015-2019 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\EAVPermissionBundle\DependencyInjection;

use Sidus\BaseBundle\DependencyInjection\Loader\ServiceLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Sidus\EAVBootstrapBundle\Form\TabbedAttributeFormBuilder;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SidusEAVPermissionExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new ServiceLoader($container);
        $loader->loadFiles(__DIR__.'/../Resources/config/services');

        // This should actually check the existence of the service but we can't because of the service loading order
        if (class_exists(TabbedAttributeFormBuilder::class)) {
            $loader->loadFiles(__DIR__.'/../Resources/config/bootstrap');
        }
    }
}
