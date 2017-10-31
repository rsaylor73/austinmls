<?php
/* This is a service class */
namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Properties {

    private $em;
    private $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    public function get_properties($city,$limit) {
        // init
        $img = "";
        $images = "";

        // get RETS
        $RETS_URL = $this->container->getParameter('RETS_URL');
        $RETS_USERNAME = $this->container->getParameter('RETS_USERNAME');
        $RETS_PASSWORD = $this->container->getParameter('RETS_PASSWORD');
        $RETS_VERSION = $this->container->getParameter('RETS_VERSION');
        $RETS_AGENT = $this->container->getParameter('RETS_AGENT');

        $config = new \PHRETS\Configuration;
        
        $config
            ->setLoginUrl($RETS_URL)
            ->setUsername($RETS_USERNAME)
            ->setPassword($RETS_PASSWORD)
            ->setRetsVersion($RETS_VERSION)
            ->setUserAgent($RETS_AGENT)
        ;

        $rets = new \PHRETS\Session($config);
        $connect = $rets->Login();

        $system = $rets->GetSystemMetadata();

        $city = "Austin,Kyle,Lakeway,Cedar Park,Pflugerville,Round Rock";
        $limit = "150";

        $results = $rets->Search("Property", "RESI", "(city=|$city)",
                array(
                    "Limit" => $limit, 
                    "Select" => "Matrix_Unique_ID,Latitude,Longitude,ListPrice,Address,City,StateOrProvince,PostalCode,NumMainLevelBeds,NumOtherLevelBeds,BathsHalf,NumGuestHalfBaths,NumGuestFullBaths,BathsFull,SqftTotal,LotSizeArea,YearBuilt,CoveredSpaces,SubdivisionName,MLSNumber,GarageDescription,Latitude,Longitude"
                )); 
        return($results);           	
    }

    public function property_details($Matrix_Unique_ID) {
        // init
        $img = "";
        $images = "";

        // get RETS
        $RETS_URL = $this->container->getParameter('RETS_URL');
        $RETS_USERNAME = $this->container->getParameter('RETS_USERNAME');
        $RETS_PASSWORD = $this->container->getParameter('RETS_PASSWORD');
        $RETS_VERSION = $this->container->getParameter('RETS_VERSION');
        $RETS_AGENT = $this->container->getParameter('RETS_AGENT');

        $config = new \PHRETS\Configuration;
        
        $config
            ->setLoginUrl($RETS_URL)
            ->setUsername($RETS_USERNAME)
            ->setPassword($RETS_PASSWORD)
            ->setRetsVersion($RETS_VERSION)
            ->setUserAgent($RETS_AGENT)
        ;

        $rets = new \PHRETS\Session($config);
        $connect = $rets->Login();

        $system = $rets->GetSystemMetadata();

        $limit = "1";

        $results = $rets->Search("Property", "resi", "(Matrix_Unique_ID=$Matrix_Unique_ID)",
                array("Limit" => $limit, "Select" => "Matrix_Unique_ID,Latitude,Longitude,ListPrice,Address,City,StateOrProvince,PostalCode,NumMainLevelBeds,NumOtherLevelBeds,BathsHalf,NumGuestHalfBaths,NumGuestFullBaths,BathsFull,SqftTotal,LotSizeArea,YearBuilt,CoveredSpaces,SubdivisionName,MLSNumber,GarageDescription")); 
        return($results);               
    }

    public function getMainImageOLD($matrixUniqueId='14398008',$images='1') {

        // init
        $contentType = "";
        $img = "";
        $data = "";
        $count = "";


        // get RETS
        $RETS_URL = $this->container->getParameter('RETS_URL');
        $RETS_USERNAME = $this->container->getParameter('RETS_USERNAME');
        $RETS_PASSWORD = $this->container->getParameter('RETS_PASSWORD');
        $RETS_VERSION = $this->container->getParameter('RETS_VERSION');
        $RETS_AGENT = $this->container->getParameter('RETS_AGENT');

        $config = new \PHRETS\Configuration;
        
        $config
            ->setLoginUrl($RETS_URL)
            ->setUsername($RETS_USERNAME)
            ->setPassword($RETS_PASSWORD)
            ->setRetsVersion($RETS_VERSION)
            ->setUserAgent($RETS_AGENT)
        ;

        $rets = new \PHRETS\Session($config);
        $connect = $rets->Login();

        $photos = $rets->GetObject("Property", "Photo", $matrixUniqueId, "1", 0);
        $data = array();
        $count = count($photos);
        for ($x=0; $x < $count; $x++) {
            $contentType = $photos[$x]->getContentType();
            //print "C $x $contentType<br>";
            $img = base64_encode($photos[$x]->getContent());
            $data[$x] = "data:{$contentType};base64,{$img}";
            //echo "<img src='data:{$contentType};base64,{$img}' /><br>";
        }
        return($data);

    }

    public function getAllImage($matrixUniqueId) {

        // init
        $contentType = "";
        $img = "";
        $data = "";
        $count = "";


        // get RETS
        $RETS_URL = $this->container->getParameter('RETS_URL');
        $RETS_USERNAME = $this->container->getParameter('RETS_USERNAME');
        $RETS_PASSWORD = $this->container->getParameter('RETS_PASSWORD');
        $RETS_VERSION = $this->container->getParameter('RETS_VERSION');
        $RETS_AGENT = $this->container->getParameter('RETS_AGENT');

        $config = new \PHRETS\Configuration;
        
        $config
            ->setLoginUrl($RETS_URL)
            ->setUsername($RETS_USERNAME)
            ->setPassword($RETS_PASSWORD)
            ->setRetsVersion($RETS_VERSION)
            ->setUserAgent($RETS_AGENT)
        ;

        $rets = new \PHRETS\Session($config);
        $connect = $rets->Login();

        $photos = $rets->GetObject("Property", "Photo", $matrixUniqueId, "*", 0);
        $data = array();
        $count = count($photos);

        for ($x=0; $x < $count; $x++) {
            $contentType = $photos[$x]->getContentType();
            //print "C $x $contentType<br>";
            $img = base64_encode($photos[$x]->getContent());
            $data[$x] = "data:{$contentType};base64,{$img}";
            //echo "<img src='data:{$contentType};base64,{$img}' /><br>";
        }
        return($data);

    }
}