<?php
/**
 * Created by PhpStorm.
 * User: Pierre
 * Date: 19/04/2015
 * Time: 14:50
 */
namespace testiAdvize\FrontBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sunra\PhpSimple\HtmlDomParser;

class Methods
{
    /**
     * @param $indexSource
     * @param $find
     * @param $source
     * @param $boolResultat
     * @return null
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