<?php

namespace testiAdvize\FrontBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class ApiController extends Controller
{
    /**
     * @return JSON
     * Controller permettant d'afficher toutes les 200 premieres VDM
     */
    public function postsAction()
    {
        $vdmCount=0;
        $chainefinale =null;
        $indexPage =0;
        while($vdmCount<200) {

            $content = HtmlDomParser::file_get_html("http://m.viedemerde.fr/?page=".$indexPage);

            $ul = Methods::searchNode(1, 'ul', $content, false);
            $nodesLi = $ul->find('li');
            $tableauJson = array();

            $val = null;
            for ($i = 0; $i < sizeof($nodesLi); $i++) {

                $li = Methods::searchNode($i, 'li', $ul, false);
                $vdmCount = $vdmCount + 1;
                $value = $li->id;
                $tableauJson[$i][0] = $value;
                $p = Methods::searchNode(0, 'p[class=text]', $li, true);
                $tableauJson[$i][1] = $p;
                $div = Methods::searchNode(0, 'div', $li, false);
                $spanDate = Methods::searchNode(0, 'span', $div, true);
                $tabDateAuteur = explode("\n", $spanDate);
                $date = $tabDateAuteur[0];
                $author = $tabDateAuteur[1];
                $tableauJson[$i][2] = $date;
                $tableauJson[$i][3] = $author;

            }


            $chaine = json_encode($tableauJson);
            $chainefinale =  $chainefinale.$chaine;
            $indexPage=$indexPage+1;

        }




        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $serializer->serialize($tableauJson,'json');



        return $this->render('testiAdvizeFrontBundle:Default:index.html.twig',array('affichage'=> $chainefinale));
        /*
         * problème de syntaxe
         * problème sur les accents
         *
         */
    }

    /**
     * @return JSON
     * Controller permettant d'afficher qu'un seul post grâce à son id
     */
    public function postsidAction($id)
    {
        $vdmCount=0;
        $chainefinale =null;
        $indexPage =0;
        while($vdmCount<200) {

            $content = HtmlDomParser::file_get_html("http://m.viedemerde.fr/?page=".$indexPage);

            $ul = Methods::searchNode(1, 'ul', $content, false);
            $nodesLi = $ul->find('li');
            $tableauJson = array();

            for ($i = 0; $i < sizeof($nodesLi); $i++) {

                $li = Methods::searchNode($i, 'li', $ul, false);
                $vdmCount = $vdmCount + 1;
                $value = $li->id;
                if($value == $id) {
                    $tableauJson[$i][0] = $value;
                    $p = Methods::searchNode(0, 'p[class=text]', $li, true);
                    $tableauJson[$i][1] = $p;
                    $div = Methods::searchNode(0, 'div', $li, false);
                    $spanDate = Methods::searchNode(0, 'span', $div, true);
                    $tabDateAuteur = explode("\n", $spanDate);
                    $date = $tabDateAuteur[0];
                    $author = $tabDateAuteur[1];
                    $tableauJson[$i][2] = $date;
                    $tableauJson[$i][3] = $author;
                }
                else{
                    $tableauJson[$i][3]=" ";
                }


            }


            $chaine = json_encode($tableauJson);
            if($chaine!=null) {
                $chainefinale = $chainefinale . $chaine;
            }
            $indexPage=$indexPage+1;

        }

        return $this->render('testiAdvizeFrontBundle:Default:index.html.twig',array('affichage'=> $chainefinale));
        /*
        * problème de syntaxe
        * problème sur les accents
        *
        */
    }

}
