.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.rst.txt

.. _features:

Features
========

In its current version Localization Manager comprises the following features:

General features
----------------

1. Support for all contents (pages, content elements) including contents from internationalized extensions (e.g. tt_news) and flexible content elements
2. Workspace support: Allows for translation workflows in offline workspaces
3. TemplaVoilà support

Export features
---------------

1. Fine grained export configurations (via Localization Manager Configuration record)
2. Export of localizable content (pages and page content) into

   1. a localization-friendly XML format
   2. MS Excel XML (for translation in MS Excel or OpenOffice Calc)

3. Selection of localizable page and content element types as well as localizable database table fields
4. Check for already localized pages and content elements
5. Options

   1. export only new/changed content
   2. skip export of hidden content elements
   3. single page export, recursive export
   4. exclude selected elements from export
   5. include selected elements from export
   6. check encoding (UTF-8)
   7. check XML for wellformedness
   8. select source languages other than the default language (important for relay translations)

6. Predefined configuration files for across, DéjàVu, memoQ, SDL TRADOS TagEditor, SDL Trados Studio 2009, and SDL PASSOLO, which avoids time-consuming manual setup
7. Automation of  exports via command line scripts (CLI) with

   1. multiple Localization Manager configurations
   2. for multiple target languages
   3. upload of source language XML files to remote server via FTP
   4. sending source language XML files via email

Import features
---------------
1. Re-import of localized XML files
2. Automatic insertion of localized pages and page content into TYPO3 database
3. Options:

   1. Overwrite existing translations (pages and content elements)
   2. Generate preview links (e.g. if translations are imported in a workspace)
   3. Write back to default language, e.g. when copying a tree.

Workflow features
-----------------
1. Localization overview (very basic)
