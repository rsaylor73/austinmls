<?php
/* This is a service class */
namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class commonservices {

    protected $em;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager,  ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    public function GetSessionData() {
        $session = new Session();
        //$session->start();
        return($session);
    }

    public function GetSimilar($id) {

    	$em = $this->em;

    	$data = array();

    	$sql = "
    	SELECT
    		`City`,
    		`StateOrProvince`,
    		`PostalCode`,
    		`NumMainLevelBeds`,
    		`NumOtherLevelBeds`,
    		`BathsHalf`,
    		`SqftTotal`,
    		`BedsTotal`,
    		`ListPrice`

    	FROM
    		`properties`

    	WHERE
    		`Matrix_Unique_ID` = '$id'
    	";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        
        while ($row = $result->fetch()) {
            $City = $row['City'];
            $StateOrProvince = $row['StateOrProvince'];
            $PostalCode = $row['PostalCode'];
            $NumMainLevelBeds = $row['NumMainLevelBeds'];
            $BathsHalf = $row['BathsHalf'];
            $SqftTotal = $row['SqftTotal'];
            $BedsTotal = $row['BedsTotal'];
            $ListPrice = $row['ListPrice'];
        }

        $range1 = $SqftTotal - 500;
        $range2 = $SqftTotal + 500;

        $range3 = $ListPrice - 10000;
        $range4 = $ListPrice + 10000;

        $sql = "
        SELECT
            `Matrix_Unique_ID`,
            `Address`,
            `City`,
            `StateOrProvince`,
            `PostalCode`,
            `NumMainLevelBeds`,
            `NumOtherLevelBeds`,
            `BathsHalf`,
            `SqftTotal`,
            `BedsTotal`,
            `ListPrice`,
            `NumMainLevelBeds` + `NumOtherLevelBeds` AS 'beds',
            `BathsHalf` + `NumGuestHalfBaths` AS 'halfbath',
            `BathsFull` + `NumGuestFullBaths` AS 'bath',
            `SqftTotal`,
            `LotSizeArea`,
            `YearBuilt`,
            `SubdivisionName`
        FROM
            `properties`

        WHERE
            `City` LIKE '%$City%'
            AND `StateOrProvince` = '$StateOrProvince'
            AND `SqftTotal` BETWEEN '$range1' AND '$range2'
            AND `ListPrice` BETWEEN '$range3' AND '$range4'

        LIMIT 10
        ";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $i = "0";
        while ($row = $result->fetch()) {
            foreach ($row as $key=>$value) {
                $data[$i][$key] = $value;
            }

            // images
            $sql2 = "
            SELECT `image`,`sequence` 
            FROM `images` 
            WHERE `matrixUniqueId` = '$row[Matrix_Unique_ID]'
            ORDER BY `sequence` ASC
            ";

            $result2 = $em->getConnection()->prepare($sql2);
            $result2->execute();
            $sequence = "";
            while ($row2 = $result2->fetch()) {
                $sequence = $row2['sequence'];
                $data[$i]['images'][$sequence] = $row2['image'];
            }
            if ($sequence == "") {
                $data[$i]['images'][0] = "";
            }

            $i++;
        }

    	return($data);

    }

}