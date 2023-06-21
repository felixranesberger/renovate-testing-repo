.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.rst.txt

.. _import:

Import
======

.. toctree::
	:maxdepth: 5
	:titlesonly:

To import the translated XML or MS Excel files, proceed as follows:

1. Go to Web>Localization Manager in the backend.
2. Select your previously created localization configuration from the table and click on the name of the configuration. This opens the Localization Manager and loads settings from the configuration.
3. Select the target language for your export from the drop-down list.
4. Select XML Export/Import or MS Excel Export/Import from the drop-down list and click on the Import tab.
5. Set the import options:

   1. Generate preview links: Check this option to generate preview links for all imported pages. You can then send these links to translators or reviewers so they can proofread the translations.
   2. Delete previous localizations before import: Check this option to delete old translations before importing new ones.
   3. Import as default language: Check this option if you want to overwrite the default language, e. g. after copying a tree.

6. Select the file to be imported (XML) from your local hard drive.
7. Click on Upload to start the import. Now the localized data is written to the appropriate database tables. Upon completion you will see a message on the bottom telling you how many data sets have been imported.

Note: Make sure the file to be imported is well-formed XML. If setup correctly translation tools will not modify or
corrupt the XML. Anyway, sometimes translators insert non-standard entities into the XML, e.g. &reg; for ® leading to
XML parsing errors. Just substitute the entities with their original character before import and everything works fine.

