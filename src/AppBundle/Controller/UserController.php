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

        if(!isset($id)) {
            $this->addFlash('danger','Your session has expired. Please log back in.');
            return $this->redirectToRoute('homepage');
        }

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
    } 

    /**   
     * @Route("/saveupdateprofile", name="saveupdateprofile")
     */
    public function saveupdateprofileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $id = $session->get('id');

        if(!isset($id)) {
            $this->addFlash('danger','Your session has expired. Please log back in.');
            return $this->redirectToRoute('homepage');
        }

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

    /**   
     * @Route("/addfavorite", name="addfavorite")
     */
    public function addfavoriteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $id = $session->get('id');

        if(!isset($id)) {
            $this->addFlash('danger','Your session has expired. Please log back in.');
            return $this->redirectToRoute('homepage');
        }

        $Matrix_Unique_ID = $request->query->get('Matrix_Unique_ID');
        $today = date("Ymd");
        $sql = "INSERT INTO `favorites` 
        (`Matrix_Unique_ID`,`userID`,`date_added`) 
        VALUES
        ('$Matrix_Unique_ID','$id','$today')
        ";
        $result = $em->getConnection()->prepare($sql);
        $result->execute(); 

        $this->addFlash('success','The property has been added to your favorites. Click on your dashboard to manage.');
        return $this->redirectToRoute('details',[
            'id' => $Matrix_Unique_ID,
        ]);
    }

    /**   
     * @Route("/myfavorite", name="myfavorite")
     */
    public function myfavoriteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $id = $session->get('id');

        if(!isset($id)) {
            $this->addFlash('danger','Your session has expired. Please log back in.');
            return $this->redirectToRoute('homepage');
        }

        $data = "";

        $sql = "
        SELECT
            `f`.`id`,
            `p`.`Matrix_Unique_ID`,
            `p`.`ListPrice`,
            `p`.`Address`,
            `p`.`City`,
            `p`.`StateOrProvince`,
            `p`.`PostalCode`

        FROM
            `favorites` f

        LEFT JOIN `properties` p ON `f`.`Matrix_Unique_ID` = `p`.`Matrix_Unique_ID`

        WHERE
            `f`.`userID` = '$id'

        ORDER BY `f`.`date_added` ASC
        ";

        $result = $em->getConnection()->prepare($sql);
        $result->execute(); 
        $i = "0";
        while ($row = $result->fetch()) {
            foreach ($row as $key=>$value) {
                $data[$i][$key] = $value;
            }
            $i++;
        }

        return $this->render('user/myfavorite.html.twig',[
            'data' => $data,
        ]);
    }

    /**   
     * @Route("/deletefavorite", name="deletefavorite")
     */
    public function deletefavoriteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $id = $session->get('id');

        if(!isset($id)) {
            $this->addFlash('danger','Your session has expired. Please log back in.');
            return $this->redirectToRoute('homepage');
        }

        $Matrix_Unique_ID = $request->query->get('Matrix_Unique_ID');

        $sql = "DELETE FROM `favorites` WHERE `Matrix_Unique_ID` = '$Matrix_Unique_ID' AND `userID` = '$id'";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();

        $this->addFlash('success','The property has been removed to your favorites.');
        return $this->redirectToRoute('myfavorite'); 
    }               
}