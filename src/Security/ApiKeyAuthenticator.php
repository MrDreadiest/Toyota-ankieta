<?php


namespace App\Security;


use App\Entity\UserToken;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

/**
 * Class ApiKeyAuthenticator
 * @package App\Security
 */
class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey)
    {
        $apiKey = $request->headers->get('token');

        if (!$apiKey) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('API Key does not exist, or null given.'), [], Response::HTTP_NOT_FOUND
            );
        }

        return new PreAuthenticatedToken(
            'anon.',
            $apiKey,
            $providerKey
        );
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof ApiKeyUserProvider) {
            throw new InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of ApiKeyUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $apiKey = $token->getCredentials();

        /** @var UserToken $userToken */
        $userToken = $userProvider->getUsernameForApiKey($apiKey);

        if (!$userToken) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('API Key "%s" does not exist.', $apiKey), [], Response::HTTP_NOT_FOUND
            );
        }

        if (
            ($userToken->getFinishedSurveyAt() && ($userToken->getReview() && $userToken->getSkippedReview() === false)) ||
            ($userToken->getFinishedSurveyAt() && (!$userToken->getReview() && $userToken->getSkippedReview() === true))
        ) {
            throw new CustomUserMessageAuthenticationException(
                'Survey is finished.', [], Response::HTTP_CONFLICT
            );
        }

        return new PreAuthenticatedToken(
            $userToken,
            $apiKey,
            $providerKey,
            ['ROLE_USER']
        );
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {

        $data = [
            'code' => $exception->getCode(),
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, $exception->getCode());
    }
}