<?php

namespace AppBundle\Controller;

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
        return $this->render('default/creation.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }
	
    /**
     * @Route("/create", name="create")
     */
    public function createAction(Request $request)
    {
		return $this->redirect( $this->generateUrl('voteForm') );
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
		
		return $this->redirect( $this->generateUrl('voteForm') );
    }
	
    /**
     * @Route("/close/", name="close")
     */
    public function closeAction(Request $request)
    {
		return $this->redirect( $this->generateUrl('voteForm') );
    }
}
