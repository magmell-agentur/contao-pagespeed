<?php

namespace Magmell\Contao\PageSpeed\Hooks;

use Contao\Combiner;
use Contao\Controller;
use Contao\LayoutModel;
use Contao\StringUtil;

class DynamicScriptTagsHook
{
    public function replaceCss($strBuffer)
    {
        global $objPage;
		$objLayout = LayoutModel::findByPk($objPage->layoutId);
		$blnCombineScripts = ($objLayout === null) ? false : $objLayout->combineScripts;
		$strScripts = '';
		$objCombiner = new Combiner();

		// Add the CSS framework style sheets
		if (!empty($GLOBALS['TL_FRAMEWORK_CSS']) && \is_array($GLOBALS['TL_FRAMEWORK_CSS']))
		{
			foreach (array_unique($GLOBALS['TL_FRAMEWORK_CSS']) as $stylesheet)
			{
				$objCombiner->add($stylesheet);
			}
		}

		// Add the internal style sheets
		if (!empty($GLOBALS['TL_CSS']) && \is_array($GLOBALS['TL_CSS']))
		{
			foreach (array_unique($GLOBALS['TL_CSS']) as $stylesheet)
			{
				$options = StringUtil::resolveFlaggedUrl($stylesheet);

				if ($options->static)
				{
					$objCombiner->add($stylesheet, $options->mtime, $options->media);
				}
				else
				{
					$strScripts .= static::generateVersionedStyleTag(Controller::addStaticUrlTo($stylesheet), $options->media) . "\n";
				}
			}
		}

		// Add the user style sheets
		if (!empty($GLOBALS['TL_USER_CSS']) && \is_array($GLOBALS['TL_USER_CSS']))
		{
			foreach (array_unique($GLOBALS['TL_USER_CSS']) as $stylesheet)
			{
				$options = StringUtil::resolveFlaggedUrl($stylesheet);

				if ($options->static)
				{
					$objCombiner->add($stylesheet, $options->mtime, $options->media);
				}
				else
				{
					$strScripts .= static::generateVersionedStyleTag(Controller::addStaticUrlTo($stylesheet), $options->media) . "\n";
				}
			}
		}

		// Create the aggregated style sheet
		if ($objCombiner->hasEntries())
		{
			if ($blnCombineScripts)
			{
				$strScripts .= static::generateVersionedStyleTag($objCombiner->getCombinedFile(), 'all') . "\n";
			}
			else
			{
				foreach ($objCombiner->getFileUrls() as $strUrl)
				{
					list($url, $media) = explode('|', $strUrl);

					$strScripts .= static::generateVersionedStyleTag($url, $media) . "\n";
				}
			}
		}

        $GLOBALS['TL_FRAMEWORK_CSS'] = [];
        $GLOBALS['TL_CSS'] = [];
		$GLOBALS['TL_USER_CSS'] = [];

        return str_replace('[[TL_CSS]]', $strScripts, $strBuffer);
    }

    /**
     * @param string $href
     * @param string|null $media
     * @return string Style tag
     */
    public static function generateVersionedStyleTag($href, $media = null)
    {
        $strFilePath = TL_ROOT . "/web/" . $href;
        $strFileLastModificationTimestamp = false;
        if (file_exists($strFilePath)) {
            if ($strFileLastModificationTimestamp = filemtime($strFilePath)) {
                $href .= "?v=" . $strFileLastModificationTimestamp;
            }
        }

        return '<link rel="stylesheet" href="' . $href . '"' . (($media && $media != 'all') ? ' media="' . $media . '"' : '') . '>';
    }
}
