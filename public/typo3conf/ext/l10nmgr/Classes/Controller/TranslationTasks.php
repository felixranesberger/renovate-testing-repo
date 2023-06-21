<?php

declare(strict_types=1);

namespace Localizationteam\L10nmgr\Controller;

/***************************************************************
 * Copyright notice
 * (c) 2007 Kasper Skårhøj <kasperYYYY@typo3.com>
 * All rights reserved
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Module 'Workspace Tasks' for the 'l10nmgr' extension.
 *
 * @author Kasper Skårhøj <kasperYYYY@typo3.com>
 */

use Localizationteam\L10nmgr\Hooks\Tcemain;
use Localizationteam\L10nmgr\Model\Tools\Tools;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class TranslationTasks extends BaseModule
{
    /**
     * @var ModuleTemplate
     */
    protected ModuleTemplate $module;

    /**
     * @var Tools
     */
    protected Tools $l10nMgrTools;

    /**
     * @var array
     */
    protected array $sysLanguages = [];

    /**
     * main action to be registered in ext_tables.php
     * @return HtmlResponse
     */
    public function mainAction(): HtmlResponse
    {
        $this->init();
        // $this->main();
        return new HtmlResponse($this->getContent());
    }

    public function init(): void
    {
        $this->module = GeneralUtility::makeInstance(ModuleTemplate::class);
        $this->MCONF['name'] = 'LocalizationManager_TranslationTasks';
        $this->getBackendUser()->modAccess($this->MCONF);
        $this->getLanguageService()->includeLLFile('EXT:l10nmgr/Resources/Private/Language/Modules/Module2/locallang.xlf');
        parent::init();
    }

    /**
     * Main function of the module. Write the content to $this->content
     * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
     */
    public function main()
    {
        // Draw the header.
        $this->module = GeneralUtility::makeInstance(DocumentTemplate::class);
        $this->module->backPath = $GLOBALS['BACK_PATH'];
        $this->module->form = '<form action="" method="post">';
        // JavaScript
        $this->module->JScode = '
	<script language="javascript" type="text/javascript">
	script_ended = 0;
	function jumpToUrl(URL)	{
	document.location = URL;
	}
	</script>
	';
        // Setting up the context sensitive menu:
        $this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/ClickMenu');
        $this->content .= $this->module->startPage($this->getLanguageService()->getLL('title'));
        $this->content .= '<div class="topspace5"></div>';
        // Render content:
        $this->moduleContent();
        // ShortCut
        if ($this->getBackendUser()->mayMakeShortcut()) {
            $this->content .= '<hr /><div>' . $this->module->makeShortcutIcon(
                'id',
                implode(',', array_keys($this->MOD_MENU)),
                $this->MCONF['name']
            ) . '</div>';
        }
        $this->content .= '<div class="bottomspace10"></div>';
    }

    /**
     * Generates the module content
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function moduleContent()
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_l10nmgr_priorities');
        // Selecting priorities:
        $priorities = $queryBuilder->select('*')
            ->from('tx_l10nmgr_priorities')
            ->orderBy('sorting')
            ->execute()
            ->fetchAll();
        $tRows = [];
        $c = 0;
        foreach ($priorities as $priorityRecord) {
            if ($lTable = $this->languageRows($priorityRecord['languages'], $priorityRecord['element'])) {
                $c++;
                $tRows[] = '
	<tr>
	<td class="bgColor5"><strong>#' . ($c) . ': ' . htmlspecialchars((string)$priorityRecord['title']) . '</strong><br />' . htmlspecialchars((string)$priorityRecord['description']) . '</td>
	</tr>
	<tr>
	<td>' . $lTable . '</td>
	</tr>';
            }
        }
        $content = '<table border="0" cellpadding="4" cellspacing="2">' . implode('', $tRows) . '</table>';
        $this->content .= '<div><h2 class="uppercase">Priority list:</h2>' . $content . '</div>';
    }

    /**
     * @param string $languageList
     * @param string $elementList
     * @return string
     */
    protected function languageRows(string $languageList, string $elementList): string
    {
        // Initialization:
        $elements = $this->explodeElement($elementList);
        $firstEl = current($elements);
        /** @var Tcemain $hookObj */
        $hookObj = GeneralUtility::makeInstance(Tcemain::class);
        $this->l10nMgrTools = GeneralUtility::makeInstance(Tools::class);
        $this->l10nMgrTools->verbose = false; // Otherwise it will show records which has fields but none editable.
        $inputRecord = BackendUtility::getRecord($firstEl[0], $firstEl[1], 'pid');
        $this->sysLanguages = $this->l10nMgrTools->t8Tools->getSystemLanguages($firstEl[0] == 'pages' ? $firstEl[1] : $inputRecord['pid']);
        $languages = $this->getLanguages($languageList, $this->sysLanguages);
        if (count($languages)) {
            $tRows = [];
            // Header:
            $cells = '<td class="bgColor2 tableheader">Element:</td>';
            foreach ($languages as $l) {
                if ($l >= 1) {
                    $baseRecordFlag = '<img src="' . htmlspecialchars($GLOBALS['BACK_PATH'] . $this->sysLanguages[$l]['flagIcon']) . '" alt="' . htmlspecialchars((string)$this->sysLanguages[$l]['title']) . '" title="' . htmlspecialchars((string)$this->sysLanguages[$l]['title']) . '" />';
                    $cells .= '<td class="bgColor2 tableheader">' . $baseRecordFlag . '</td>';
                }
            }
            $tRows[] = $cells;
            foreach ($elements as $el) {
                $rec_on = [];
                // Get CURRENT online record and icon based on "t3ver_oid":
                if ($el[0] !== '' && $el[1] > 0) {
                    $rec_on = BackendUtility::getRecord($el[0], $el[1]);
                }
                $icon = GeneralUtility::makeInstance(IconFactory::class)->getIconForRecord($el[0], $rec_on);
                $icon = BackendUtility::wrapClickMenuOnIcon($icon, $el[0], $rec_on['uid'], 2);
                $linkToIt = '<a href="#" onclick="' . htmlspecialchars('parent.list_frame.location.href="' . $GLOBALS['BACK_PATH'] . PathUtility::stripPathSitePrefix(ExtensionManagementUtility::extPath('l10nmgr')) . 'cm2/index.php?table=' . $el[0] . '&uid=' . $el[1] . '"; return false;') . '" target="listframe">
	' . BackendUtility::getRecordTitle($el[0], $rec_on, true) . '
	</a>';
                if ($el[0] == 'pages') {
                    // If another page module was specified, replace the default Page module with the new one
                    $newPageModule = trim($this->getBackendUser()->getTSConfig()['options.']['overridePageModule']);
                    $pageModule = BackendUtility::isModuleSetInTBE_MODULES($newPageModule) ? $newPageModule : 'web_layout';
                    $path_module_path = GeneralUtility::resolveBackPath($GLOBALS['BACK_PATH'] . '../' . substr(
                        $GLOBALS['TBE_MODULES']['_PATHS'][$pageModule],
                        strlen(Environment::getPublicPath() . '/')
                    ));
                    $onclick = 'parent.list_frame.location.href="' . $path_module_path . '?id=' . $el[1] . '"; return false;';
                    $path_module_path = GeneralUtility::resolveBackPath($GLOBALS['BACK_PATH'] . '../' . substr(
                        $GLOBALS['TBE_MODULES']['_PATHS'][$pageModule],
                        strlen(Environment::getPublicPath())
                    ));
                    $pmLink = '<a href="#" onclick="' . htmlspecialchars($onclick) . '" target="listframe"><i>[Edit page]</i></a>';
                } else {
                    $pmLink = '';
                }
                $cells = '<td>' . $icon . $linkToIt . $pmLink . '</td>';
                foreach ($languages as $l) {
                    if ($l >= 1) {
                        $cells .= '<td align="center">' . $hookObj->calcStat([$el[0], $el[1]], $l) . '</td>';
                    }
                }
                $tRows[] = $cells;
            }
            return '<table border="0" cellpadding="0" cellspacing="0"><tr>' . implode(
                '</tr><tr>',
                $tRows
            ) . '</tr></table>';
        }
        return '';
    }

    /**
     * @param string $elementList
     * @return array
     */
    protected function explodeElement(string $elementList): array
    {
        $elements = GeneralUtility::trimExplode(',', $elementList);
        foreach ($elements as $k => $element) {
            $elements[$k] = GeneralUtility::revExplode('_', $element, 2);
        }
        return $elements;
    }

    /**
     * @param string $limitLanguageList
     * @param array $sysLanguages
     * @return array
     */
    protected function getLanguages(string $limitLanguageList, array $sysLanguages): array
    {
        $languageListArray = explode(
            ',',
            $this->getBackendUser()->groupData['allowed_languages'] ?: implode(
                ',',
                array_keys($sysLanguages)
            )
        );
        foreach ($languageListArray as $kkk => $val) {
            if ($limitLanguageList && !GeneralUtility::inList($limitLanguageList, $val)) {
                unset($languageListArray[$kkk]);
            }
        }
        return $languageListArray;
    }

    /**
     * Prints out the module HTML
     *
     * @return string
     */
    protected function getContent(): string
    {
        $this->content = '<h2>Currently deactivated. Will be refactored soon.</h2>';
        return $this->content;
    }

    /**
     * Adds items to the ->MOD_MENU array. Used for the function menu selector.
     */
    public function menuConfig(): void
    {
        parent::menuConfig();
    }
}
