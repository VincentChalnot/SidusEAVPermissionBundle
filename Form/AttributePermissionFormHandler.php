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
use Sidus\EAVPermissionBundle\Security\Permission;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /**
     * @param AttributeFormBuilderInterface $baseAttributeFormBuilder
     * @param TokenStorageInterface         $tokenStorage
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        AttributeFormBuilderInterface $baseAttributeFormBuilder,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->baseAttributeFormBuilder = $baseAttributeFormBuilder;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param AttributeInterface   $attribute
     * @param array                $options
     *
     * @throws \Exception
     */
    public function addAttribute(
        FormBuilderInterface $builder,
        AttributeInterface $attribute,
        array $options = []
    ) {
        // Not (Read OR edit)
        if (!$this->authorizationChecker->isGranted([Permission::READ, Permission::EDIT], $attribute)) {
            return;
        }
        if (!$this->authorizationChecker->isGranted(Permission::EDIT, $attribute)) {
            $options['form_options']['disabled'] = true;
        }

        $this->baseAttributeFormBuilder->addAttribute($builder, $attribute, $options);
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserInterface|null
     */
    protected function getUser()
    {
        if (!$this->tokenStorage->getToken()) {
            return null;
        }

        return $this->tokenStorage->getToken()->getUser();
    }
}
