<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Properties; // Service Class
use AppBundle\Service\Pageinate;

class FormController extends Controller
{

    /**   
     * @Route("/detailformmini", name="detailformmini")
     */
    public function detailformminiAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $phone = $request->request->get('phone');
        $comments = $request->request->get('comments');
        $url = $this->container->getParameter('site_url') . "details/" . $id;
        $site_name = $this->container->getParameter('site_name');
        $site_email = $this->container->getParameter('site_email');
        //$email = "robert@customphpdesign.com";
        $email = "contact@freeaustinmlssearch.com";


        $message = (new \Swift_Message('New property request'))
            ->setFrom($site_email)
            ->setTo($email)
            ->setSubject('New property request')
            ->setBody(
                $this->renderView(
                    'email/detailsminiform.html.twig',
                    array(
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'comments' => $comments,
                        'url' => $url
                    )
                ),
                'text/html'
            )
        ;

        $this->get('mailer')->send($message);

        $this->addFlash('success','The form was submitted');
        return $this->redirectToRoute('details',[
            'id' => $id,
        ]);
    }


    /**   
     * @Route("/detailformrequest", name="detailformrequest")
     */
    public function detailformrequestAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $phone = $request->request->get('phone');
	$subj = $request->request->get('subj');
	$date = $request->request->get('date');
	$time = $request->request->get('time');
        $comments = $request->request->get('comments');
        $url = $this->container->getParameter('site_url') . "details/" . $id;
        $site_name = $this->container->getParameter('site_name');
        $site_email = $this->container->getParameter('site_email');
        //$email = "robert@customphpdesign.com";
        $email = "contact@freeaustinmlssearch.com";

        
        $message = (new \Swift_Message('New property request'))
            ->setFrom($site_email)
            ->setTo($email)
            ->setSubject('New property request')
            ->setBody(
                $this->renderView(
                    'email/detailformrequest.html.twig',
                    array(
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
			'subj' => $subj,
			'date' => $date,
			'time' => $time,
                        'comments' => $comments,
                        'url' => $url
                    )
                ),
                'text/html'
            )
        ;

        $this->get('mailer')->send($message);

        $this->addFlash('success','The form was submitted');
        return $this->redirectToRoute('details',[
            'id' => $id,
        ]);
    }


}
