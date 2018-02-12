<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\Properties; // Service Class
use AppBundle\Service\Pageinate;

class HomeController extends Controller
{

    /**
     * @Route("/unittest", name="unittest")
    */
    public function unittestAction(Request $request,Properties $Properties) {
        $em = $this->getDoctrine()->getManager();
        $response = 'test';

        $sql = "SELECT `Matrix_Unique_ID` FROM `properties`";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        while ($row = $result->fetch()) {
            $id = $row['Matrix_Unique_ID'];
            //print "ID: $id<br>";

            $counter = "0";
            $data = $Properties->getAllImage($id);
            if(is_array($data)) {
                foreach ($data as $key=>$value) {
                    print "V: $value<Br><br>";

                    list($type, $value) = explode(';', $value);
                    if ($type == "data:image/jpeg") {
                        print "Type: $type<br>";
                        list(, $value)      = explode(',', $value);
                        $image = base64_decode($value);
                        $new_file = $id .'-' . $counter .'.png';

                        file_put_contents('/home/freemls/www/symfony/mls/web/properties/' . $new_file, $image);

                        $sql2 = "INSERT INTO `freemls_mls`.`images` 
                        (`matrixUniqueId`,`sequence`,`image`) 
                        VALUES
                        ('$id','$counter','$new_file')
                        ";
                        $result2 = $em->getConnection()->prepare($sql2);
                        $result2->execute();

                        $counter++;
                    }
                }
            }
        }


        return $this->render('api/api.html.twig',[
            'response' => $response,
        ]);
    }


    /**
     * @Route("/", name="homepage")
     * @Route("/list-map/{page}/{next}", name="list-map")
     * @Route("/gallery-map/{page}/{next}", name="gallery-map")     
     * @Route("/gallery/{page}/{next}", name="gallery")

     */
    public function homeAction(Request $request,Properties $Properties,Pageinate $Pageinate,$page='1',$next='0')
    {
        $em = $this->getDoctrine()->getManager();
        $type = "";


        $routeName = $request->get('_route');

        // init
        $img = "";
        $images = "";

        // main page listings

        $date2 = date("Ymd");
        $date1 = date("Ymd", strtotime('-7 day'));

        // POST Params
        $property_search = $request->query->get('property_search');
        $bedmin = $request->query->get('bedmin');
        $bedmax = $request->query->get('bedmax');
        $bathmin = $request->query->get('bathmin');
        $bathmax = $request->query->get('bathmax');
        $sqfmin = $request->query->get('sqfmin');
        $sqfmax = $request->query->get('sqfmax');
        $storiesmin = $request->query->get('storiesmin');
        $storiesmax = $request->query->get('storiesmax');
        $garagemin = $request->query->get('garagemin');
        $garagemax = $request->query->get('garagemax');
        $acresmin = $request->query->get('acresmin');
        $acresmax = $request->query->get('acresmin');
        $choose1 = $request->query->get('choose1'); // master on main
        $choose2 = $request->query->get('choose2'); // Pool
        $choose3 = $request->query->get('choose3'); // Waterfront
        $choose4 = $request->query->get('choose4'); // Fireplace
        $choose5 = $request->query->get('choose5'); // Disabilities
        $choose6 = $request->query->get('choose6'); // Gated Community
        $choose7 = $request->query->get('choose7'); // HOA
        $minRange = $request->query->get('minRange'); // Price low ??
        $maxRange = $request->query->get('maxRange');
        $city = $request->query->get('city'); // City - Array
        $zip = $request->query->get('zip');
        $neighborhod = $request->query->get('neighborhod');
        $county = $request->query->get('county');
        $yearmin = $request->query->get('yearmin');
        $yearmax = $request->query->get('yearmax');
        $streetnumber = $request->query->get('streetnumber');
        $streetname = $request->query->get('streetname');
        $school = $request->query->get('school');
        $es = $request->query->get('es'); // Elementry
        $ms = $request->query->get('ms'); // Middle
        $hs = $request->query->get('hs'); // High
        $daysmin = $request->query->get('daysmin');
        $daysmax = $request->query->get('daysmax');
        $view = $request->query->get('view'); // multiple array
        $amenities = $request->query->get('amenities');
        $lot = $request->query->get('lot');
        $access_type = $request->query->get('access_type');
        $waterfront_desc = $request->query->get('waterfront_desc');
        $body_water = $request->query->get('body_water');

        // LastStatus (checkbox = checked)
        $comming_soon = $request->query->get('comming_soon');
        $active = $request->query->get('active');
        $active_contingent = $request->query->get('active_contingent');
        $pending = $request->query->get('pending');
        $pending_backups = $request->query->get('pending_backups');
        $open_house = $request->query->get('open_house');

        // PropertySubType (checkbox = checked)
        $house = $request->query->get('house');
        $condo = $request->query->get('condo');
        $townhouse = $request->query->get('townhouse');
        $modular = $request->query->get('modular');
        $mobile = $request->query->get('mobile');
        $manufactured = $request->query->get('manufactured');
        $duplex = $request->query->get('duplex');

        $date1s = $request->query->get('date1');
        $date2s = $request->query->get('date2');

        $params = "";
        $params = "&property_search=$property_search&bedmin=$bedmin&bedmax=$bedmax&bathmin=$bathmin&bathmax=$bathmax&sqfmin=$sqfmin&sqfmax=$sqfmax&storiesmin=$storiesmin&storiesmax=$storiesmax&garagemin=$garagemin&garagemax=$garagemax&acresmin=$acresmin&acresmax=$acresmax&choose1=$choose1&choose2=$choose2&choose3=$choose3&choose4=$choose4&choose5=$choose5&choose6=$choose6&choose7=$choose7&minRange=$minRange&maxRange=$maxRange&city=$city&zip=$zip&neighborhod=$neighborhod&county=$county&yearmin=$yearmin&yearmax=$yearmax&streetnumber=$streetnumber&streetname=$streetname&school=$school&es=$es&ms=$ms&hs=$hs&daysmin=$daysmin&daysmax=$daysmax&view=$view&amenities=$amenities&lot=$lot&access_type=$access_type&waterfront_desc=$waterfront_desc&body_water=$body_water&comming_soon=$comming_soon&active=$active&active_contingent=$active_contingent&pending=$pending&pending_backups=$pending_backups&open_house=$open_house&house=$house&condo=$condo&townhouse=$townhouse&modular=$modular&mobile=$mobile&manufactured=$manufactured&duplex=$duplex&date1s=$date1s&date2s=$date2s";

        // search queries
        $date_sql = "AND `date_updated` BETWEEN '$date1' AND '$date2'";
        
        $general_search = ""; $bed_sql = ""; $bath_sql = ""; $sqf_sql = ""; $stories_sql = "";
        $acres_sql = ""; $price_sql = ""; $LastStatus_in = ""; $LastStatus_sql = ""; $list = "";
        $PropertySubType_in = ""; $list2 = ""; $PropertySubType_sql = "";

        if ($property_search != "") {
            $general_search = "AND CONCAT(`Address`,`SubdivisionName`,`PostalCode`,`City`,`MLSNumber`) LIKE '%$property_search%'";
        }

        if (($bedmin != "") && ($bedmax != "")) {
            $bed_sql = "AND `BedsTotal` BETWEEN '$bedmin' AND '$bedmax'";
        }

        if (($bathmin != "") && ($bathmax != "")) {
            $bath_sql = "AND `BathsFull` BETWEEN '$bathmin' AND '$bathmax'";
        }

        if (($sqfmin != "") && ($sqfmax != "")) {
            $sqf_sql = "AND `SqftTotal` BETWEEN '$sqfmin' AND '$sqfmax'";
        }

        if (($storiesmin != "") && ($storiesmax != "")) {
            $stories_sql = "AND `StoriesLookup` BETWEEN '$storiesmin' AND '$storiesmax'";
        }

        if (($acresmin != "") && ($acresmax != "")) {
            $acres_sql = "AND `LotSizeArea` BETWEEN '$acresmin' AND '$acresmax'";
        }

        if (($minRange != "") && ($maxRange != "")) {
            $price_sql = "AND `ListPrice` BETWEEN '$minRange' AND '$maxRange'";
        }

        if ($comming_soon != "") {
           $LastStatus_in[] = "Comming Soon"; 
        }
        if ($active != "") {
            $LastStatus_in[] = "Active";
        }
        if ($active_contingent != "") {
            $LastStatus_in[] = "Active Contingent";
        }
        if ($pending != "") {
            $LastStatus_in[] = "Pending";
        }
        if ($pending_backups != "") {
            $LastStatus_in[] = "Pending - Taking Backups";
        }
        if ($open_house != "") {
            $LastStatus_in[] = "Open House Only";
        }
        if(is_array($LastStatus_in)) {
            foreach ($LastStatus_in as $key=>$value) {
                $list .= "'$value',";
            }
            $list = substr($list,0,-1);
            $LastStatus_sql = "AND `LastStatus` IN ($list)";
        }

        if ($house != "") {
            $PropertySubType_in[] = "House";
        }
        if ($condo != "") {
            $PropertySubType_in[] = "Condo";
        }
        if ($townhouse != "") {
            $PropertySubType_in[] = "Townhouse";
        }
        if ($modular != "") {
            $PropertySubType_in[] = "Modular Home";
        }
        if ($mobile != "") {
            $PropertySubType_in[] = "Mobile Home";
        }
        if ($manufactured != "") {
            $PropertySubType_in[] = "Manufactured Home";
        }
        if ($duplex != "") {
            $PropertySubType_in[] = "Attached 1/2 Duplex";
        }
        if(is_array($PropertySubType_in)) {
            foreach ($PropertySubType_in as $key=>$value) {
                $list2 .= "'$value',";
            }
            $list2 = substr($list2,0,-1);
            $PropertySubType_sql = "AND `LastStatus` IN ($list2)";
        }

        if (($date1s != "") && ($date2s != "")) {
            $date1s = date("Ymd", strtotime($date1s));
            $date2s = date("Ymd", strtotime($date2s));
            $date_sql = "AND `date_updated` BETWEEN '$date1s' AND '$date2s'";
        }

        $sql = "
        SELECT `Matrix_Unique_ID`,`Latitude`,`Longitude`,`ListPrice`,`Address`,
        `City`,`StateOrProvince`,`PostalCode`,`NumMainLevelBeds`,`NumOtherLevelBeds`,
        `BathsHalf`,`NumGuestHalfBaths`,`NumGuestFullBaths`,`BathsFull`,`SqftTotal`,
        `LotSizeArea`,`YearBuilt`,`CoveredSpaces`,`SubdivisionName`,`MLSNumber`,`BedsTotal`,
        `GarageDescription`

        FROM
            `properties`

        WHERE
            1
            $date_sql
            $general_search
            $bed_sql
            $bath_sql
            $sqf_sql
            $stories_sql
            $acres_sql
            $price_sql
            $LastStatus_sql
            $PropertySubType_sql
            AND `Longitude` != ''
            AND `Latitude` != ''

        ORDER BY `ListPrice` ASC


        ";

        $template = "";
        $url = "";
        switch($routeName) {
            case "list-map":
            $template = "list_map.html.twig";
            $url = "list-map";
            $limit = "14";
            break;

            case "gallery":
            $template = "gallery.html.twig";
            $url = "gallery";
            $limit = "8";
            break;

            case "gallery-map":
            $template = "gallery_map.html.twig";
            $url = "gallery-map";
            $limit = "4";
            break;

            default:
            $template = "list_map.html.twig";
            $url = "list-map";
            $limit = "14";
            break;
        }

        /* BEGIN Paginate */
        //$sql_check = $sql . " LIMIT 1";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        $total_records = $result->rowCount();
        $records = $total_records;

        if ($total_records < 1) {
            $this->addFlash('danger','Your search did not return any records.');
            return $this->redirectToRoute('homepage'); 
        }

        $paginate_output = ""; // init
        $pageinate_output = $Pageinate->page_numbers($sql,$page);
        $sql .= " LIMIT $next,$limit";
        /* END Paginate */

        $i = "0";
        $found = "0";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        while ($property = $result->fetch()) {
            $found = "1";
            foreach($property as $key=>$value) {
                $data[$i][$key] = $value;
            }
            $sql2 = "
            SELECT `image`,`sequence` 
            FROM `images` 
            WHERE `matrixUniqueId` = '$property[Matrix_Unique_ID]'
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

            //$data[$i]['images'] = $Properties->getAllImage($property['Matrix_Unique_ID']);


            if ($i == "0") {
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
                $GarageDescription = $property['GarageDescription'];

                if ($half_bath == "1") {
                    $half_bath = "5";
                }
            }
            $i++;
        }
        if ($found == "0") {
            $data = "N/A";
            $ListPrice = "";
            $Address = "";
            $City = "";
            $StateOrProvince = "";
            $PostalCode = "";
            $beds = "";
            $half_bath = "";
            $full_bath = "";
            $SqftTotal = "";
            $LotSizeArea = "";
            $YearBuilt = "";
            $SubdivisionName = "";
            $images = "";
            $Latitude = "";
            $Longitude = "";
            $GarageDescription = "";
        }

        $featured = "10";
        $justin = "5";
        $sql = json_encode($sql);


        // Featured Properties
        $featuredData = $this->get('commonservices')->GetFeatured();

        // Just Listed Properties
        $justlistedData = $this->get('commonservices')->GetJustListed();

        return $this->render('home/'.$template,[
            'Matrix_Unique_ID' => $Matrix_Unique_ID,
	        'featured' => $featured,
            'justin' => $justin,
            'property' => $data,
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
            'params' => $params,
            'paginate' => $pageinate_output,
            'url' => $url,
            'records' => $records,
            'GarageDescription' => $GarageDescription,
            'featuredData' => $featuredData,
            'justlistedData' => $justlistedData,
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

    /**
     * @Route("/property_container", name="property_container")
     */
    public function property_containerAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $Matrix_Unique_ID = $request->request->get('id');
        //$Matrix_Unique_ID = $request->query->get('Matrix_Unique_ID');

        //print "<pre>";
        //print_r($Matrix_Unique_ID);
        //print "</pre>";
        //$results = $Properties->property_details($Matrix_Unique_ID);

        $sql = "
        SELECT `Matrix_Unique_ID`,`Latitude`,`Longitude`,`ListPrice`,`Address`,
        `City`,`StateOrProvince`,`PostalCode`,`NumMainLevelBeds`,`NumOtherLevelBeds`,
        `BathsHalf`,`NumGuestHalfBaths`,`NumGuestFullBaths`,`BathsFull`,`SqftTotal`,
        `LotSizeArea`,`YearBuilt`,`CoveredSpaces`,`SubdivisionName`,`MLSNumber`,`GarageDescription`

        FROM
            `properties`

        WHERE
            1
            AND `Matrix_Unique_ID` = '$Matrix_Unique_ID'

        ORDER BY `ListPrice` ASC
        ";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        while ($property = $result->fetch()) {
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
            $GarageDescription = $property['GarageDescription'];
        }



        if ($half_bath == "1") {
            $half_bath = "5";
        }


        return $this->render('ajax/property_container.html.twig',[
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
            'GarageDescription' => $GarageDescription,
        ]);
    }


}
