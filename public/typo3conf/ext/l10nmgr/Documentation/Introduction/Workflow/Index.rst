.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.rst.txt

.. _workflow:

Localization of TYPO3 websites
==============================
According to the adopted localization concept, new localizations of a web page and their respective content elements
are mainly realized either with language overlays or with a one-tree-per-language approach, where the whole source page
tree is copied (sometimes onto a different TYPO3 installation).

Although TYPO3 comes with all necessary localization features, localization of larger TYPO3 web sites is very often a
tedious and time-consuming task.
Translation of pages and content elements is often done inside TYPO3 by manually  localizing (translating)  a web
page and copying the default content elements, which are then overwritten with the translation. This requires translators
to have access to and knowledge about TYPO3 (how to access TYPO3, how to use the RTE, how to localize or translate a
page, etc.). While translating within TYPO3, translators do not have support from professional translation tools – such
as translation memories, terminology databases or spell-checkers – which usually guarantee consistency of translations
and terminology, as well as the efficiency of the translation process. This makes translators and translation service
providers (as well as TYPO3 administrators) somewhat unhappy...

The other possible route to localizing a TYPO3 website is to dump the database tables containing the localizable
information (pages, tt_content,...) e. g. into *.csv format, then to translate the content and import it into another
TYPO3 installation. This approach enables  the use of professional translation tools but requires the database tables
to be translated as a whole, as no subset can be exported. Furthermore, one has to cope with lowlevel problems such as
choosing the right separator. Little programming or SQL knowledge is required. However, this approach  works only for
the one-tree-per-language approach.
