.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.rst.txt

.. _typo3-multi-language-setup

TYPO3 – multi-language setup
============================

.. toctree::
	:maxdepth: 5
	:titlesonly:

    - There needs to be a connection between the default language and their translation, means all the translation needs to have a kind of pointer to their original default element. Therefore use these hints:
    - Use "one tree" concept for your page
    - Translation of normal content elements:
        ◦ they need to use the "l18n_parent" feature: This means you should translate elements in the PAGE module or in LIST module with the check box "Localization view"
        ◦ So you always have a default record overlayed with a translation.
    - TemplaVoilà: setup of Flexible Content Elements (FCE):
        ◦ The localization mode needs to be: langChildren=enabled
        ◦ To translate FCE also with normal overlay elements (introduced with extension languagevisibility) you need to add :code:`<langDatabaseOverlay type="integer">1</langDatabaseOverlay>` to the DS of the FCE.

For more information read the `Frontend Localization Guide <http://typo3.org/documentation/document-library/core-documentation/doc_l10nguide/current/>`__ in the TYPO3 core documentation.
