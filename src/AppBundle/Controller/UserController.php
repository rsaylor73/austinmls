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
        $id = $session->get('id');
        if(isset($id)) {
        	return $this->render('user/dashboard.html.twig');
        } else {
        	$this->addFlash('danger','Your session has expired. Please log back in.');
			return $this->redirectToRoute('homepage');  
        }
    }        

    /**   
     * @Route("/updateprofile", name="updateprofile")
     */
    public function updateprofileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $email = $session->get('email');
        $id = $session->get('id');

        if(isset($id)) {
            $sql = "SELECT `first`,`last`,`email`,`phone` FROM `users` WHERE `id` = '$id' AND `active` = 'Yes' LIMIT 1";
            $result = $em->getConnection()->prepare($sql);
            $result->execute();
            $data = "";
            while ($row = $result->fetch()) {
                foreach ($row as $key=>$value) {
                    $data[$key] = $value;
                }
            }

            return $this->render('user/profile.html.twig',[
                'data' => $data,
            ]);
        } else {
            $this->addFlash('danger','Your session has expired. Please log back in.');
            return $this->redirectToRoute('homepage');  
        }
    } 

    /**   
     * @Route("/saveupdateprofile", name="saveupdateprofile")
     */
    public function saveupdateprofileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $id = $session->get('id');

        $first = $request->request->get('first');
        $last = $request->request->get('last');
        $email = $request->request->get('email');
        $phone = $request->request->get('phone');
        $password = $request->request->get('password');

        $pw_sql = "";
        if ($password != "") {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $pw_sql = ",`password` = '$hashed_password'";
        }

        // check email
        $sql = "SELECT `email` FROM `users` WHERE `email` = '$email' AND `id` != '$id'";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        while ($row = $result->fetch()) {    
            $this->addFlash('danger','The email you entered is registered with another user.');
            return $this->redirectToRoute('updateprofile');        
        }

        $sql = "UPDATE `users` SET `first` = '$first', `last` = '$last', `email` = '$email', `phone` = '$phone' $pw_sql WHERE `id` = '$id'";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();            
        $this->addFlash('success','Your profile was updated. If you changed your password you will need to log back in.');
        return $this->redirectToRoute('updateprofile'); 

    }
}