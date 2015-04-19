<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 19/04/2015
 * Time: 14:50
 */
namespace testiAdvize\FrontBundle\Controller;

class Methods
{
    /**
     * @param $indexSource
     * @param $find
     * @param $source
     * @param $boolResultat
     * @return null
     * PERMET DE RECHERCHER UN NOEUD DANS UN FICHIER HTML
     * $indexSource permet de donner l'indexation de la source( si il en plusieurs du même nom)
     * $find permet de donner les caractères de recherche
     * $source permet de spécifier la source
     * $boolResultat permet de spécifier si l'ont veur récupérer la valeur en String du noeud
     */
    public static function searchNode($indexSource, $find, $source,$boolResultat)
    {
        $nodes = $source->find($find);
        $res = null;
        $index = 0;
        foreach ($nodes as $node) {
            if ($indexSource == $index && $boolResultat) {
                $res = $node->plaintext;
            }
            elseif($indexSource == $index)
            {
                $res = $node;
            }

            $index = $index + 1;
        }
        return $res;
    }
}