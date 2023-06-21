.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.rst.txt

.. _xmlExchangeFormat:

XML exchange format
===================

The XML format used by the l10nmgr is a simple XML format that can be used with all state-of-the-art localisation and
translation tools. The Localization Manager ships with the required settings files that specify translatable and
non-translatable elements. Currently settings files are available for the following tools: across, DéjàVu, SDL Trados
and SDL Passolo. Settings files for other tools can easily be set up.
The file format for the export & import of translatable data of a TYPO3 website is a localization-friendly XML format.
The default encoding of XML files is UTF-8.
Context information
One important feature of the XML format is that the translatable contents (<data> elements) are grouped according to
the context of the page on which they occur (<pageGrp>).
To get a preview of the source web page, the URL can be reassembled by combining the <baseURL> with the ID attribute of
the <pageGrp> element and the ID of the source language (<sysLang>):

`http://\<baseURL\>/index.php?id=pageGrp:id&L=\<sysLang\> <http://\<baseURL\>/index.php?id=pageGrp:id&L=\<sysLang\>>`_


Structure
---------

+----------------------+---------------------------------------------------------------+
| Element name         | Semantics                                                     |
+======================+===============================================================+
| \<TYPO3L10N\>        | Root element                                                  |
+----------------------+---------------------------------------------------------------+
| \<head\>             | Header with meta information                                  |
+----------------------+---------------------------------------------------------------+
| \<t3_l10ncfg\>       | ID of Localization Manager configuration used for the export. |
+----------------------+---------------------------------------------------------------+
| \<t3_sysLang\>       | ID of target language (equal to IDs of website languages)     |
+----------------------+---------------------------------------------------------------+
| \<t3_sourceLang\>    | ISO-639-1 language code for source language                   |
+----------------------+---------------------------------------------------------------+
| \<t3_targetLang\>    | ISO-639-1 language code for target language                   |
+----------------------+---------------------------------------------------------------+
| \<t3_baseURL\>       | Base URL of TYPO3-Website to be translated                    |
+----------------------+---------------------------------------------------------------+
| \<t3_workspaceId\>   | ID of workspace from which has been exported                  |
+----------------------+---------------------------------------------------------------+
| \<t3_count\>         | Number of exported data sets                                  |
+----------------------+---------------------------------------------------------------+
| \<t3_wordCount\>     | Word count of source text                                     |
+----------------------+---------------------------------------------------------------+
| \<t3_internal\>      | Internal messages                                             |
+----------------------+---------------------------------------------------------------+
| \<t3_skippedItem\>   | Elements skipped during export                                |
+----------------------+---------------------------------------------------------------+
| \<t3_description\>   | Error messages                                                |
+----------------------+---------------------------------------------------------------+
| \<t3_key\>           | Key of skipped element                                        |
+----------------------+---------------------------------------------------------------+
| \<t3_formatVersion\> | Version number of XML format                                  |
+----------------------+---------------------------------------------------------------+
| \<pageGrp\>          | Grouping element that embraces all translatable elements of a |
|                      | page. The id- attribute indicates the page ID. The sourceUrl  |
|                      | attribute indicates the url of the source language webpage.   |
+----------------------+---------------------------------------------------------------+
| \<data\>             | Translatable contents. Used attributes: table = database      |
|                      | table from which contents has been exported elementUid = UID  |
|                      | of localizable data set key = Trigger for localization        |
|                      | command. Syntax: table:NEW/ t3_sysLang/elementUid:fieldname   |
|                      | (Initial localization) table:elementUid:fieldname (Update     |
|                      | localization, elementUid is ID of data set to be updated)     |
+----------------------+---------------------------------------------------------------+
|                      | <data> can further contain all kind HTML elements. Number and |
|                      | type of elements can vary for every TYPO3 installation.       |
+----------------------+---------------------------------------------------------------+
