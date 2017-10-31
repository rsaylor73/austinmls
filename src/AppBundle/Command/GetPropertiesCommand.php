<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetPropertiesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:get_properties')
            ->setDescription('Gets PHRETS properties : parameters city limit (max 3000)')
            ->addArgument('city')
            ->addArgument('limit')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	ini_set('memory_limit', '512M');
		$doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $sql = "";
        $city = $input->getArgument('city');
        $limit = $input->getArgument('limit');

		$output->writeln('Running '.$city.' ...');

		$image_path = $this->getContainer()->getParameter('image_path');
		
        $results = $this->get_properties($city,$limit);
        foreach($results as $obj=>$json) {
            $properties = json_decode($json);

            $sql = "SELECT `Matrix_Unique_ID` FROM `properties` 
            WHERE `Matrix_Unique_ID` = '".$properties->Matrix_Unique_ID."'";
	        $query = $em->getConnection()->prepare($sql);
	        $query->execute(); 
	        $found = "0";
	        while ($row = $query->fetch()) {
	        	$found = "1";
	        }   
	        $date = date("Ymd");       
	        switch ($found) {
	        	case "0": // insert
	        	$address = "";
	        	$address = $properties->Address;
	        	$address = str_replace("'","",$address);

	        	$subname = "";
	        	$subname = $properties->SubdivisionName;
	        	$subname = str_replace("'","",$subname);

	        	$Fence = "";
	        	$Fence = $properties->Fence;
	        	$Fence = str_replace("',"," - ",$Fence);

	        	$Flooring = "";
	        	$Flooring = $properties->Flooring;
	        	$Flooring = str_replace("'"," - ",$Flooring);
	        		     
	        	$sql = "INSERT INTO `properties`
	        	(
	        	`Matrix_Unique_ID`,
	        	`Latitude`,
	        	`Longitude`,
	        	`ListPrice`,
	        	`Address`,
	        	`City`,
	        	`StateOrProvince`,
	        	`PostalCode`,
	        	`NumMainLevelBeds`,
	        	`NumOtherLevelBeds`,
	        	`BathsHalf`,
	        	`NumGuestHalfBaths`,
	        	`NumGuestFullBaths`,
	        	`BathsFull`,
	        	`SqftTotal`,
	        	`LotSizeArea`,
	        	`YearBuilt`,
	        	`CoveredSpaces`,
	        	`SubdivisionName`,
	        	`MLSNumber`,
	        	`date_added`,
	        	`date_updated`,
	        	`Gr9HighSchool`,
	        	`JuniorHighSchool`,
	        	`MiddleIntermediateSchool`,
	        	`SchoolDistrict`,
	        	`SeniorHighSchool`,
	        	`ElementaryA`,
	        	`BedsTotal`,
	        	`BodyofWater`,
	        	`Fence`,
	        	`FireplaceFeatures`,
	        	`Flooring`,
	        	`GarageDescription`,
	        	`IsDeleted`,
	        	`LandSQFT`,
	        	`LastStatus`,
	        	`NumParkingSpaces`,
	        	`OpenHouseUpcoming`,
	        	`OpenHouseDatePublic`,
	        	`OpenHouseTimePublic`,
	        	`ParkingFeatures`,
	        	`PoolonProperty`,
	        	`PropertySubType`,
	        	`PropertyType`,
	        	`WaterAccess`,
	        	`StoriesLookup`
	        	) VALUES (
	        	'$properties->Matrix_Unique_ID',
	        	'$properties->Latitude',
	        	'$properties->Longitude',
	        	'$properties->ListPrice',
	        	'$address',
	        	'$properties->City',
	        	'$properties->StateOrProvince',
	        	'$properties->PostalCode',
	        	'$properties->NumMainLevelBeds',
	        	'$properties->NumOtherLevelBeds',
	        	'$properties->BathsHalf',
	        	'$properties->NumGuestHalfBaths',
	        	'$properties->NumGuestFullBaths',
	        	'$properties->BathsFull',
	        	'$properties->SqftTotal',
	        	'$properties->LotSizeArea',
	        	'$properties->YearBuilt',
	        	'$properties->CoveredSpaces',
	        	'$subname',
	        	'$properties->MLSNumber',
	        	'$date',
	        	'$date',
	        	'$properties->Gr9HighSchool',
	        	'$properties->JuniorHighSchool',
	        	'$properties->MiddleIntermediateSchool',
	        	'$properties->SchoolDistrict',
	        	'$properties->SeniorHighSchool',
	        	'$properties->ElementaryA',
	        	'$properties->BedsTotal',
	        	'$properties->BodyofWater',
	        	'$Fence',
	        	'$properties->FireplaceFeatures',
	        	'$Flooring',
	        	'$properties->GarageDescription',
	        	'$properties->IsDeleted',
	        	'$properties->LandSQFT',
	        	'$properties->LastStatus',
	        	'$properties->NumParkingSpaces',
	        	'$properties->OpenHouseUpcoming',
	        	'$properties->OpenHouseDatePublic',
	        	'$properties->OpenHouseTimePublic',
	        	'$properties->ParkingFeatures',
	        	'$properties->PoolonProperty',
	        	'$properties->PropertySubType',
	        	'$properties->PropertyType',
	        	'$properties->WaterAccess',
	        	'$properties->StoriesLookup'
	        	)
	        	";
				$query = $em->getConnection()->prepare($sql);
	        	$query->execute(); 


	            $data = $this->getAllImage($properties->Matrix_Unique_ID);
	            if(is_array($data)) {
	                foreach ($data as $key=>$value) {
	                    list($type, $value) = explode(';', $value);
	                    if ($type == "data:image/jpeg") {
	                        list(, $value)      = explode(',', $value);
	                        $image = base64_decode($value);
	                        $new_file = $properties->Matrix_Unique_ID .'-' . $counter .'.png';

	                        file_put_contents($image_path . $new_file, $image);

	                        $sql2 = "INSERT INTO `freemls_mls`.`images` 
	                        (`matrixUniqueId`,`sequence`,`image`) 
	                        VALUES
	                        ('$properties->Matrix_Unique_ID','$counter','$new_file')
	                        ";
	                        $result2 = $em->getConnection()->prepare($sql2);
	                        $result2->execute();

	                        $counter++;
	                    }
	                }
	            }

	        	// get images
        	

	        	break;

	        	case "1": // update
				$address = "";
	        	$address = $properties->Address;
	        	$address = str_replace("'","",$address);

	        	$subname = "";
	        	$subname = $properties->SubdivisionName;
	        	$subname = str_replace("'","",$subname);

	        	$Fence = "";
	        	$Fence = $properties->Fence;
	        	$Fence = str_replace("',"," - ",$Fence);

	        	$Flooring = "";
	        	$Flooring = $properties->Flooring;
	        	$Flooring = str_replace("'"," - ",$Flooring);

	        	$sql = "UPDATE `properties` SET
	        	`Latitude` = '$properties->Latitude',
	        	`Longitude` = '$properties->Longitude',
	        	`ListPrice` = '$properties->ListPrice',
	        	`Address` = '$address',
	        	`City` = '$properties->City',
	        	`StateOrProvince` = '$properties->StateOrProvince',
	        	`PostalCode` = '$properties->PostalCode',
	        	`NumMainLevelBeds` = '$properties->NumMainLevelBeds',
	        	`NumOtherLevelBeds` = '$properties->NumOtherLevelBeds',
	        	`BathsHalf` = '$properties->BathsHalf',
	        	`NumGuestHalfBaths` = '$properties->NumGuestHalfBaths',
	        	`NumGuestFullBaths` = '$properties->NumGuestFullBaths',
	        	`BathsFull` = '$properties->BathsFull',
	        	`SqftTotal` = '$properties->SqftTotal',
	        	`LotSizeArea` = '$properties->LotSizeArea',
	        	`YearBuilt` = '$properties->YearBuilt',
	        	`CoveredSpaces` = '$properties->CoveredSpaces',
	        	`SubdivisionName` = '$subname',
	        	`MLSNumber` = '$properties->MLSNumber',
	        	`date_updated` = '$date',
	        	`Gr9HighSchool` = '$properties->Gr9HighSchool',
	        	`JuniorHighSchool` = '$properties->JuniorHighSchool',
	        	`MiddleIntermediateSchool` = '$properties->MiddleIntermediateSchool',
	        	`SchoolDistrict` = '$properties->SchoolDistrict',
	        	`SeniorHighSchool` = '$properties->SeniorHighSchool',
	        	`ElementaryA` = '$properties->ElementaryA',
	        	`BedsTotal` = '$properties->BedsTotal',
	        	`BodyofWater` = '$properties->BodyofWater',
	        	`Fence` = '$Fence',
	        	`FireplaceFeatures` = '$properties->FireplaceFeatures',
	        	`Flooring` = '$Flooring',
	        	`GarageDescription` = '$properties->GarageDescription',
	        	`IsDeleted` = '$properties->IsDeleted',
	        	`LandSQFT` = '$properties->LandSQFT',
	        	`LastStatus` = '$properties->LastStatus',
	        	`NumParkingSpaces` = '$properties->NumParkingSpaces',
	        	`OpenHouseUpcoming` = '$properties->OpenHouseUpcoming',
	        	`OpenHouseDatePublic` = '$properties->OpenHouseDatePublic',
	        	`OpenHouseTimePublic` = '$properties->OpenHouseTimePublic',
	        	`ParkingFeatures` = '$properties->ParkingFeatures',
	        	`PoolonProperty` = '$properties->PoolonProperty',
	        	`PropertySubType` = '$properties->PropertySubType',
	        	`PropertyType` = '$properties->PropertyType',
	        	`WaterAccess` = '$properties->WaterAccess',
	        	`StoriesLookup` = '$properties->StoriesLookup'

	        	WHERE `Matrix_Unique_ID` = '$properties->Matrix_Unique_ID'
	        	";
				$query = $em->getConnection()->prepare($sql);
	        	$query->execute(); 
	        	break;
	        } 

        }
    }

    public function get_properties($city,$limit) {
        // init
        $img = "";
        $images = "";

        // get RETS
        $RETS_URL = $this->getContainer()->getParameter('RETS_URL');
        $RETS_USERNAME = $this->getContainer()->getParameter('RETS_USERNAME');
        $RETS_PASSWORD = $this->getContainer()->getParameter('RETS_PASSWORD');
        $RETS_VERSION = $this->getContainer()->getParameter('RETS_VERSION');
        $RETS_AGENT = $this->getContainer()->getParameter('RETS_AGENT');

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

        $results = $rets->Search("Property", "RESI", "(city=|$city)",
                array(
                    "Limit" => $limit, 
                    "Select" => "Matrix_Unique_ID,Latitude,Longitude,ListPrice,Address,City,StateOrProvince,PostalCode,NumMainLevelBeds,NumOtherLevelBeds,BathsHalf,NumGuestHalfBaths,NumGuestFullBaths,BathsFull,SqftTotal,LotSizeArea,YearBuilt,CoveredSpaces,SubdivisionName,MLSNumber,GarageDescription,Latitude,Longitude,Status,Gr9HighSchool,JuniorHighSchool,MiddleIntermediateSchool,SchoolDistrict,SeniorHighSchool,ElementaryA,BedsTotal,BodyofWater,Fence,FireplaceFeatures,Flooring,GarageDescription,IsDeleted,LandSQFT,LastStatus,NumParkingSpaces,OpenHouseUpcoming,OpenHouseDatePublic,OpenHouseTimePublic,ParkingFeatures,PoolonProperty,PropertySubType,PropertyType,WaterAccess,StoriesLookup"
                )); 
        return($results);           	
    }


    public function getAllImage($matrixUniqueId) {

        // init
        $contentType = "";
        $img = "";
        $data = "";
        $count = "";


        // get RETS
        $RETS_URL = $this->getContainer()->getParameter('RETS_URL');
        $RETS_USERNAME = $this->getContainer()->getParameter('RETS_USERNAME');
        $RETS_PASSWORD = $this->getContainer()->getParameter('RETS_PASSWORD');
        $RETS_VERSION = $this->getContainer()->getParameter('RETS_VERSION');
        $RETS_AGENT = $this->getContainer()->getParameter('RETS_AGENT');


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