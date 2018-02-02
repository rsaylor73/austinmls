<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Properties; // Service Class
use AppBundle\Service\Pageinate;

class AdminController extends Controller
{

    /**   
     * @Route("/admin", name="admin")
     */
    public function adminAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');

        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

		return $this->render('admin/dashboard.html.twig');
    } 

    /**   
     * @Route("/users", name="users")
     */
    public function usersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');
        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

        $sql = "
        SELECT
            `u`.`id`,
            `u`.`first`,
            `u`.`last`,
            `u`.`email`,
            `u`.`phone`,
            `u`.`userType`,
            `u`.`active`,
            DATE_FORMAT(`u`.`date`, '%M %d, %Y') AS 'date'
        FROM
            `users` u
        WHERE
            1

        ORDER BY `date` DESC
        ";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $data = "";
        $i = "0";
        while ($row = $result->fetch()) {
            foreach ($row as $key=>$value) {
                $data[$i][$key] = $value;
            }
            $i++;
        }
        return $this->render('admin/users.html.twig',[
            'data' => $data,
        ]);
    } 

    /**   
     * @Route("/edituser", name="edituser")
     */
    public function edituserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');
        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

        $userID = $request->query->get('id');

        $sql = "
        SELECT
            `u`.`id`,
            `u`.`first`,
            `u`.`last`,
            `u`.`phone`,
            `u`.`email`,
            `u`.`userType`,
            `u`.`active`,
            DATE_FORMAT(`u`.`date`,'%m/%d/%Y') AS 'date'
        FROM
            `users` u
        WHERE
            `u`.`id` = '$userID'
        ";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $data = "";
        while ($row = $result->fetch()) {
            foreach ($row as $key=>$value) {
                $data[$key] = $value;
            }
        }

        return $this->render('admin/edituser.html.twig',[
            'data' => $data,
        ]);
    }

    /**   
     * @Route("/updateuser", name="updateuser")
     */
    public function updateuserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');
        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

        $id = $request->request->get('id');
        $first = $request->request->get('first');
        $last = $request->request->get('last');
        $email = $request->request->get('email');
        $phone = $request->request->get('phone');
        $userType = $request->request->get('userType');
        $active = $request->request->get('active');

        $sql = "UPDATE `users` SET
        `first` = '$first',
        `last` = '$last',
        `email` = '$email',
        `phone` = '$phone',
        `userType` = '$userType',
        `active` = '$active'
        WHERE `id` = '$id'
        ";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $this->addFlash('success','The user was updated.');
        return $this->redirectToRoute('users');

    }

    /**   
     * @Route("/deleteuser", name="deleteuser")
     */
    public function deleteuserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');
        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

        $id = $request->query->get('id');
        $sql = "DELETE FROM `users` WHERE `id` = '$id'";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $this->addFlash('success','The user was deleted.');
        return $this->redirectToRoute('users');
    }


    /**   
     * @Route("/favorites", name="favorites")
     */
    public function favoritesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');
        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

        $data = "";

        $sql = "
        SELECT
            `u`.`first`,
            `u`.`last`,
            `u`.`email`,
            `u`.`phone`,
            DATE_FORMAT(`f`.`date_added`, '%m/%d/%Y') AS 'date',
            `p`.`Address`,
            `p`.`City`,
            `p`.`StateOrProvince`,
            `p`.`PostalCode`,
            `f`.`Matrix_Unique_ID`,
            `f`.`id`

        FROM
            `favorites` f

        LEFT JOIN `users` u ON `f`.`userID` = `u`.`id`
        LEFT JOIN `properties` p ON `f`.`Matrix_Unique_ID` = `p`.`Matrix_Unique_ID`

        WHERE
            1

        ORDER BY `f`.`date_added` ASC
        ";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $data = "";
        $i="0";
        while ($row = $result->fetch()) {
            foreach ($row as $key=>$value) {
                $data[$i][$key] = $value;
            }
            $i++;
        }

        return $this->render('admin/favorites.html.twig',[
            'data' => $data,
        ]);
    }

    /**   
     * @Route("/admindeletefavorite", name="admindeletefavorite")
     */
    public function admindeletefavoriteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');
        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

        $id = $request->query->get('id');
        $sql = "DELETE FROM `favorites` WHERE `id` = '$id'";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $this->addFlash('success','The favorite was deleted.');
        return $this->redirectToRoute('favorites');
    }

    /**   
     * @Route("/managesearch", name="managesearch")
     */
    public function managesearchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');
        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

        $data = "";

        $sql = "
        SELECT
            `u`.`first`,
            `u`.`last`,
            `u`.`email`,
            `u`.`phone`,
            DATE_FORMAT(`s`.`date`, '%m/%d/%Y') AS 'date',
            `s`.`link`,
            `s`.`id`

        FROM
            `saved_search` s

        LEFT JOIN `users` u ON `s`.`userID` = `u`.`id`

        WHERE
            1

        ORDER BY `s`.`date` ASC
        ";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $data = "";
        $i="0";
        while ($row = $result->fetch()) {
            foreach ($row as $key=>$value) {
                $data[$i][$key] = $value;
            }
            $i++;
        }

        return $this->render('admin/saved_search.html.twig',[
            'data' => $data,
        ]);
    }

    /**   
     * @Route("/admindeletesearch", name="admindeletesearch")
     */
    public function admindeletesearchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');
        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

        $id = $request->query->get('id');
        $sql = "DELETE FROM `saved_search` WHERE `id` = '$id'";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $this->addFlash('success','The search was deleted.');
        return $this->redirectToRoute('managesearch');
    }


    /**   
     * @Route("/managefeatured", name="managefeatured")
     */
    public function managefeaturedAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');
        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

        $sql = "
        SELECT
            `f`.`id`,
            `f`.`mls`,
            `p`.`Matrix_Unique_ID`,
            `p`.`Address`,
            `p`.`City`,
            `p`.`StateOrProvince`,
            `p`.`PostalCode`,
            DATE_FORMAT(`f`.`date`, '%m/%d/%Y') AS 'date'

        FROM
            `featured` f

        LEFT JOIN `properties` p ON `f`.`mls` = `p`.`MLSNumber`

        WHERE
            1

        ";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $data = "";
        $i="0";
        while ($row = $result->fetch()) {
            foreach ($row as $key=>$value) {
                $data[$i][$key] = $value;
            }
            $i++;
        }

        return $this->render('admin/managefeatured.html.twig',[
            'data' => $data,
        ]);
    }

    /**   
     * @Route("/admindeletefeatured", name="admindeletefeatured")
     */
    public function admindeletefeaturedAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');
        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

        $id = $request->query->get('id');
        $sql = "DELETE FROM `featured` WHERE `id` = '$id'";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $this->addFlash('success','The featured was deleted.');
        return $this->redirectToRoute('managefeatured');
    }

    /**   
     * @Route("/addfeatured", name="addfeatured")
     */
    public function addfeaturedAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('commonservices')->GetSessionData();
        $userType = $session->get('userType');
        $logged = $this->admin_access($userType);
        if ($logged == "no") {
            return $this->redirectToRoute('homepage');
        }

        $mls = $request->request->get('mls');

        $sql = "SELECT `MLSNumber` FROM `properties` WHERE `MLSNumber` = '$mls'";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $found = "0";
        while ($row = $result->fetch()) {
            $found = "1";
        }
        if ($found == "1") {
            $date = date("Ymd");
            $sql = "INSERT INTO `featured` (`mls`,`date`) VALUES ('$mls','$date')";
            $result = $em->getConnection()->prepare($sql);
            $result->execute();
        }

        if ($found == "0") {
            $this->addFlash('danger','The MLS number was not found.');
            return $this->redirectToRoute('managefeatured');            
        } else {
            $this->addFlash('success','The MLS number was added to the featured.');
            return $this->redirectToRoute('managefeatured');             
        }
    }


    private function admin_access($userType) {
        $logged = "no";
        if($userType != "admin") {
            $this->addFlash('danger','Your session has expired or you do not have access. Please log back in.');

        } else {
            $logged = "yes";
        }    	
        return($logged);
    }

}