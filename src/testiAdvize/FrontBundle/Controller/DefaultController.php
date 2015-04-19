<?php

namespace testiAdvize\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sunra\PhpSimple\HtmlDomParser;
use testiAdvize\FrontBundle\Class\Methods;;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $content =HtmlDomParser::file_get_html( "http://m.viedemerde.fr/" );

        $ul = Methods::searchNode(1,'ul',$content,false);
        $p = Methods::searchNode(0,'p',$ul,true);




        return $this->render('testiAdvizeFrontBundle:Default:index.html.twig',array('dom'=>$p));
    }

}
