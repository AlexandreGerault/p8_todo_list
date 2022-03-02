<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        return $attribute == 'TASK_EDIT' && $subject instanceof Task;
    }

    /**
     * @param string $attribute
     * @param Task $subject
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        return $subject?->getUser()->getUserIdentifier() === $token->getUser()->getUserIdentifier();
    }
}
