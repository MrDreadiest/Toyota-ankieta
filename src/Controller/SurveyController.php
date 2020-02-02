<?php

namespace App\Controller;

use App\Entity\Dealer;
use App\Entity\Question;
use App\Entity\UserQuestionAnswer;
use App\Entity\UserQuestionAnswerDeleted;
use App\Entity\UserToken;
use App\Form\UserQuestionAnswerType;
use App\Form\UserTokenReviewType;
use App\Model\UserQuestionAnswer\SetUserQuestionAnswer;
use App\Model\UserToken\SetUserTokenReview;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Routing\ClassResourceInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SurveyController
 * @package App\Controller
 * @View(serializerEnableMaxDepthChecks=true)
 */
class SurveyController extends AbstractFOSRestController implements ClassResourceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * SurveyController constructor.
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     */
    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->em = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @param string $token
     * @return array
     * @Template("default/index.html.twig")
     * @Rest\Route("survey/{token}", name="get_survey", methods={"GET"})
     */
    public function getAction(string $token)
    {
        $userToken = $this->em->getRepository(UserToken::class)->find($token);

        if (!$userToken) {
            return ['error' => 1];
        } elseif (
            ($userToken->getFinishedSurveyAt() && ($userToken->getReview() && $userToken->getSkippedReview() === false)) ||
            ($userToken->getFinishedSurveyAt() && (!$userToken->getReview() && $userToken->getSkippedReview() === true))
        ) {
            return ['error' => 2];
        } elseif (
            $userToken->getFinishedSurveyAt() && (!$userToken->getReview() && $userToken->getSkippedReview() === null)
        ) {
            return [
                'error' => 3,
                'token' => $userToken->getToken()
            ];
        }

        $questions = $this->em->getRepository(Question::class)
            ->findBy(
                ['survey' => $userToken->getSurvey()->getId()],
                ['id' => 'ASC']
            );

        $dealers = $this->em->getRepository(Dealer::class)->findAll();

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        $context->setSerializeNull(true);
        $context->setGroups('QuestionList');

        $jsonQuestions = $this->serializer->serialize($questions, 'json', $context);

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        $context->setSerializeNull(true);

        $jsonDealers = $this->serializer->serialize($dealers, 'json', $context);

        return [
            'error' => false,
            'survey' => $jsonQuestions,
            'token' => $token,
            'dealers' => $jsonDealers
        ];
    }

    /**
     * @param Request $request
     * @return array|Response
     * @throws Exception
     * @Rest\Route("survey-answer", name="post_survey_answer", methods={"POST"})
     */
    public function postAnswerAction(Request $request)
    {
        /** @var UserToken $userToken */
        $userToken = $this->getUser();

        $form = $this->createForm(UserQuestionAnswerType::class, new SetUserQuestionAnswer($this->em, $userToken));
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->handleView($this->view($form));
        }

        /** @var SetUserQuestionAnswer $setUserQuestionAnswer */
        $setUserQuestionAnswer = $form->getData();

        try {
            $this->em->beginTransaction();

            $userQuestionAnswer = new UserQuestionAnswer(
                $setUserQuestionAnswer->getUserToken(),
                $setUserQuestionAnswer->getAnswer(),
                new DateTime(),
                $setUserQuestionAnswer->getTextAnswer()
            );

            if ($setUserQuestionAnswer->findFirstQuestion() === $userQuestionAnswer->getAnswer()->getQuestion()) {
                $this->clearAndCloneUQA($userToken);
            }

            if (!$userQuestionAnswer->getAnswer()->getNextQuestion()) {
                $userToken->setFinishedSurveyAt(new DateTime());
                $this->getDoctrine()->getManager()->flush();
            }

            $this->em->persist($userQuestionAnswer);
            $this->em->flush();

            $this->em->commit();
        } catch (Exception $exception) {
            $this->em->rollBack();
            throw $exception;
        }

        return new JsonResponse(null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }

    /**
     * @param UserToken $userToken
     */
    public function clearAndCloneUQA(UserToken $userToken)
    {
        $tempAnswers = $this->em->getRepository(UserQuestionAnswer::class)->findBy(
            ['userToken' => $userToken->getToken()],
            ['answeredAt' => 'ASC']
        );

        foreach ($tempAnswers as $answer) {
            $this->em->persist(new UserQuestionAnswerDeleted($answer));
            $this->em->remove($answer);
            $this->em->flush();
        }
    }

    /**
     * @param Request $request
     * @Rest\Route("survey-review", name="post_survey_review", methods={"POST"})
     * @return array|Response
     * @throws Exception
     */
    public function postReviewAction(Request $request)
    {
        /** @var UserToken $userToken */
        $userToken = $this->getUser();

        $form = $this->createForm(UserTokenReviewType::class, new SetUserTokenReview($this->em, $userToken));
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->handleView($this->view($form));
        }

        /** @var UserToken $tempUserToken */
        $tempUserToken = $form->getData();
        if ($tempUserToken->getReview()){
            $userToken->setReview($tempUserToken->getReview());
            $userToken->setSkippedReview(false);
        } else {
            $userToken->setSkippedReview(true);
        }

        $this->em->flush();

        return new JsonResponse(null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }

    /**
     * @return JsonResponse
     * @throws Exception
     * @Rest\Route("survey-start", name="post_survey_start", methods={"POST"})
     */
    public function postStartAction()
    {
        /** @var UserToken $userToken */
        $userToken = $this->getUser();

        if(!$userToken->getStartedSurveyAt()){
            try {
                $userToken->setStartedSurveyAt(new DateTime());
                $this->em->persist($userToken);
                $this->em->flush();
            } catch (Exception $exception) {
                throw $exception;
            }
        }

        return new JsonResponse(null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }
}
