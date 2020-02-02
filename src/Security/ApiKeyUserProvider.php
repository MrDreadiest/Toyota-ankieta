<?php


namespace App\Security;

use App\Entity\UserToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiKeyUserProvider implements UserProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * ApiKeyUserProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $apiKey
     * @return string
     */
    public function getUsernameForApiKey($apiKey)
    {
        return $this->em->getRepository(UserToken::class)->find($apiKey);
    }

    /**
     * @param string $username The username
     * @return UserInterface
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        return $this->em->getRepository(UserToken::class)->find($username);
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function refreshUser(UserInterface $user)
    {
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}