<?php

declare(strict_types=1);

namespace Localizationteam\L10nmgr\View;

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

use Localizationteam\L10nmgr\Model\L10nConfiguration;
use Localizationteam\L10nmgr\Traits\BackendUserTrait;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * l10nmgr detail view:
 * renders information for a l10ncfg record.
 *
 * @author Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @author Daniel Pötzinger <development@aoemedia.de>
 * @author Stefano Kowalke <info@arroba-it.de>
 */
class L10nConfigurationDetailView
{
    use BackendUserTrait;

    /**
     * @var L10nConfiguration
     */
    protected L10nConfiguration $l10ncfgObj;

    /**
     * @var LanguageService
     */
    protected LanguageService $languageService;

    /**
     * @param L10nConfiguration $l10ncfgObj
     */
    public function __construct(L10nConfiguration $l10ncfgObj)
    {
        $this->l10ncfgObj = $l10ncfgObj;
        $this->languageService = GeneralUtility::makeInstance(LanguageService::class);
    }

    /**
     * @return array
     */
    public function render(): array
    {
        if (!$this->hasValidConfig()) {
            return [
                'isInvalid' => true,
                'error' => $this->getLanguageService()->getLL('general.export.configuration.error.title'),
            ];
        }

        $configurationSettings = [
            'header' => htmlspecialchars($this->l10ncfgObj->getData('title')) . ' [' . $this->l10ncfgObj->getData('uid') . ']',
            'depth' => htmlspecialchars($this->l10ncfgObj->getData('depth')),
            'tables' => htmlspecialchars($this->l10ncfgObj->getData('tablelist')),
            'exclude' => htmlspecialchars($this->l10ncfgObj->getData('exclude')),
            'include' => htmlspecialchars($this->l10ncfgObj->getData('include')),
        ];

        return str_replace(',', ', ', $configurationSettings);
    }

    /**
     * @return bool
     */
    protected function hasValidConfig(): bool
    {
        return is_object($this->l10ncfgObj) && $this->l10ncfgObj->isLoaded();
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        if ($this->getBackendUser()) {
            $this->languageService->init($this->getBackendUser()->uc['lang'] ?? ($this->getBackendUser()->user['lang'] ?? 'en'));
        }
        return $this->languageService;
    }
}
