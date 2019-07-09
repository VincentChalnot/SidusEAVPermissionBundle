<?php
/*
 * This file is part of the Sidus/EAVPermissionBundle package.
 *
 * Copyright (c) 2015-2019 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\EAVPermissionBundle\Form;

use Sidus\EAVModelBundle\Form\AttributeFormBuilderInterface;
use Sidus\EAVModelBundle\Model\AttributeInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Overrides base attribute form builder to handle permissions
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class AttributePermissionFormHandler implements AttributeFormBuilderInterface
{
    /** @var AttributeFormBuilderInterface */
    protected $baseAttributeFormBuilder;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /**
     * @param AttributeFormBuilderInterface $baseAttributeFormBuilder
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        AttributeFormBuilderInterface $baseAttributeFormBuilder,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->baseAttributeFormBuilder = $baseAttributeFormBuilder;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function addAttribute(
        FormBuilderInterface $builder,
        AttributeInterface $attribute,
        array $options = []
    ): void {
        // Not (Read OR edit)
        if (!$this->authorizationChecker->isGranted(['read', 'edit'], $attribute)) {
            return;
        }
        if (!$this->authorizationChecker->isGranted('edit', $attribute)) {
            $options['form_options']['disabled'] = true;
        }

        $this->baseAttributeFormBuilder->addAttribute($builder, $attribute, $options);
    }
}
