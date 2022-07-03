<?php
// HTTP
define('HTTP_SERVER', 'http://localhost/noteactive/caseapi/');

// HTTPS
define('HTTPS_SERVER', 'http://localhost/noteactive/caseapi/');

// DIR
define('DIR_APPLICATION', 'D:\xampp\htdocs\noteactive/caseapi/sites/');
define('DIR_SYSTEM', 'D:\xampp\htdocs\noteactive/system/');
define('DIR_DATABASE', 'D:\xampp\htdocs\noteactive/system/database/');
define('DIR_LANGUAGE', 'D:\xampp\htdocs\noteactive/caseapi/sites/language/');
define('DIR_TEMPLATE', 'D:\xampp\htdocs\noteactive/sites/view/');
define('DIR_CONFIG', 'D:\xampp\htdocs\noteactive/system/config/');
define('DIR_IMAGE', 'D:\xampp\htdocs\noteactive/image/');
define('DIR_CACHE', 'D:\xampp\htdocs\noteactive/system/cache/');
define('DIR_DOWNLOAD', 'D:\xampp\htdocs\noteactive/download/');
define('DIR_LOGS', 'D:\xampp\htdocs\noteactive/system/logs/');
//define('LICENCE_URL', 'http://dev.noteactive.com/licservices/web_services.php');
define('LICENCE_URL', 'http://licenseserver.noteactive.com/web_services.php');

define('DIR_APPLICATION_AWS', 'D:\xampp\htdocs\noteactive/aws/');



define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('SESSION_NAME_1', 'NOTEACIVESESSID');

define('SFTPDEFAULTBUCKET', 'awssftpfiles');

define('KILLSESSION', '1'); 
//define('SFTPCONECTION', '0'); 


/*
// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'demo-newdb.cyxsij4cwahs.us-east-1.rds.amazonaws.com');
define('DB_USERNAME', 'demousr');
define('DB_PASSWORD', 'Servitium2016#');
define('DB_DATABASE', 'demonewdb');
define('DB_PREFIX', 'dg_');
*/

define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'noteactivenew');
define('DB_PREFIX', 'dg_');



/*
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'aurorademo.cyxsij4cwahs.us-east-1.rds.amazonaws.com');
define('DB_USERNAME', 'demousr');
define('DB_PASSWORD', 'Servitium2016#');
define('DB_DATABASE', 'demonewdb');
define('DB_PREFIX', 'dg_');
*/
define('DYNAMODBCHECKLIST', 'checklist');
define('DYNAMODBINCIDENT', 'incidentform');

define('incident_severity1', '1 - Minor Injury, Minor Arrest, Illness, Running Away');
define('incident_severity2', '2 - Baker ACT, Running Away, Use of Illegal Drugs/Substances, Minor Abuse Offense');
define('incident_severity3', '3 - Major Injury, Natural Disaster, Felony Arrest,');
define('incident_severity4', '4 - Aggravated Battery, Inappropriate Sexual Contact, Suicide Attempt, Major Abuse Offense');
define('incident_severity5', '5 -  Death');
define('USERDEMO', '1'); 

define('IS_WAREHOUSE', '1');
define('IS_INVENTORY', '1');

define('CUSTOME_FORMID', '1');
define('CUSTOM_USERPIC', '5');
define('ALLTASKTYPE', '1');

define('SYSTEM_GENERATED', 'System Generated');
define('SYSTEM_GENERATED_PIN', 'NA1');
define('AZURE_URL', 'https://noteactive.blob.core.windows.net/dev1/');

define('AWS_URL', 'https://d90uo344z7188.cloudfront.net/');


define('REALTIMEURL', 'https://api.powerbi.com/beta/6372002c-2860-43a8-b8a2-7fa21b5b117f/datasets/c8b858d6-0126-46bd-8348-6b923949dc4e/rows?key=d6uMBzr70XSGGHaNsxM3%2BN9olRDs54OFU7P%2B%2FXN7R0EwzGjA6HSdVyV5RcDScL%2B4GFNdIIRQrKKrsF0Hulr%2BIQ%3D%3D');

define('REALTIMEREPORTID', 'e5f09f8f-afb1-48e3-8c92-834eaab14513');
define('REALTIMEREPORTID2', 'c37232f6-081e-481c-8023-aab56906055e');

/*
define('TAG_EXTID', 'text_92710969');
define('TAG_SSN', 'text_59058963');
define('TAG_FNAME', 'text_59815482');

define('TAG_LNAME', 'text_2637670');
define('TAG_DOB', 'date_70767270');
define('TAG_SCREENING', 'date_90767736');
define('TAG_AGE', '');

define('TAG_GENDER', 'select_40322663');
define('TAG_PHONE', 'text_84980038');
define('TAG_ADDRESS', 'text_67156164');
define('TAG_ADDRESS2', '');


define('TAG_EXTID', 'text_96070445');
define('TAG_SSN', 'text_5257010');
define('TAG_FNAME', 'text_7396344');
define('TAG_MNAME', 'text_5616550');
define('TAG_LNAME', 'text_16995055');
define('TAG_DOB', 'date_90767736');
define('TAG_SCREENING', 'date_3967987');
define('TAG_AGE', '');

define('TAG_GENDER', 'select_48640992');
define('TAG_PHONE', 'text_86460809');
define('TAG_ADDRESS', 'text_82454490');
define('TAG_ADDRESS2', '');
*/

define('IS_MAUTIPLE', '1');
define('IS_STOP_AJAX', '11-2017');


//define('CUSTOME_INTAKEID', '2');
//define('CUSTOME_INTAKEID', '26');
define('CUSTOME_HOMEVISIT', '12');
define('CUSTOME_DISCHARGE', '34');

define('CONFIG_REVIEW_NOTES', 'https://noteactiveicons.s3.amazonaws.com/591eaeb4b1a13.png');
define('INTAKE_ICON', 'https://noteactiveicons.s3.amazonaws.com/590b20d7a910f.png');
define('DISCHARGE_ICON', 'https://noteactiveicons.s3.amazonaws.com/589a4edf67855.png');
define('HEADCOUNT_ICON', 'https://noteactiveicons.s3.amazonaws.com/589a4fb5b5679.png');
define('MEDICATION_ICON', 'https://noteactiveicons.s3.amazonaws.com/56f46413dc20d.png');

define('GOOGLE_API_KEY', 'AIzaSyDlO8-Wh6kuj9tbhdPSSbOygpoEUe6OAas');
define('CONFIG_LIMIT', '5');

/*** warehouse databse connection setting */

define('NEWDB_DRIVER', 'mysqli');
define('NEWDB_HOSTNAME', 'localhost');
define('NEWDB_USERNAME', 'root');
define('NEWDB_PASSWORD', '');
define('NEWDB_DATABASE', 'noteactivenew');
define('NEWDB_PREFIX', 'dg_');

define('FNNEWDB_DRIVER', 'mysql');
define('FNNEWDB_HOSTNAME', 'localhost');
define('FNNEWDB_USERNAME', 'root');
define('FNNEWDB_PASSWORD', '');
define('FNNEWDB_DATABASE', 'noteactivenew');
define('FNNEWDB_PREFIX', 'dg_');

?>