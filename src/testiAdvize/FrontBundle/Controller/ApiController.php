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
        $vdmCount=0;//compteur de post de VDM
        $chainefinale =null;
        $indexPage =0;// permet de changer de page
        // tant que les 200 VDM ne sont pas lues
        while($vdmCount<200) {
            // récupération du flux HTML de la page VDM mobile
            $content = HtmlDomParser::file_get_html("http://m.viedemerde.fr/?page=".$indexPage);
            //utilisation de la méthode personalisée de recherche de valeur dans des noeuds HTML
            $ul = Methods::searchNode(1, 'ul', $content, false);
            // récupération des noeuds <li> dans le deuxieme noeud <ul> (celui qui contient dans la pasge les données recherchées)
            $nodesLi = $ul->find('li');
            // instanciation du tableau de reception des valeurs lues dans les noeuds
            $tableauJson = array();
            //boucle for permettant de naviguer entre les posts VDM des noeuds <li>
            for ($i = 0; $i < sizeof($nodesLi); $i++) {
                // récupération de l'objet <li> qui contient toutes les infos d'un post
                $li = Methods::searchNode($i, 'li', $ul, false);
                //incrémentation de la valeur lue de posts VDM
                $vdmCount = $vdmCount + 1;
                // récupération de la valeur de l'"id"
                $value = $li->id;
                $tableauJson[$i][0] = $value;
                // récupération de la valeur du "content"
                $p = Methods::searchNode(0, 'p[class=text]', $li, true);
                $tableauJson[$i][1] = $p;
                // récupération de l'objet <div> qui contient les <span> de date et de author
                $div = Methods::searchNode(0, 'div', $li, false);

                $spanDate = Methods::searchNode(0, 'span', $div, true);
                //séparation dans la chaine de caractère de la date de author
                $tabDateAuteur = explode("\n", $spanDate);
                $date = $tabDateAuteur[0];
                $author = $tabDateAuteur[1];
                // affectation des valeurs de date et de author au tableauJson
                $tableauJson[$i][2] = $date;
                $tableauJson[$i][3] = $author;

            }

            // encodage du tableau au formation JSON
            $chaine = json_encode($tableauJson);

            //concaténation de la nouvelle chaine de caractère lue avec la chaine finale
            $chainefinale =  $chainefinale.$chaine;
            //changement de page
            $indexPage=$indexPage+1;

        }



        //début de sérialisation pour sauvegarder les données
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $serializer->serialize($tableauJson,'json');



        return $this->render('testiAdvizeFrontBundle:Default:index.html.twig',array('affichage'=> $chainefinale));
        /*PROBLEMES RESTANTS
         * CLASSES DE TESTS !!
         * Créé la persistance des données
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
        // même schéma que dans la méthode postsAction() => sauf au choix de l'id lu
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
                //Changement ici => test si l'id rentré dans l'URL existe dans les 200 derniers posts
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
         * CLASSES DE TESTS!!
        * problème de syntaxe
        * problème sur les accents
        *
        */
    }

}
