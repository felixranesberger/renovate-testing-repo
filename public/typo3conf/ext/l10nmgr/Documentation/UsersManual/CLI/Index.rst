.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.rst.txt

.. _cli:

Command Line Interface (CLI)
============================

.. toctree::
	:maxdepth: 5
	:titlesonly:

Command Line Interface (CLI)
The L10N Manger also offers two command line interfaces to export and import content in CAT XML or Excel XML format. To use the CLI you have to create a TYPO3 BE user named _cli_user.
CLI for export
To find out more about the export interface call the CLI with :code:`--help` as argument.

:code:`php cli_dispatch.phpsh l10nmgr_export –-help`

+----------------+------------------------------------------------------------------------------------+
| Property:      | Description:                                                                       |
+================+====================================================================================+
| -s             | Silent operation, will only output errors and important messages.                  |
+----------------+------------------------------------------------------------------------------------+
| --silent       | Same as -s.                                                                        |
+----------------+------------------------------------------------------------------------------------+
| --ss           | Super silent, will not even output errors or important messages.                   |
+----------------+------------------------------------------------------------------------------------+
| --format       | Format for export of tranlatable data, supported are:                              |
|                |                                                                                    |
|                | - CATXML = XML for translation tools (default)                                     |
|                | - EXCEL = Microsoft XML format                                                     |
+----------------+------------------------------------------------------------------------------------+
| --config       | Localization Manager configurations.                                               |
|                | UIDs of the localization manager configurations to be used for export.             |
|                | Comma seperated values, no spaces. Default is EXTCONF which means values           |
|                | are taken from extension configuration.                                            |
+----------------+------------------------------------------------------------------------------------+
| --target       | Target languages                                                                   |
|                | UIDs for the target languages used during export. Comma seperated values,          |
|                | no spaces. Default is 0. In that case UIDs are taken from extension configuration. |
+----------------+------------------------------------------------------------------------------------+
| --workspace    | Workspace ID                                                                       |
|                | UID of the workspace used during export. Default = 0                               |
+----------------+------------------------------------------------------------------------------------+
| --hidden       | Do not export hidden content, the values can be:                                   |
|                |                                                                                    |
|                | - TRUE = Hidden content is skipped                                                 |
|                | - FALSE = Hidden content is exported (default).                                    |
+----------------+------------------------------------------------------------------------------------+
| --updated      | Export only new/updated content, the values can be:                                |
|                |                                                                                    |
|                | - TRUE = Only new/updated content is exported                                      |
|                | - FALSE = All content is exported (default)                                        |
+----------------+------------------------------------------------------------------------------------+
| --check-export | Check for already exported content                                                 |
|                | The values can be:                                                                 |
|                | • TRUE = Check if content has already been exported.                               |
|                | • FALSE = Don't check, just create a new export (default).                         |
+----------------+------------------------------------------------------------------------------------+
| --help         | Show help.                                                                         |
+----------------+------------------------------------------------------------------------------------+
| -h             | Same as –help.                                                                     |
+----------------+------------------------------------------------------------------------------------+

Example
:code:`php cli_dispatch.phpsh l10nmgr_export --format=CATXML --config=3 --target=1 –hidden=FALSE`

CLI for import
^^^^^^^^^^^^^^

To find out more about the import interface call the CLI with :code:`--help` as argument. The CLI for import of translations only supports the CAT XML format.

:code:`php cli_dispatch.phpsh l10nmgr_import –-help`

+---------------------------+-------------------------------------------------------------------------------+
| Property:                 | Description:                                                                  |
+===========================+===============================================================================+
| -s                        | Silent operation, will only output errors and important messages.             |
+---------------------------+-------------------------------------------------------------------------------+
| --silent                  | Same as -s.                                                                   |
+---------------------------+-------------------------------------------------------------------------------+
| --ss                      | Super silent, will not even output errors or important messages.              |
+---------------------------+-------------------------------------------------------------------------------+
| --task                    | The task to execute, the values can be:                                       |
|                           |                                                                               |
|                           | - importString = Import a XML string                                          |
|                           | - importFile = Import a XML file                                              |
|                           | - preview = Generate a preview of the source from a XML string                |
+---------------------------+-------------------------------------------------------------------------------+
| --preview                 | Preview flag                                                                  |
|                           | Set to 1 in case of preview, 0 otherwise. Defaults to 0.                      |
+---------------------------+-------------------------------------------------------------------------------+
| --string                  | XML string to import                                                          |
+---------------------------+-------------------------------------------------------------------------------+
| --file                    | Import file                                                                   |
|                           | Path to the file to import. Can be XML or ZIP archive. If both XML string and |
|                           | import file are not defined, will import from FTP server (if defined).        |
+---------------------------+-------------------------------------------------------------------------------+
| --server                  | Server link for the preview URL.                                              |
+---------------------------+-------------------------------------------------------------------------------+
| --importAsDefaultLanguage | Import as default language. If set this setting will overwrite the default    |
|                           | language during the import.                                                   |
|                           |                                                                               |
|                           | - TRUE = Content will be imported as default language.                        |
|                           | - FALSE = Content will be imported as translation (default).                  |
+---------------------------+-------------------------------------------------------------------------------+
| --help                    | Show help.                                                                    |
+---------------------------+-------------------------------------------------------------------------------+
| -h                        | Same as –help.                                                                |
+---------------------------+-------------------------------------------------------------------------------+

Example

:code:`php cli_dispatch.phpsh l10nmgr_import –-task=importFile –-preview=0 -–file=translated-content.xml`

