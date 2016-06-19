<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Poll;
use AppBundle\Entity\Option;
use AppBundle\Form\Type\PollType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        $option1->setPoll($poll);
        $poll->getOptions()->add($option1);
		
        $option2 = new Option();
        $option2->setText('Choix 2');
        $option2->setPoll($poll);
        $poll->getOptions()->add($option2);
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
            $poll = $form_poll->getData();
			
			$newToken = bin2hex(openssl_random_pseudo_bytes( 32 ));
			$poll->setToken( $newToken );
			$poll->setIsClosed(false);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($poll);
			$em->flush();
			
			return $this->redirectToRoute( 'voteForm' );
        } else {
			
			return $this->render('default/creation.html.twig', [
				'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
				'form_poll' => $form_poll->createView(),
			]);
		}
		
    }
	
    /**
     * @Route("/voteform/", name="voteForm")
     */
    public function voteFormAction(Request $request)
    {
        return $this->render('default/vote.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
			'is_closed' => true,
        ]);
    }
	
    /**
     * @Route("/vote/", name="vote")
     */
    public function voteAction(Request $request)
    {
		
		return $this->redirectToRoute( 'voteForm' );
    }
	
    /**
     * @Route("/close/", name="close")
     */
    public function closeAction(Request $request)
    {
		return $this->redirectToRoute( 'voteForm' );
    }
}
