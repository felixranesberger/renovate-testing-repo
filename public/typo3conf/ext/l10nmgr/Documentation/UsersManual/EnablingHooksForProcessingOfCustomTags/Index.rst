.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.rst.txt

.. _enablingHooksForProcessingOfCustomTags:

Enabling hooks for processing of custom tags
============================================

.. toctree::
	:maxdepth: 5
	:titlesonly:


Extensions may add custom tags to RTE-enabled fields. First and foremost is the DAM, which adds tag :html:`<media>`. Upon
export to CAT XML, this tag must be interpreted (as when loaded into the RTE) otherwise it is exported with < and >
transformed to HTML entities, and reimported as such, breaking all links to RTE files.
Because there might be many different custom tags used in a TYPO3 installation we decided against including default
implementations. If you want to use e. g. the hook to transform the DAM media tag you can add the following PHP code to
your localconf:

.. code-block:: php

	$TYPO3_CONF_VARS['EXTCONF']['l10nmgr']['transformation']['txdam_media'] = 'EXT:dam/binding/mediatag/class.tx_dam_rtetransform_mediatag.php:&tx_dam_rtetransform_mediatag';
	$TYPO3_CONF_VARS['EXTCONF']['l10nmgr']['transformation']['ts_links'] = 'EXT:dam/binding/mediatag/class.tx_dam_rtetransform_ahref.php:&tx_dam_rtetransform_ahref';
