<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Properties; // Service Class
use AppBundle\Service\Pageinate;

class DetailsController extends Controller
{

    /**   
     * @Route("/details/{id}", name="details")

     */
    public function homeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('details/index.html.twig');
    }

}
