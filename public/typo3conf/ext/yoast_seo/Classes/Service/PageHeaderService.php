<?php
declare(strict_types=1);
namespace YoastSeoForTypo3\YoastSeo\Service;

use TYPO3\CMS\Core\SingletonInterface;

class PageHeaderService implements SingletonInterface
{
    /**
     * @var bool
     */
    protected $snippetPreviewEnabled = false;

    /**
     * @var array
     */
    protected $moduleData = [];

    /**
     * @var int
     */
    protected $pageId = 0;

    public function setSnippetPreviewEnabled(bool $snippetPreviewEnabled): void
    {
        $this->snippetPreviewEnabled = true;
    }

    public function isSnippetPreviewEnabled(): bool
    {
        return $this->snippetPreviewEnabled;
    }

    public function getModuleData(): array
    {
        return $this->moduleData;
    }

    public function setModuleData(array $moduleData): void
    {
        $this->moduleData = $moduleData;
    }

    public function getPageId(): int
    {
        return $this->pageId;
    }

    public function setPageId(int $pageId): void
    {
        $this->pageId = $pageId;
    }
}
