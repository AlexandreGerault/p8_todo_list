<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class WebAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): RedirectResponse
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()?->getFlashBag();

        $message = $accessDeniedException->getMessage();

        if ($message === "Access Denied.") {
            $message = "Vous n'avez pas le droit d'accéder à cette page.";
        }

        $flashBag->add('error', $message);

        return new RedirectResponse($request->headers->get('referer') ?? "/");
    }
}
