<?php

declare(strict_types=1);

namespace Localizationteam\L10nmgr\View;

/***************************************************************
 * Copyright notice
 * (c) 2006 Kasper Skårhøj <kasperYYYY@typo3.com>
 *
 * @author Fabian Seltmann <fs@marketing-factory.de>
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
use PDO;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageRendererResolver;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\DiffUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Abstract class for all export views
 *
 * @author Fabian Seltmann <fs@marketing-factory.de>
 **/
abstract class AbstractExportView
{
    use BackendUserTrait;

    /**
     * @var string
     */
    public string $filename = '';

    /**
     * @var Site The site configuration object
     */
    protected Site $site;

    /**
     * @var L10nConfiguration The language configuration object
     */
    protected L10nConfiguration $l10ncfgObj;

    /**
     *flags for controlling the fields which should render in the output:
     */
    /**
     * @var int The sys_language_uid of language to export
     */
    protected int $sysLang;

    /**
     * @var bool
     */
    protected bool $modeOnlyChanged = false;

    /**
     * @var bool
     */
    protected bool $modeOnlyNew = false;

    /**
     * @var bool
     */
    protected bool $modeNoHidden = false;

    /**
     * @var string
     */
    protected string $customer = '';

    /**
     * @var int
     */
    protected int $exportType = 0;

    /**
     * @var LanguageService
     */
    protected LanguageService $languageService;

    /**
     * @var array List of messages issued during rendering
     */
    protected array $internalMessages = [];

    /**
     * @var int
     */
    protected int $forcedSourceLanguage = 0;

    /**
     * AbstractExportView constructor.
     *
     * @param L10nConfiguration $l10ncfgObj
     * @param int $sysLang
     * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
     */
    public function __construct(L10nConfiguration $l10ncfgObj, int $sysLang)
    {
        $this->sysLang = $sysLang;
        $this->l10ncfgObj = $l10ncfgObj;
        // Load system languages into menu:
        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $this->site = $siteFinder->getSiteByPageId((int)$l10ncfgObj->getData('pid'));
    }

    /**
     * @return int
     */
    public function getExportType(): int
    {
        return $this->exportType;
    }

    public function setModeNoHidden(): void
    {
        $this->modeNoHidden = true;
    }

    public function setModeOnlyChanged(): void
    {
        $this->modeOnlyChanged = true;
    }

    public function setModeOnlyNew(): void
    {
        $this->modeOnlyNew = true;
    }

    /**
     * Sets the customer name for the export
     * @param string $customer
     */
    public function setCustomer(string $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * Saves the information of the export in the database table 'tx_l10nmgr_sava_data'
     *
     * @return bool
     */
    public function saveExportInformation(): bool
    {
        // get current date
        $date = time();
        // query to insert the data in the database
        $field_values = [
            'source_lang' => $this->forcedSourceLanguage ?: 0,
            'translation_lang' => $this->sysLang,
            'crdate' => $date,
            'tstamp' => $date,
            'l10ncfg_id' => (int)$this->l10ncfgObj->getData('uid'),
            'pid' => (int)$this->l10ncfgObj->getData('pid'),
            'tablelist' => $this->l10ncfgObj->getData('tablelist'),
            'title' => $this->l10ncfgObj->getData('title'),
            'cruser_id' => (int)$this->l10ncfgObj->getData('cruser_id'),
            'filename' => $this->getFilename(),
            'exportType' => $this->exportType,
        ];

        /** @var Connection $databaseConnection */
        $databaseConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_l10nmgr_exportdata');
        $res = $databaseConnection->insert(
            'tx_l10nmgr_exportdata',
            $field_values
        );

        if (!empty($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['l10nmgr']['exportView'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['l10nmgr']['exportView'] as $classData) {
                $postSaveProcessor = GeneralUtility::makeInstance($classData);
                if ($postSaveProcessor instanceof PostSaveInterface) {
                    $postSaveProcessor->postExportAction(
                        [
                            'uid' => (int)$databaseConnection->lastInsertId('tx_l10nmgr_exportdata'),
                            'data' => $field_values,
                        ]
                    );
                }
            }
        }
        return $res > 0;
    }

    /**
     * Get filename
     *
     * @return string File name
     */
    public function getFilename(): string
    {
        if (empty($this->filename)) {
            $this->setFilename();
        }
        return $this->filename;
    }

    /**
     * Set filename
     */
    public function setFilename(): void
    {
        $sourceLang = '';
        $targetLang = '';
        if ($this->exportType == '0') {
            $fileType = 'excel';
        } else {
            $fileType = 'catxml';
        }
        if ($this->sysLang && ExtensionManagementUtility::isLoaded('static_info_tables')) {
            if ($this->l10ncfgObj->getData('sourceLangStaticId')) {
                $staticLangArr = BackendUtility::getRecord(
                    'static_languages',
                    $this->l10ncfgObj->getData('sourceLangStaticId'),
                    'lg_iso_2'
                );
            }
            $targetLangSysLangArr = BackendUtility::getRecord('sys_language', $this->sysLang);
            $targetLangArr = BackendUtility::getRecord(
                'static_languages',
                $targetLangSysLangArr['static_lang_isocode'] ?? 0
            );
            // Set sourceLang for filename
            $sourceLang = $staticLangArr['lg_iso_2'] ?? 0;
            // Use locale for targetLang in filename if available
            $targetLang = $targetLangArr['lg_collate_locale'] ?? ($targetLangArr['lg_iso_2'] ?? 0);
        } else {
            $sourceLanguageConfiguration = $this->site->getAvailableLanguages($this->getBackendUser())[0] ?? null;
            if ($sourceLanguageConfiguration instanceof SiteLanguage) {
                $sourceLang = $sourceLanguageConfiguration->getHreflang() ?: $sourceLanguageConfiguration->getTwoLetterIsoCode();
            }
            $targetLanguageConfiguration = $this->site->getAvailableLanguages($this->getBackendUser())[$this->sysLang] ?? null;
            if ($targetLanguageConfiguration instanceof SiteLanguage) {
                $targetLang = $targetLanguageConfiguration->getHreflang() ?: $targetLanguageConfiguration->getTwoLetterIsoCode();
            }
        }
        if (isset($sourceLang) && isset($targetLang)) {
            $fileNamePrefix = (trim($this->l10ncfgObj->getData('filenameprefix'))) ? $this->l10ncfgObj->getData('filenameprefix') . '_' . $fileType : $fileType;
            // Setting filename:
            $filename = $fileNamePrefix . '_' . $sourceLang . '_to_' . $targetLang . '_' . date('dmy-His') . '.xml';
            $this->filename = $filename;
        } else {
            throw new Exception('Source or target language configuration is missing!');
        }
    }

    /**
     * Checks if an export exists
     *
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function checkExports(): bool
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_l10nmgr_exportdata');
        $numRows = $queryBuilder->count('*')
            ->from('tx_l10nmgr_exportdata')
            ->where(
                $queryBuilder->expr()->eq(
                    'l10ncfg_id',
                    $queryBuilder->createNamedParameter((int)$this->l10ncfgObj->getData('uid'), PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'exportType',
                    $queryBuilder->createNamedParameter($this->exportType)
                ),
                $queryBuilder->expr()->eq(
                    'translation_lang',
                    $queryBuilder->createNamedParameter($this->sysLang, PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetchColumn();

        return $numRows > 0;
    }

    /**
     * Renders a list of saved exports as HTML table.
     *
     * @return string HTML table
     */
    public function renderExports(): string
    {
        $content = [];
        $exports = $this->fetchExports();
        foreach ($exports as $export => $exportData) {
            $content[$export] = sprintf(
                '
<tr class="db_list_normal">
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
</tr>',
                BackendUtility::datetime($exportData['crdate'] ?? 0),
                $exportData['l10ncfg_id'] ?? 0,
                $exportData['exportType'] ?? '',
                $exportData['translation_lang'] ?? 0,
                sprintf(
                    '<a href="%suploads/tx_l10nmgr/jobs/out/%s">%s</a>',
                    GeneralUtility::getIndpEnv('TYPO3_SITE_URL'),
                    $exportData['filename'] ?? '',
                    $exportData['filename'] ?? ''
                )
            );
        }
        return sprintf(
            '
<table class="table table-striped table-hover">
	<thead>
	<tr class="t3-row-header">
	<th>%s</th>
	<th>%s</th>
	<th>%s</th>
	<th>%s</th>
	<th>%s</th>
	</tr>
	</thead>
	<tbody>
%s
	</tbody>
</table>',
            $this->getLanguageService()->getLL('export.overview.date.label'),
            $this->getLanguageService()->getLL('export.overview.configuration.label'),
            $this->getLanguageService()->getLL('export.overview.type.label'),
            $this->getLanguageService()->getLL('export.overview.targetlanguage.label'),
            $this->getLanguageService()->getLL(
                'export.overview.filename.label'
            ),
            implode(chr(10), $content)
        );
    }

    /**
     * Fetches saved exports based on configuration, export format and target language.
     *
     * @return array Information about exports.
     * @throws \Doctrine\DBAL\DBALException
     * @author Andreas Otto <andreas.otto@dkd.de>
     */
    protected function fetchExports(): array
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_l10nmgr_exportdata');
        return $queryBuilder->select('crdate', 'l10ncfg_id', 'exportType', 'translation_lang', 'filename')
            ->from('tx_l10nmgr_exportdata')
            ->where(
                $queryBuilder->expr()->eq(
                    'l10ncfg_id',
                    $queryBuilder->createNamedParameter((int)$this->l10ncfgObj->getData('uid'), PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'exportType',
                    $queryBuilder->createNamedParameter($this->exportType)
                ),
                $queryBuilder->expr()->eq(
                    'translation_lang',
                    $queryBuilder->createNamedParameter($this->sysLang, PDO::PARAM_INT)
                )
            )
            ->orderBy('crdate', 'DESC')
            ->execute()
            ->fetchAll();
    }

    /**
     * getter/setter for LanguageService object
     *
     * @return LanguageService $languageService
     */
    protected function getLanguageService(): LanguageService
    {
        $this->languageService = $GLOBALS['LANG'];
        $this->languageService->includeLLFile('EXT:l10nmgr/Resources/Private/Language/Cli/locallang.xml');
        if ($this->getBackendUser()) {
            $this->languageService->init($this->getBackendUser()->uc['lang'] ?? ($this->getBackendUser()->user['lang'] ?? 'en'));
        }
        return $this->languageService;
    }

    /**
     * Renders a list of saved exports as text.
     *
     * @return string text
     */
    public function renderExportsCli(): string
    {
        $content = [];
        $exports = $this->fetchExports();
        foreach ($exports as $export => $exportData) {
            $content[$export] = sprintf(
                '%-15s%-15s%-15s%-15s%s',
                BackendUtility::datetime($exportData['crdate'] ?? 0),
                $exportData['l10ncfg_id'] ?? 0,
                $exportData['exportType'] ?? '',
                $exportData['translation_lang'] ?? 0,
                sprintf('%suploads/tx_l10nmgr/jobs/out/%s', Environment::getPublicPath() . '/', $exportData['filename'] ?? '')
            );
        }
        return sprintf(
            '%-15s%-15s%-15s%-15s%s%s%s',
            $this->getLanguageService()->getLL('export.overview.date.label'),
            $this->getLanguageService()->getLL('export.overview.configuration.label'),
            $this->getLanguageService()->getLL('export.overview.type.label'),
            $this->getLanguageService()->getLL('export.overview.targetlanguage.label'),
            $this->getLanguageService()->getLL('export.overview.filename.label'),
            LF,
            implode(LF, $content)
        );
    }

    /**
     * Saves the exported files to the folder /uploads/tx_l10nmgr/jobs/out/
     *
     * @param string $fileContent The content to save to file
     * @return string $fileExportName The complete filename
     */
    public function saveExportFile(string $fileContent): string
    {
        $outPath = Environment::getPublicPath() . '/uploads/tx_l10nmgr/jobs/out/';
        if (!is_dir(GeneralUtility::getFileAbsFileName($outPath))) {
            GeneralUtility::mkdir_deep($outPath);
        }

        $fileExportName = $outPath . $this->getFilename();
        GeneralUtility::writeFile($fileExportName, $fileContent);
        return PathUtility::getAbsoluteWebPath($fileExportName);
    }

    /**
     * Diff-compare markup
     *
     * @param string $old Old content
     * @param string $new New content
     * @return string Marked up string.
     */
    public function diffCMP(string $old, string $new): string
    {
        // Creates diff-result
        /** @var DiffUtility $t3lib_diff_Obj */
        $t3lib_diff_Obj = GeneralUtility::makeInstance(DiffUtility::class);
        return $t3lib_diff_Obj->makeDiffDisplay($old, $new);
    }

    /**
     * Renders internal messages as flash message.
     * If the export was successful, check if there were any internal warnings.
     * If yes, display them below the success message.
     *
     * @param string $status Flag which indicates if the export was successful.
     * @return string Rendered flash message or empty string if there are no messages.
     */
    public function renderInternalMessagesAsFlashMessage(string $status): string
    {
        $ret = '';
        if ($status == AbstractMessage::OK) {
            $internalMessages = $this->getMessages();
            if (count($internalMessages) > 0) {
                $messageBody = '';
                foreach ($internalMessages as $messageInformation) {
                    $messageBody .= ($messageInformation['message'] ?? '') . ' (' . ($messageInformation['key'] ?? '') . ')<br />';
                }
                /** @var FlashMessage $flashMessage */
                $flashMessage = GeneralUtility::makeInstance(
                    FlashMessage::class,
                    $messageBody,
                    $this->getLanguageService()->getLL('export.ftp.warnings'),
                    AbstractMessage::WARNING
                );
                $ret .= GeneralUtility::makeInstance(FlashMessageRendererResolver::class)
                    ->resolve()
                    ->render([$flashMessage]);
            }
        }
        return $ret;
    }

    /**
     * Returns the list of internal messages
     *
     * @return array List of messages
     */
    public function getMessages(): array
    {
        return $this->internalMessages;
    }

    /**
     * Store a message in the internal queue
     * Note: this method is protected. Messages should not be set from the outside.
     *
     * @param string $message Text of the message
     * @param string $key Key identifying the element where the problem happened
     */
    protected function setInternalMessage(string $message, string $key): void
    {
        $this->internalMessages[] = [
            'message' => $message,
            'key' => $key,
        ];
    }
}
