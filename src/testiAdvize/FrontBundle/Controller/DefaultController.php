<?php

namespace testiAdvize\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sunra\PhpSimple\HtmlDomParser;

class DefaultController extends Controller
{
    public function indexAction()
    {


        $content =HtmlDomParser::file_get_html( "http://m.viedemerde.fr/" );

        $nodes = $content->find('ul');
        $res= null;
        $index =0;
        foreach($nodes as $node)
        {
            if($index==1)
            {
                $res = $node;

            }
            $index = $index +1;
        }

        $nodes = $res->find('p');
        $res= null;
        $index =0;
        foreach($nodes as $node)
        {
            if($index==0)
            {
                $res = $node->plaintext;

            }
            $index = $index +1;
        }


        return $this->render('testiAdvizeFrontBundle:Default:index.html.twig',array('dom'=>$res));
    }
}
