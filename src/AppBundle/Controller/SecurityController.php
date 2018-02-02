<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Properties; // Service Class
use AppBundle\Service\Pageinate;

class SecurityController extends Controller
{

    /**   
     * @Route("/warning", name="warning")
     */
    public function warningAction(Request $request)
    {
        return $this->render('user/warnings.html.twig');
    }

    /**   
     * @Route("/saveuser", name="saveuser")
     */
    public function saveuserAction(Request $request)
    {

    	$em = $this->getDoctrine()->getManager();
    	$first = $request->request->get('first');
    	$last = $request->request->get('last');
    	$email = $request->request->get('email');
    	$phone = $request->request->get('phone');
    	$password = $request->request->get('password');
    	$password2 = $request->request->get('password2');

    	$sql = "SELECT `email` FROM `users` WHERE `email` = '$email' LIMIT 1";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();

        while ($row = $result->fetch()) {
        	$this->addFlash('danger','The email provided is already registered.');
        	return $this->redirectToRoute('homepage');        	
        }

        if (($password != $password2) and ($password != "")) {
        	$this->addFlash('danger','The password did not match.');
        	return $this->redirectToRoute('homepage');           	
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);  

        $date = date("Ymd");
        $sql = "INSERT INTO `users` 
        (`first`,`last`,`email`,`phone`,`password`,`active`,`date`)
        VALUES
        ('$first','$last','$email','$phone','$hashed_password','Yes','$date')
        ";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();

        $session = $this->get('commonservices')->GetSessionData();
        $session->set('first',$first);
        $session->set('last',$last);
        $session->set('email',$email);
        $session->set('phone',$phone);
        $id = $em->getConnection()->lastInsertId();
        $session->set('id',$id);
        
        $this->addFlash('success','Your account was created.');
        return $this->redirectToRoute('homepage');
    }

    /**   
     * @Route("/userlogin", name="userlogin")
     */
    public function userloginAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $session = $this->get('commonservices')->GetSessionData();

        $sql = "SELECT `id`,`first`,`last`,`email`,`phone`,`password`,`userType` FROM `users`
        WHERE `email` = '$email' AND `active` = 'Yes' LIMIT 1";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        while ($row = $result->fetch()) {
            if(password_verify($password, $row['password'])) {
                foreach ($row as $key=>$value) {
                    if ($key != "password") {
                        $session->set($key,$value);
                    }
                }
                $this->addFlash('success','You have been logged in.');
                return $this->redirectToRoute('homepage');                
            }
        }

        $this->addFlash('danger','There was an error processing your login.');
        return $this->redirectToRoute('homepage'); 

    }

    /**   
     * @Route("/userlogout", name="userlogout")
     */
    public function userlogoutAction(Request $request)
    {

        $session = $this->get('commonservices')->GetSessionData();
        $session->clear();

        $this->addFlash('info','You have been logged out.');
        return $this->redirectToRoute('homepage'); 

    }
}