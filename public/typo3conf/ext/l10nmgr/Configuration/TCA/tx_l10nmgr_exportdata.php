<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:l10nmgr/Resources/Private/Language/locallang_db.xlf:tx_l10nmgr_export',
        'label' => 'title',
        'l10ncfg_id' => 'l10ncfg_id',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'source_lang' => 'source_lang',
        'translation_lang' => 'translation_lang',
        'default_sortby' => 'ORDER BY title',
        'delete' => 'deleted',
        'iconfile' => 'EXT:l10nmgr/Resources/Public/Icons/icon_tx_l10nmgr_cfg.gif',
    ],
    'feInterface' => [
        'fe_admin_fieldList' => 'title, source_lang, l10ncfg_id, crdate, delete, exclude',
    ],
    'columns' => [
        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:l10nmgr/Resources/Private/Language/locallang_db.xlf:tx_l10nmgr_cfg.title',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'eval' => 'required',
                'readOnly' => 1,
            ],
        ],
        'crdate' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:l10nmgr/Resources/Private/Language/locallang_db.xlf:tx_l10nmgr_cfg.crdate',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date',
                'size' => 48,
                'readOnly' => 1,
            ],
        ],
        'tablelist' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:l10nmgr/Resources/Private/Language/locallang_db.xlf:tx_l10nmgr_cfg.exporttablelist',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'readOnly' => 1,
            ],
        ],
        'translation_lang' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:l10nmgr/Resources/Private/Language/locallang_db.xlf:tx_l10nmgr_cfg.translationLang',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'readOnly' => 1,
            ],
        ],
        'source_lang' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:l10nmgr/Resources/Private/Language/locallang_db.xlf:tx_l10nmgr_cfg.sourceLang',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'readOnly' => 1,
            ],
        ],
        'l10ncfg_id' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:l10nmgr/Resources/Private/Language/locallang_db.xlf:tx_l10nmgr_priorities.configuration',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_l10nmgr_cfg',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
                'readOnly' => 1,
            ],
        ],
        'filename' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:l10nmgr/Resources/Private/Language/locallang_db.xlf:tx_l10nmgr_cfg.filename',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'readOnly' => 1,
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'title, crdate, translation_lang, tablelist, source_lang, l10ncfg_id, exportType, filename'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
];
