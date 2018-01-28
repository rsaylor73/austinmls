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
        $this->admin_access($userType);

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
        $this->admin_access($userType);

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
        $this->admin_access($userType);

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
        $this->admin_access($userType);

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
        $this->admin_access($userType);
        $id = $request->query->get('id');
        $sql = "DELETE FROM `users` WHERE `id` = '$id'";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $this->addFlash('success','The user was deleted.');
        return $this->redirectToRoute('users');
    }

    private function admin_access($userType) {
        if($userType != "admin") {
            $this->addFlash('danger','Your session has expired or you do not have access. Please log back in.');
            return $this->redirectToRoute('homepage');
        }    	
    }

}