<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class testCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:get_images')
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

        $sql = "SELECT `Matrix_Unique_ID` FROM `properties`";
        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        while ($row = $result->fetch()) {
            $id = $row['Matrix_Unique_ID'];
            print "ID: $id<br>";

            $counter = "0";
            $data = $this->getAllImage($id);
            if(is_array($data)) {
                foreach ($data as $key=>$value) {
                    print "V: $value<Br><br>";

                    list($type, $value) = explode(';', $value);
                    if ($type == "data:image/jpeg") {
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
