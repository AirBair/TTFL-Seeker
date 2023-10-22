<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\FantasyUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Contracts\Service\Attribute\Required;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    private EntityManagerInterface $entityManager;

    #[Required]
    public function setEntityManagerInterface(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        $user = $token->getUser();
        if ($user instanceof FantasyUser) {
            $user->setLastLoginAt(new \DateTime());
            $this->entityManager->flush();
        }

        return parent::onAuthenticationSuccess($request, $token);
    }
}
