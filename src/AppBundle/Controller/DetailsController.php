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

        if ($id == "") {
        	$id = $request->query->get('id');
        }

        $session = $this->get('commonservices')->GetSessionData();
        $userID = $session->get('id');
        $counter = "0";
        if ($userID == "") {
        	$counter = $session->get('counter');
        	$counter++;
        	$session->set('counter',$counter);
        	if ($counter > 3) {
        		return $this->redirectToRoute('warning');
        	}
        }

        $sql = "
        SELECT `Matrix_Unique_ID`,`Latitude`,`Longitude`,`ListPrice`,`Address`,
        `City`,`StateOrProvince`,`PostalCode`,`NumMainLevelBeds`,`NumOtherLevelBeds`,
        `BathsHalf`,`NumGuestHalfBaths`,`NumGuestFullBaths`,`BathsFull`,`SqftTotal`,
        `LotSizeArea`,`YearBuilt`,`CoveredSpaces`,`SubdivisionName`,`MLSNumber`,`BedsTotal`,
		`PropertyType`,`PropertySubType`,`YearBuilt`,`StoriesLookup`,`WaterAccess`,`PoolonProperty`,
		`MLSNumber`,`SubdivisionName`,`WaterAccess`,`BodyofWater`,`GarageDescription`,`Fence`,
		`ParkingFeatures`,`SchoolDistrict`,`ElementaryA`,`MiddleIntermediateSchool`,
		`SeniorHighSchool`,`SpaHotTubYN`,`ForeclosureREO`,`CountyOrParish`,`Area`,`Roof`,
		`FoundationDetails`,`Construction`,`BuilderName`,`LotFeatures`,`View`,`Faces`,`Waterfront`,
		`MasterMain`,`NumLiving`,`DiningDescription`,`Rooms`,`NumFireplaces`,`MasterDescription`,
		`Flooring`,`LaundryLocation`,`LaundryFacilities`,`KitchenAppliances`,`InteriorFeatures`,
		`DisabilityFeatures`,`Steps`,`ExteriorFeatures`,`GatedCommunity`,`AreaAmenities`,
		`HOAName`,`AssociationFee`,`TaxAmount`,`TaxRate`,`Utilities`,`TaxYear`,`MasterDescription`,
		`ActualTax`,`Heating`,`WaterSource`,`Sewer`,`AC`,
		DATE_FORMAT(`ListingContractDate`,'%Y%m%d') AS 'ListingContractDate'

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
			$SpaHotTubYN = $property['SpaHotTubYN'];	
			$ForeclosureREO = $property['ForeclosureREO'];
			$CountyOrParish = $property['CountyOrParish'];
			$Area = $property['Area'];
			$Roof = $property['Roof'];
			$FoundationDetails = $property['FoundationDetails'];
			$Construction = $property['Construction'];
			$BuilderName = $property['BuilderName'];
			$LotFeatures = $property['LotFeatures'];
			$View = $property['View'];
			$Faces = $property['Faces'];
			$Waterfront = $property['Waterfront'];
			$MasterMain = $property['MasterMain'];
			$NumLiving = $property['NumLiving'];
			$DiningDescription = $property['DiningDescription'];
			$Rooms = $property['Rooms'];
			$NumFireplaces = $property['NumFireplaces'];
			$MasterDescription = $property['MasterDescription'];
			$Flooring = $property['Flooring'];
			$LaundryLocation = $property['LaundryLocation'];
			$LaundryFacilities = $property['LaundryFacilities'];
			$KitchenAppliances = $property['KitchenAppliances'];
			$InteriorFeatures = $property['InteriorFeatures'];
			$DisabilityFeatures = $property['DisabilityFeatures'];
			$Steps = $property['Steps'];
			$ExteriorFeatures = $property['ExteriorFeatures'];
			$GatedCommunity = $property['GatedCommunity'];
			$AreaAmenities = $property['AreaAmenities'];
			$HOAName = $property['HOAName'];
			$AssociationFee = $property['AssociationFee'];
			$TaxAmount = $property['TaxAmount'];
			$TaxRate = $property['TaxRate'];
			$Utilities = $property['Utilities'];
			$TaxYear = $property['TaxYear'];
			$MasterDescription = $property['MasterDescription'];
			$ActualTax = $property['ActualTax'];
			$ListingContractDate = $property['ListingContractDate'];
			$Heating = $property['Heating'];
			$WaterSource = $property['WaterSource'];
			$Sewer = $property['Sewer'];
			$AC = $property['AC'];

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

		// To Do: get number of days from
		// ListingContractDate

		$now = date("Y-m-d");
		$ListingContractDate = date("Y-m-d", strtotime($ListingContractDate));

		$date1 = date_create($ListingContractDate);
		$date2 = date_create($now);
		$diff = date_diff($date1,$date2);
		$days = $diff->format("%a");
		$today_date = date("m/d/Y");

		// Similar Properties
		$simData = $this->get('commonservices')->GetSimilar($id);

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
			'SpaHotTubYN' => $SpaHotTubYN,
			'ForeclosureREO' => $ForeclosureREO,
			'CountyOrParish' => $CountyOrParish,
			'Area' => $Area,
			'Roof' => $Roof,
			'FoundationDetails' => $FoundationDetails,
			'Construction' => $Construction,
			'BuilderName' => $BuilderName,
			'LotFeatures' => $LotFeatures,
			'View' => $View,
			'Faces' => $Faces,
			'Waterfront' => $Waterfront,
			'MasterMain' => $MasterMain,
			'NumLiving' => $NumLiving,
			'DiningDescription' => $DiningDescription,
			'Rooms' => $Rooms,
			'NumFireplaces' => $NumFireplaces,
			'MasterDescription' => $MasterDescription,
			'Flooring' => $Flooring,
			'LaundryLocation' => $LaundryLocation,
			'LaundryFacilities' => $LaundryFacilities,
			'KitchenAppliances' => $KitchenAppliances,
			'InteriorFeatures' => $InteriorFeatures,
			'DisabilityFeatures' => $DisabilityFeatures,
			'Steps' => $Steps,
			'ExteriorFeatures' => $ExteriorFeatures,
			'GatedCommunity' => $GatedCommunity,
			'AreaAmenities' => $AreaAmenities,
 			'HOAName' => $HOAName,
 			'AssociationFee' => $AssociationFee,
 			'TaxAmount' => $TaxAmount,
 			'TaxRate' => $TaxRate,
 			'Utilities' => $Utilities,
 			'TaxYear' => $TaxYear,
 			'MasterDescription' => $MasterDescription,
 			'ActualTax' => $ActualTax,
 			'Heating' => $Heating,
 			'WaterSource' => $WaterSource,
 			'Sewer' => $Sewer,
 			'AC' => $AC,
 			'days' => $days,
 			'today_date' => $today_date,
 			'simData' => $simData
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
