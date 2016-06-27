<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\PollType;
use AppBundle\Entity\Poll;
use AppBundle\Entity\Option;
use AppBundle\Entity\Participant;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="createForm")
     */
    public function createFormAction(Request $request)
    {
		//////////////////
		// Sample poll
        $poll = new Poll();
		$poll->setQuestion('Quelle est la question ?');
		
        $option1 = new Option();
        $option1->setText('Choix 1');
        $poll->addOption($option1);
		
        $option2 = new Option();
        $option2->setText('Choix 2');
        $poll->addOption($option2);
        
        $participant1 = new Participant();
        $participant1->setEmailClear('Participant1@email.com');
        $participant1->setIsAdmin(true);
        $poll->addParticipant($participant1);
		
        $participant2 = new Participant();
        $participant2->setEmailClear('Participant2@email.com');
        $participant2->setIsAdmin(false);
        $poll->addParticipant($participant2);
		//////////////////
		
        $form_poll = $this->createForm(
				PollType::class, 
				$poll,
				array( 'action' => $this->generateUrl('create'))
			);
		
        return $this->render('default/creation.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
			'form_poll' => $form_poll->createView(),
        ]);
    }
	
    /**
     * @Route("/create", name="create")
     */
    public function createAction(Request $request)
    {
        $poll = new Poll();
        $form_poll = $this->createForm(
				PollType::class, 
				$poll,
				array( 'action' => $this->generateUrl('create'))
			);
			
        $form_poll->handleRequest($request);

        if ($form_poll->isSubmitted() && $form_poll->isValid()) {
            
			$newToken = bin2hex(openssl_random_pseudo_bytes( 32 ));
			$poll->setToken( $newToken );
			$poll->setIsClosed(false);
            $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');
            
            foreach($poll->getParticipants() as $participant) {
                $newToken = bin2hex(openssl_random_pseudo_bytes( 32 ));
                
                $user = $userRepository->findByEmailClear( $participant->getEmailClear() );
                if(!$user) {
                    $user = new User();
                    $user->setEmailHashFromEmailClear($participant->getEmailClear());
                    $user->setScore(0);
                }
                $participant->setToken($newToken);
                $participant->setUser($user);
            }
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($poll);
			$em->flush();
			
			// send emails
			$pollToken = $poll->getToken();
			$creatorRouteParams = null;
            foreach($poll->getParticipants() as $participant) {
				$email = $participant->getEmailClear();
                $participantToken = $participant->getToken();
				$routeParams = array( 'pollId' => $poll->getId(), 'participantToken' => $participantToken);
				if(!$creatorRouteParams)
					$creatorRouteParams = $routeParams;
				
				$link = $this->get('router')->generate('voteForm', $routeParams, UrlGeneratorInterface::ABSOLUTE_URL);
				$twigparams = array('link'=>$link, 'is_admin'=>$participant->getIsAdmin() );
				
				$message = \Swift_Message::newInstance();
				$message->setSubject('Hello Email');
				$message->setFrom('send@example.com');
				$message->setTo($email);
				$message->setBody( $this->renderView( 'emails/participant.html.twig', $twigparams ), 'text/html'  );
				$message->addPart( $this->renderView( 'emails/participant.txt.twig', $twigparams ), 'text/plain' );
				$this->get('mailer')->send($message);
            }
			
			return $this->redirectToRoute( 'voteForm', $creatorRouteParams );
        } else {
			
			return $this->render('default/creation.html.twig', [
				'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
				'form_poll' => $form_poll->createView(),
			]);
		}
		
    }
	
	private function createFormChoice( Poll $poll, $participantToken ) 
	{
		$choicesResult = array();
		$formChoices = $this->createFormBuilder($choicesResult)
			->add('choice', ChoiceType::class, array(
					'label' => 'Vote',
					'choices' => $poll->getOptions(),
					'choice_label' => 'text',
					'expanded' => true
				))
			->add('expert', ChoiceType::class, array(
					'label' => 'Expert',
					'choices' => $poll->getParticipants(),
					'choice_label' => 'user.name',
					'expanded' => true
				))
			->add('vote', SubmitType::class, array('label' => 'Vote'))
			->setAction($this->generateUrl('vote', array('pollId' => $poll->getId(), 'participantToken'=>$participantToken )))
			->getForm();
		return $formChoices;
	}
	
	private function createFormAdmin( Poll $poll, $participantToken ) 
	{
		$adminResult = array();	
		$formAdmin = $this->createFormBuilder($adminResult)
			->add('close', SubmitType::class, array('label' => 'Close Poll'))
			->setAction($this->generateUrl('close', array('pollId' => $poll->getId(), 'participantToken'=>$participantToken )))
			->getForm();
		return $formAdmin;
	}
	
    /**
     * @Route("/voteform/{pollId}/{participantToken}", name="voteForm")
     */
    public function voteFormAction($pollId, $participantToken, Request $request)
    {				
		$pollRepository = $this->getDoctrine()->getRepository('AppBundle:Poll');
		$poll = $pollRepository->findOneById($pollId);		
		if(!$poll) {
			throw $this->createNotFoundException('The poll does not exist');
		}		
		
		$participant = $poll->findParticipantFromToken($participantToken);
		
		$isClosed = $poll->getIsClosed();		
		$isAdmin = $participant->getIsAdmin();
		$formChoices = null;
		$formAdmin = null;
		if($isClosed) {
			
		} else {
			if(!$participant) {
				throw $this->createNotFoundException('The participant does not exist');
			}
			$formChoices = $this->createFormChoice( $poll, $participantToken );
			if($isAdmin) {
				$adminResult = array();	
				$formAdmin = $this->createFormAdmin( $poll, $participantToken );
			}
		}
		
		return $this->render('default/vote.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
			'is_closed' => $isClosed,
			'is_admin' => $isAdmin,
			'form_choices' => ( $formChoices ? $formChoices->createView() : null),
			'form_admin' => ( $formAdmin ? $formAdmin->createView() : null),
        ]);
    }
	
    /**
     * @Route("/vote/{pollId}/{participantToken}", name="vote")
     */
    public function voteAction($pollId, $participantToken, Request $request)
    {		
		return $this->redirectToRoute( 'voteForm', array('pollId' => $pollId, 'participantToken'=>$participantToken ) );
    }
	
    /**
     * @Route("/close/{pollId}/{participantToken}", name="close")
     */
    public function closeAction($pollId, $participantToken, Request $request)
    {		
		return $this->redirectToRoute( 'voteForm', array('pollId' => $pollId, 'participantToken'=>$participantToken ) );
    }
}
