.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.rst.txt

.. _export:

Export
======

.. toctree::
	:maxdepth: 5
	:titlesonly:

The export function allows for exporting pages and their content into a) a localization-friendly XML format or b) the MS Excel XML format.
In order to export pages and page content to be localized, proceed as follows:

1. Go to the backend and click on Web > Localization Manager.
2. Select your previously created localization configuration from the table and click on the name of the configuration. This opens the Localization Manager and loads settings from the configuration.
3. Select the target language for your export from the drop-down list.
4. Select XML Export/Import or MS Excel Export/Import from the drop-down list and click on the Export tab. For translation with professional translation tools choose XML. For translation with MS Excel or OpenOffice Calc choose Excel.

Note: Although Excel is a quite popular localization format it usually is tricky to translate when exported from TYPO3
as a lot of HTML or TYPO3 tags might be displayed as text making it hard to read and translate the text. So the
recommendation is definitely to go for translation using the XML format.

|img-xml-export-settings|

1. New/changed content only: Check this option if you only want to export new or changed content for translation. The comparison is always based on the default language version of an element.
2. No hidden content: Check this option if you do not want to export hidden content elements (often content elements that are still not ready to be published).
3. Check for already exported content: Check this option if you want to make sure that you don't export the same translation job twice.
4. Do not check XML: Select this option if you don't want to use the inbuilt XML check. In that case Localization Manager includes content that is not XML well-formed as CDATA elements. Otherwise this contents is not exported and you will find error messages indicating the Ids of the skipped elements.
5. Check encoding (UTF-8): Check this option if you want to make sure that the output XML file consistently used UTF-8 encoding.
6. Force source language: Select a language from the drop-down list to choose a source language for translation other than the default language (relay translations)
7. Click on Export to start the export.
8. The data is now retrieved and packed into  the desired file format.
9. Save the export file.

|img-excel-export-settings|
