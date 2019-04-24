<?php

namespace Magmell\Contao\PageSpeed\Hooks;

use Contao\Config;

class FrontendTemplateHook
{
    public function replaceHeadTag($strBuffer)
    {
        if (!Config::get('loadJavascriptAsynchronously')) {
            return $strBuffer;
        }

        // Eigene Verarbeitung von allem was wirklich in den Kopf gehört (siehe Controller 874ff)
        $strHeadContent = '';
        if (!empty($GLOBALS['TL_HEAD']) && is_array($GLOBALS['TL_HEAD'])) {
            foreach (array_unique($GLOBALS['TL_HEAD']) as $head) {
                $strHeadContent .= trim($head) . "\n";
            }
        }

        if (!empty($GLOBALS['TL_JAVASCRIPT']) && is_array($GLOBALS['TL_JAVASCRIPT'])) {
            // dynamically loads all JS files asynchronously
            // @see https://www.html5rocks.com/en/tutorials/speed/script-loading/
            $strHeadContent .= "<script>!function(e,t,r){function n(){for(;d[0]&&\"loaded\"==d[0][f];)c=d.shift(),c[o]=!i.parentNode.insertBefore(c,i)}for(var s,a,c,d=[],i=e.scripts[0],o=\"onreadystatechange\",f=\"readyState\";s=r.shift();)a=e.createElement(t),\"async\"in i?(a.async=!1,e.head.appendChild(a)):i[f]?(d.push(a),a[o]=n):e.write(\"<\"+t+' src=\"'+s+'\" defer></'+t+\">\"),a.src=s}(document,\"script\",[";

            // exceptions that have nice async rules and handling already
			$arrExceptions = array();
            // $arrExceptions = ["https://maps.googleapis.com/maps/api/js", "https://maps.google.com/maps/api"];

            foreach ($GLOBALS['TL_JAVASCRIPT'] as $strScriptFile) {
                foreach ($arrExceptions as $strException) {
                    if (strpos($strScriptFile, $strException) !== false) {
                        continue 2;
                    }
                }

                $strScriptFile = str_replace('|static', '', $strScriptFile);
                $strVersionedScriptFile = "";

                $strFilePath = TL_ROOT . "/web/" . $strScriptFile;

                if (file_exists($strFilePath)) {
                    $strVersionedScriptFile = $strScriptFile . "?v=" . filemtime($strFilePath);
                }

                $strHeadContent .= sprintf("\"%s\"%s", $strVersionedScriptFile ?: $strScriptFile, ($strScriptFile !== end($GLOBALS['TL_JAVASCRIPT'])) ? ',' : '');
            }

            $strHeadContent .= '])</script>';

            $GLOBALS['TL_JAVASCRIPT'] = [];
        }

        $strBuffer = str_replace('[[TL_HEAD]]', $strHeadContent, $strBuffer);

        // globales Array leer machen, ist ja schon erfolgreich bearbeitet (sonst verarbeitet Contao nochmal)
        $GLOBALS['TL_HEAD'] = [];

        $intPositionFooterEnde = strpos($strBuffer, '</footer>');
        $intPositionErstesScriptNachFooter = strpos($strBuffer, '<script>', $intPositionFooterEnde);

        $strBufferErsterTeil = substr($strBuffer, 0, $intPositionErstesScriptNachFooter);
        $strBufferRest = substr($strBuffer, $intPositionErstesScriptNachFooter);

        //$strBuffer = $strBufferErsterTeil."[[TL_CSS]]"."[[TL_HEAD]]\n".$strBufferRest;
        $strBuffer = $strBufferErsterTeil . "[[TL_HEAD]]\n" . $strBufferRest;

        return $strBuffer;
    }
}
