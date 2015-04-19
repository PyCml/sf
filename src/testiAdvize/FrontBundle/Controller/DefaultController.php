<?php

namespace testiAdvize\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sunra\PhpSimple\HtmlDomParser;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $content =HtmlDomParser::file_get_html( "http://m.viedemerde.fr/" );

        $ul = Methods::searchNode(1,'ul',$content,false);
        $li = Methods::searchNode(0,'li',$ul,false);
        $span = Methods::searchNode(0,'span',$li,true);







        return $this->render('testiAdvizeFrontBundle:Default:index.html.twig',array('dom'=>$span));
    }

}
