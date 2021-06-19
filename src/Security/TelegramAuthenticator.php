<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\UserRepository;
use App\Telegram\Security\TelegramAuthorization;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

use function strtr;

class TelegramAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    private RouterInterface $router;
    private TelegramAuthorization $telegramAuthorization;
    private UserRepository $userRepository;

    public function __construct(
        RouterInterface $router,
        TelegramAuthorization $telegramAuthorization,
        UserRepository $userRepository
    ) {
        $this->router                = $router;
        $this->telegramAuthorization = $telegramAuthorization;
        $this->userRepository        = $userRepository;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_telegram_check';
    }

    public function authenticate(Request $request): PassportInterface
    {
        if (! $this->telegramAuthorization->check($request)) {
            throw new CustomUserMessageAuthenticationException('Error al validar el token de Telegram');
        }

        return new SelfValidatingPassport(
            new UserBadge(
                $request->get('id'),
                fn ($userIdentifier) => $this->userRepository->find($userIdentifier)
            )
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $homepagePath = $this->router->generate('homepage');

        if (! $request->getSession() instanceof Session) {
            return new RedirectResponse($homepagePath);
        }

        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        if (! $targetPath) {
            return new RedirectResponse($homepagePath);
        }

        return new RedirectResponse($targetPath);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}
