<?php

$GLOBALS['TL_HOOKS']['outputFrontendTemplate'][] = ['Magmell\Contao\PageSpeed\Hooks\FrontendTemplateHook', 'replaceHeadTag'];
