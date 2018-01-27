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


    private function admin_access($userType) {
        if($userType != "admin") {
            $this->addFlash('danger','Your session has expired or you do not have access. Please log back in.');
            return $this->redirectToRoute('homepage');
        }    	
    }

}