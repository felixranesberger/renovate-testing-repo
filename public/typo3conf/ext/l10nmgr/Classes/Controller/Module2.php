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

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Script Class for rendering the frameset
 *
 * @author Kasper Skaarhoj <kasperYYYY@typo3.com>
 */
class Module2
{
    // Internal, static:
    /**
     * @var int
     */
    protected int $defaultWidth = 300; // Default width of the navigation frame. Can be overridden from $TBE_STYLES['dims']['navFrameWidth'] (alternative default value) AND from User TSconfig

    // Internal, dynamic:
    /**
     * @var string
     */
    protected string $content; // Content accumulation.

    /**
     * Creates the header and frameset for the module/submodules
     * @throws \TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException
     */
    public function main(): void
    {
        // Setting frame width:
        $width = $this->defaultWidth;
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $this->content .= '
	<frameset cols="' . $width . ',*">
	<frame name="nav_frame" src="' . $uriBuilder->buildUriFromRoute('LocalizationManager_TranslationTasks') . '" marginwidth="0" marginheight="0" scrolling="auto" />
	<frame name="list_frame" src="" marginwidth="0" marginheight="0" scrolling="auto" />
	</frameset>
	</html>
	';
        $this->printContent();
    }

    /**
     * Outputting the accumulated content to screen
     */
    protected function printContent(): void
    {
        echo $this->content;
    }
}
