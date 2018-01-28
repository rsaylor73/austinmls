<?php
/* This is a service class */
namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class Pageinate {

    protected $em;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager,ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

   public function map_numbers($max,$pages) {
        $array = "";
        $stop = "";
        for ($i=0; $i < $pages; $i++) {
            if ($stop == "") {
                $stop = "0";
            }
            if ($i > 0) {
                $stop = $stop + $max;
            }
            $i2 = $i + 1;
            $array[$i2] = $stop;
        }
        return $array;
    }

    public function page_numbers($sql,$page) {
        $em = $this->em;
        $container = $this->container;

        $max = "12";

        // init
        $total_records = "0";
        $html = "";
        $url = "WEBAPP";
        $next = "";
        $next10 = "";
        $next100 = "";
        $pre = "";
        $pre10 = "";
        $pre100 = "";

        $sql .= "LIMIT 500";

        $result = $em->getConnection()->prepare($sql);
        $result->execute();
        
        $total_records = $result->rowCount();

        $total_records = $total_records / $max;

        $pages = ceil($total_records);      

        $html = "<div class=\"btn-group\" role=\"group\" aria-label=\"...\">";
        $html .= "<button type=\"button\" class=\"btn btn-default\" disabled>Page</button>";
        if ($page == "1") {
            $html .= "<button type=\"button\" class=\"btn btn-primary\" onclick=\"document.location.href='".$url.$page."/0'\">1</button>";
            $array = $this->map_numbers($max,$pages);
            $next = $page + 1;
            $next10 = $page + 10;
            $next100 = $page + 100;

            if ($next < $pages) {
                $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/".$next."/".$array[$next]."'\">&gt;&gt;</button>";
            }

            if ($next10 < $pages) {
                $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/".$next10."/".$array[$next10]."'\">+ 10</button>";
            }

            if ($next100 < $pages) {
                $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/".$next100."/".$array[$next100]."'\">+ 100</button>";
            }

            $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/".$pages."/".$array[$pages]."'\">$pages</button>";

        } else {
            $array = $this->map_numbers($max,$pages);
            $pre = $page - 1;
            $pre10 = $page - 10;
            $pre100 = $page - 100;
            $next = $page + 1;
            $next10 = $page + 10;
            $next100 = $page + 100;

            $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/1/0'\">1</button>";

            if ($pre10 > 0) {
                    $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/".$pre10."/".$array[$pre10]."'\">- 10</button>";
            }

            if ($pre100 > 0) {
                    $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/".$pre100."/".$array[$pre100]."'\">- 100</button>";
            }


            if(is_array($array[$pre])) {
            $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/".$pre."/".$array[$pre]."'\">&lt;&lt;</button>";
            }


            $html .= "<button type=\"button\" class=\"btn btn-primary\" onclick=\"document.location.href='".$url."/".$page."/".$array[$page]."'\">$page</button>";


            if ($next < $pages) {
                    $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/".$next."/".$array[$next]."'\">&gt;&gt;</button>";
            }

            if ($next10 < $pages) {
                    $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/".$next10."/".$array[$next10]."'\">+ 10</button>";
            }

            if ($next100 < $pages) {
                    $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/".$next100."/".$array[$next100]."'\">+ 100</button>";
            }

            $html .= "<button type=\"button\" class=\"btn btn-default\" onclick=\"document.location.href='".$url."/".$pages."/".$array[$pages]."'\">$pages</button>";

        }
        $html .= "</div>";
        return $html;   
    }

}