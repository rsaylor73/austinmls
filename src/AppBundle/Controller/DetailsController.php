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
     * @Route("/details", name="details")
     * @Route("/details/{id}")
     */
    public function detailsAction(Request $request, $id='')
    {
        $em = $this->getDoctrine()->getManager();

        $sql = "
        SELECT `Matrix_Unique_ID`,`Latitude`,`Longitude`,`ListPrice`,`Address`,
        `City`,`StateOrProvince`,`PostalCode`,`NumMainLevelBeds`,`NumOtherLevelBeds`,
        `BathsHalf`,`NumGuestHalfBaths`,`NumGuestFullBaths`,`BathsFull`,`SqftTotal`,
        `LotSizeArea`,`YearBuilt`,`CoveredSpaces`,`SubdivisionName`,`MLSNumber`,`BedsTotal`,
		`PropertyType`,`PropertySubType`,`YearBuilt`,`StoriesLookup`,`WaterAccess`,`PoolonProperty`,
		`MLSNumber`,`SubdivisionName`,`WaterAccess`,`BodyofWater`,`GarageDescription`,`Fence`,
		`ParkingFeatures`,`SchoolDistrict`,`ElementaryA`,`MiddleIntermediateSchool`,
		`SeniorHighSchool`

        FROM
            `properties`

        WHERE
            1
	    AND `Matrix_Unique_ID` = '$id'
		";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        while ($property = $result->fetch()) {
            $Matrix_Unique_ID = $property['Matrix_Unique_ID'];
            $ListPrice = $property['ListPrice'];
            $Address = $property['Address'];
            $City = $property['City'];
            $StateOrProvince = $property['StateOrProvince'];
            $PostalCode = $property['PostalCode'];
            $beds = $property['NumMainLevelBeds'] + $property['NumOtherLevelBeds'];
            $half_bath = $property['BathsHalf'] + $property['NumGuestHalfBaths'];
            $full_bath = $property['BathsFull'] + $property['NumGuestFullBaths'];
            $SqftTotal = $property['SqftTotal'];
            $LotSizeArea = $property['LotSizeArea'];
            $YearBuilt = $property['YearBuilt'];
            $SubdivisionName = $property['SubdivisionName'];
            $images = $this->getMainImage($Matrix_Unique_ID,$em);
            $Latitude = $property['Latitude'];
            $Longitude = $property['Longitude'];
			$PropertyType = $property['PropertyType'];
			$PropertySubType = $property['PropertySubType'];
			$YearBuilt = $property['YearBuilt'];
			$StoriesLookup = $property['StoriesLookup'];
			$WaterAccess = $property['WaterAccess'];
			$PoolonProperty = $property['PoolonProperty'];
			$MLSNumber = $property['MLSNumber'];
			$SubdivisionName = $property['SubdivisionName'];
			$WaterAccess = $property['WaterAccess'];
			$BodyofWater = $property['BodyofWater'];
			$NumMainLevelBeds = $property['NumMainLevelBeds'];
			$NumOtherLevelBeds = $property['NumOtherLevelBeds'];
			$BathsFull = $property['BathsFull'];
			$BathsHalf = $property['BathsHalf'];
			$GarageDescription = $property['GarageDescription'];
			$Fence = $property['Fence'];
			$ParkingFeatures = $property['ParkingFeatures'];
			$SchoolDistrict = $property['SchoolDistrict'];
			$ElementaryA = $property['ElementaryA'];
			$MiddleIntermediateSchool = $property['MiddleIntermediateSchool'];
			$SeniorHighSchool = $property['SeniorHighSchool'];

			// images
            $sql2 = "
            SELECT `image`,`sequence` 
            FROM `images` 
            WHERE `matrixUniqueId` = '$property[Matrix_Unique_ID]'
            ORDER BY `sequence` ASC
            ";
			$data = "";
			$i = "0";
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
		}

		if ($StoriesLookup == "0") {
			$StoriesLookup = "1";
		}

		if ($PoolonProperty == "1") {
			$PoolonProperty = 'Yes';
		} else {
			$PoolonProperty = 'No';
		}

		if ($WaterAccess == "0") {
			$WaterAccess = "No";
		} else {
			$WaterAccess = "Yes";
		}

		if ($BodyofWater == "") {
			$BodyofWater = "None";
		}

		if ($GarageDescription == "") {
			$GarageDescription = "None";
		}

		$price_per_sqf = floor($ListPrice / $SqftTotal);

        return $this->render('details/index.html.twig',[
			'Matrix_Unique_ID' => $Matrix_Unique_ID,
			'images' => $images,
			'ListPrice' => $ListPrice,
			'Address' => $Address,
			'City' => $City,
			'StateOrProvince' => $StateOrProvince,
			'PostalCode' => $PostalCode,
			'beds' => $beds,
			'half_bath' => $half_bath,
			'full_bath' => $full_bath,
			'SqftTotal' => $SqftTotal,
			'LotSizeArea' => $LotSizeArea,
			'YearBuilt' => $YearBuilt,
			'SubdivisionName' => $SubdivisionName,
			'PropertyType' => $PropertyType,
			'PropertySubType' => $PropertySubType,
			'YearBuilt' => $YearBuilt,
			'StoriesLookup' => $StoriesLookup,
			'WaterAccess' => $WaterAccess,
			'PoolonProperty' => $PoolonProperty,
			'MLSNumber' => $MLSNumber,
			'SubdivisionName' => $SubdivisionName,
			'WaterAccess' => $WaterAccess,
			'BodyofWater' => $BodyofWater,
			'NumMainLevelBeds' => $NumMainLevelBeds,
			'NumOtherLevelBeds' => $NumOtherLevelBeds,
			'BathsFull' => $BathsFull,
			'BathsHalf' => $BathsHalf,
			'property' => $data,
			'GarageDescription' => $GarageDescription,
			'Fence' => $Fence,
			'ParkingFeatures' => $ParkingFeatures,
			'SchoolDistrict' => $SchoolDistrict,
			'ElementaryA' => $ElementaryA,
			'MiddleIntermediateSchool' => $MiddleIntermediateSchool,
			'SeniorHighSchool' => $SeniorHighSchool,
			'price_per_sqf' => $price_per_sqf,
		]);
    }


    public function getMainImage($matrixUniqueId,$em) {
        $image = "";
        $sql = "SELECT `image` FROM `images` WHERE `matrixUniqueId` = '$matrixUniqueId' ORDER BY `sequence` ASC LIMIT 1";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        while ($row = $result->fetch()) {
            $image = $row['image'];
        }
        return($image);
    }


}
