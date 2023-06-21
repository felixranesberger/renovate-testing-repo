.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.rst.txt

.. _translationWithoutL10Nmgr:

Translation workflow without l10nmgr
====================================

Translators  work directly in TYPO3. They do not have support from translation tools, translation memories and
terminology databases or spell checkers. For each page to be translated the following steps have to be performed:

#. Access the page to be translated,
#. Translate the web page into $LANGUAGE
#. Insert translations for all fields in the new translation form
#. Copy default content elements
#. Open each content element to overwrite the sourcetext with the translation.

To translate e.g. 50 web pages containing 300 content elements you would need at least 800
mouse clicks – not counting the typing of the translation.

|img-translation-workflow-without-l10nmgr|
