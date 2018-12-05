<?php

$GLOBALS['TL_HOOKS']['outputFrontendTemplate'][] =   ['Magmell\Contao\PageSpeed\Hooks\FrontendTemplateHook', 'replaceHeadTag'];
$GLOBALS['TL_HOOKS']['replaceDynamicScriptTags'][] = ['Magmell\Contao\PageSpeed\Hooks\DynamicScriptTagsHook', 'replaceCss'];
