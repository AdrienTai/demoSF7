<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class APIAuthenticator extends AbstractAuthenticator
{
	public function supports(Request $request): ?bool
	{
		return $request->headers->has('Authorization') && str_contains($request->headers->get('Authorization'), 'Bearer');
	}

	public function authenticate(Request $request): Passport
	{
		$token = substr($request->headers->get('Authorization'), 7);
        return new SelfValidatingPassport(
            new UserBadge($token)
        );
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
	{
		return null;
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
	{
		return new JsonResponse([
            'message' => $exception->getMessage(),            
        ], Response::HTTP_UNAUTHORIZED);
	}
}