<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Properties; // Service Class
use AppBundle\Service\Pageinate;

class UserController extends Controller
{

    /**   
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $email = $session->get('email');
        if(isset($email)) {
        	return $this->render('user/dashboard.html.twig');
        } else {
        	$this->addFlash('danger','Your session has expired. Please log back in.');
			return $this->redirectToRoute('homepage');  
        }
    }        

}