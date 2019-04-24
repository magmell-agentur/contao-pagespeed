<?php

$GLOBALS['TL_DCA']['tl_settings']['fields']['loadJavascriptAsynchronously'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['loadJavascriptAsynchronously'],
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50')
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['enableCssVersioning'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['enableCssVersioning'],
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50')
];

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace(
    ';{backend_legend:',
    ',loadJavascriptAsynchronously,enableCssVersioning;{backend_legend:',
    $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);
