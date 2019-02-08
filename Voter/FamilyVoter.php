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

use Sidus\EAVModelBundle\Model\FamilyInterface;
use Sidus\EAVPermissionBundle\Security\Permission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Allows the access to a family based on the family permissions of a user.
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class FamilyVoter implements VoterInterface
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

        if (!$object instanceof FamilyInterface) {
            return $result;
        }

        if (!array_key_exists('permissions', $object->getOptions())) {
            return VoterInterface::ACCESS_GRANTED; // No permissions means access is granted
        }

        $permissions = $object->getOptions()['permissions'];

        $result = VoterInterface::ACCESS_DENIED;
        foreach ($attributes as $attribute) {
            if (!\in_array($attribute, Permission::getPermissions(), true)) {
                throw new \UnexpectedValueException("Invalid permission '{$attribute}'");
            }

            if (!array_key_exists($attribute, $permissions)) {
                return VoterInterface::ACCESS_GRANTED; // No permissions means access is granted
            }

            if ($this->decisionManager->decide($token, (array) $permissions[$attribute])) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return $result;
    }
}
