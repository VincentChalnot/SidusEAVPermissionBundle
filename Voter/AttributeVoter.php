<?php
/*
 * This file is part of the Sidus/EAVPermissionBundle package.
 *
 * Copyright (c) 2015-2019 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\EAVPermissionBundle\Voter;

use Sidus\EAVModelBundle\Model\AttributeInterface;
use Sidus\EAVPermissionBundle\Security\Permission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Checks if an attribute is readable or editable
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class AttributeVoter implements VoterInterface
{
    /** @var AccessDecisionManagerInterface */
    protected $decisionManager;

    /**
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;
        if (!$object instanceof AttributeInterface) {
            return $result;
        }
        $permissions = $object->getOption('permissions');
        if (empty($permissions)) {
            return VoterInterface::ACCESS_GRANTED; // No permissions means always editable (thus readable)
        }

        foreach ($attributes as $attribute) {
            if (!\in_array($attribute, [Permission::EDIT, Permission::READ], true)) {
                throw new \UnexpectedValueException('Unsupported Attribute permission type '.$attribute);
            }

            if (!array_key_exists($attribute, $permissions)) {
                return VoterInterface::ACCESS_GRANTED; // Always grant undefined permissions
            }

            if ($this->decisionManager->decide($token, (array) $permissions[$attribute])) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
