<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
    public function __construct(private Security $security)
    {
    }

    /**
     * @param string $attribute
     * @param Task|object $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, ['TASK_EDIT', 'TASK_TOGGLE', 'TASK_DELETE']) && $subject instanceof Task;
    }

    /**
     * @param string $attribute
     * @param Task $subject
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN') && $subject->getUser() === null) {
            return true;
        }

        return $subject->getUser()?->getUserIdentifier() === $token->getUser()?->getUserIdentifier();
    }
}
