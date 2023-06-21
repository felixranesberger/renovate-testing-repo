.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.rst.txt

.. _options:

Options
=======

.. toctree::
	:maxdepth: 5
	:titlesonly:

Configuration options available during installation of extension:

- Enable hidden languages (enable_hidden_languages): Decide whether to make available hidden system languages for export/import.
- Send email notification (enable_notification): Send email otification upon completion of export/import via CLI.
- Enable FTP upload (enable FTP): Use this for uploading exported files to translation service providers' FTP server. Provide login credentials below.
- L10N Manager configurations (l10nmgr_cfg): List uids of configuration records for automation of export via CLI script. Use a comma for separating multiple uids.
- L10N Manager target languages: List of uids of sys_languages for automated export for multiple target languages via CLI
- Email address: for notification emails in CLI mode

  - Email addres (email_sender): The email address of the sender.
  - Email address (email_recipient): Email address of the recipient, e.g. translator@lsp.com.
  - Organisation (email_sender_organisation): The name of the sender's organisation, e.g. Your Company.
  - Attachment (email_attachment): Attach exported files to notification email.

- FTP server details

  - FTP server address (ftp_server): The FTP server address where l10nmgr exports should be saved, e.g. ftp.yourdomain.com.
  - FTP server upload path (ftp_server_path): Path o nFTP server where to upload exports.
  - FTP server login (ftp_server_username): The username of your FTP account..
  - FTP server password (ftp_server_password): The password of your FTP account.

- CLI options

  - Parallel import jobs (service_children): Maximum number of parallel import jobs (processes) (Default = 2)
  - User name for CLI authentication (service_user)
  - Password for CLI authentication (service_pwd)
  - Encryption key for CLI authentication (service_enc): Encryption key for CLI authentication
