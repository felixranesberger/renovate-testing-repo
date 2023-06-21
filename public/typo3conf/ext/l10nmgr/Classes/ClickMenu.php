<?php

declare(strict_types=1);

namespace Localizationteam\L10nmgr;

/***************************************************************
 * Copyright notice
 * (c) 2006 Kasper Skårhøj <kasperYYYY@typo3.com>
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
 * Addition of an item to the clickmenu
 *
 * @author Kasper Skårhøj <kasperYYYY@typo3.com>
 */

use Localizationteam\L10nmgr\Traits\BackendUserTrait;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Context menu processing
 *
 * @author Kasper Skaarhoj <kasperYYYY@typo3.com>
 *
 * @todo This class was used in the hook `$GLOBALS['TBE_MODULES_EXT']['xMOD_alt_clickmenu']['extendCMclasses']`, which was removed in TYPO3 v8.
 *       It needs to be migrated to the new ItemProvider API TYPO3\CMS\Backend\ContextMenu\ItemProviders\ProviderInterface.
 */
class ClickMenu
{
    use BackendUserTrait;

    /**
     * @var LanguageService
     */
    protected LanguageService $languageService;

    public function __construct()
    {
        $this->languageService = GeneralUtility::makeInstance(LanguageService::class);
    }

    /**
     * Main function
     *
     * @param mixed $backRef
     * @param array $menuItems
     * @param string $table
     * @param int $uid
     * @return array
     * @throws RouteNotFoundException
     */
    public function main(mixed $backRef, array $menuItems, string $table, int $uid): array
    {
        $localItems = [];
        if (!$backRef->cmLevel) {
            // Returns directly, because the clicked item was not from the pages table
            if ($table == 'tx_l10nmgr_cfg') {
                // Adds the regular item:
                $LL = $this->includeLL();
                // Repeat this (below) for as many items you want to add!
                // Remember to add entries in the localconf.php file for additional titles.
                $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
                $urlParameters = [
                    'id' => $backRef->rec['pid'],
                    'srcPID' => $backRef->rec['pid'],
                    'exportUID' => $uid,
                ];
                try {
                    $uri = $uriBuilder->buildUriFromRoute('ConfigurationManager_LocalizationManager', $urlParameters);
                } catch (RouteNotFoundException $e) {
                    $uri = $uriBuilder->buildUriFromRoutePath(
                        'ConfigurationManager_LocalizationManager',
                        $urlParameters
                    );
                }
                $url = (string)$uri;

                $localItems[] = $backRef->linkItem(
                    $this->getLanguageService()->getLL('cm1_title'),
                    $backRef->excludeIcon('<img src="' . PathUtility::stripPathSitePrefix(ExtensionManagementUtility::extPath('l10nmgr')) . 'cm1/cm_icon.gif" width="15" height="12" border="0" align="top" />'),
                    $backRef->urlRefForCM($url),
                    1 // Disables the item in the top-bar. Set this to zero if you with the item to appear in the top bar!
                );
            }
            $localItems['moreoptions_tx_l10nmgr_cm3'] = $backRef->linkItem(
                'L10Nmgr tools',
                '',
                "top.loadTopMenu('" . GeneralUtility::linkThisScript() . "&cmLevel=1&subname=moreoptions_tx_l10nmgrXX_cm3');return false;",
                0,
                1
            );
            // Simply merges the two arrays together and returns ...
            $menuItems = array_merge($menuItems, $localItems);
        } elseif (GeneralUtility::_GET('subname') == 'moreoptions_tx_l10nmgrXX_cm3') {
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $url = $uriBuilder->buildUriFromRoute(
                'LocalizationManager_TranslationTasks',
                [
                    'id' => $backRef->rec['pid'],
                    'table' => $table,
                ]
            );
            $localItems[] = $backRef->linkItem(
                'Create priority',
                '',
                $backRef->urlRefForCM($url . '&cmd=createPriority'),
                1
            );
            $localItems[] = $backRef->linkItem(
                'Manage priorities',
                '',
                $backRef->urlRefForCM($url . '&cmd=managePriorities'),
                1
            );
            $localItems[] = $backRef->linkItem(
                'Update Index',
                '',
                $backRef->urlRefForCM($url . '&cmd=updateIndex'),
                1
            );
            $localItems[] = $backRef->linkItem(
                'Flush Translations',
                '',
                $backRef->urlRefForCM($url . '&cmd=flushTranslations'),
                1
            );
            $menuItems = array_merge($menuItems, $localItems);
        }
        return $menuItems;
    }

    /**
     * Reads the [extDir]/locallang.xml and returns the $LOCAL_LANG array found in that file.
     *
     * @return array Local lang value.
     */
    protected function includeLL(): array
    {
        return $this->getLanguageService()->includeLLFile(
            'EXT:l10nmgr/Resources/Private/Language/locallang.xml'
        );
    }

    /**
     * setter for databaseConnection object
     *
     * @return LanguageService $languageService
     */
    protected function getLanguageService(): LanguageService
    {
        if ($this->getBackendUser()) {
            $this->languageService->init($this->getBackendUser()->uc['lang'] ?? ($this->getBackendUser()->user['lang'] ?? 'en'));
        }
        return $this->languageService;
    }
}
