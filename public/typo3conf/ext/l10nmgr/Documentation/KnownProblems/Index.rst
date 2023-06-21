.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.rst.txt

.. _knownProblems:

Known problems
==============

.. toctree::
	:maxdepth: 5
	:titlesonly:

- Performance issues in the context of very large websites during export & import of translations. Temporary solution
is to increase the maximum execution time of PHP scripts (php.ini) and Apache processes (httpd.conf). Another option is
to split the export/import files into smaller chunks. Just make sure that you have the head section in the XML and that
you don't cut the :code:`<pageGrp>` element.

- FAL compatibility issues in version 6.2: Exporting file references and meta data for translation is currently not
possible as FAL uses a different mechanism for storing and localizing data. This results sometimes in duplicated images
in the frontend apart from the meta data not being translated. Tip: Don't select tables related to :code:`sys_file_references`
for export. The issue will be addressed during a code sprint in October 2014.

Note: Please contact info@loctimize.com if you are interested in sponsoring the next code sprint.

TYPO3 Forge: http://forge.typo3.org/projects/show/extension-l10nmgr
