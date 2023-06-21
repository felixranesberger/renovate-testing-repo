.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.rst.txt

.. _hooks:

Hooks
============

.. toctree::
	:maxdepth: 5
	:titlesonly:

+--------------------------+--------------------------------+
| Name                     | Method                         |
+==========================+================================+
| savePreProcess           | processBeforeSaving            |
+--------------------------+--------------------------------+
| savePostProcess          | processAfterSaving             |
+--------------------------+--------------------------------+
| exportCatXmlPreProcess   | processBeforeExportingCatXml   |
+--------------------------+--------------------------------+
| exportExcelXmlPreProcess | processBeforeExportingExcelXml |
+--------------------------+--------------------------------+
| transformation           | transform_rte                  |
+--------------------------+--------------------------------+
| transformation           | transform_db                   |
+--------------------------+--------------------------------+
