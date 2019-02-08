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

use Sidus\EAVModelBundle\Entity\DataInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Allows the access to a data based on it's family
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class EAVDataVoter implements VoterInterface
{
    /** @var FamilyVoter */
    protected $familyVoter;

    /**
     * @param FamilyVoter $familyVoter
     */
    public function __construct(FamilyVoter $familyVoter)
    {
        $this->familyVoter = $familyVoter;
    }

    /**
     * {@inheritdoc}
     */
    public function vote(TokenInterface $token, $object, array $attributes): ?int
    {
        if ($object instanceof DataInterface) {
            return $this->familyVoter->vote($token, $object->getFamily(), $attributes);
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}
