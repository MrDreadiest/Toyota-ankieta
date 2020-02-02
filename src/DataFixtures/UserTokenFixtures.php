<?php


namespace App\DataFixtures;

use App\Entity\Survey;
use App\Entity\UserToken;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;


class UserTokenFixtures extends BaseFixtures
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(UserToken::class, 100, function (UserToken $userToken, $count) {
            /** @var Survey $surveys */
            $surveys = $this->em->getRepository(Survey::class)->findAll();
                $userToken->setToken($this->faker->regexify('[A-Za-z0-9]{10}'))
                    ->setSurvey($this->faker->randomElement($surveys))
                    ->setFirstName($this->faker->firstName())
                    ->setLastName($this->faker->lastName());
        });
        $manager->flush();
    }
}