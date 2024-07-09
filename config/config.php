<?php
const DEV_MODE = true;
const DOC_ROOT = '/public/';
const ROOT_FOLDER = '/public/';
const DOMAIN = 'http://localhost:';
//Database connection
$type = 'mysql';
$host = 'localhost';
$port = '3306';
$dbname = 'cms_edvgraz';
$user_name = 'cms_edvgraz';
$password = '';
$dsn = "$type:host=$host:$port;dbname=$dbname";

//File upload
const MEDIA_TYPES = ['image/jpeg', 'image/png'];
const FILE_EXTENSIONS = ['jpg', 'jpeg', 'png'];
const MAX_FILE_SIZE = 1024 * 1024 * 2;
define ("UPLOAD_DIR", dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . ROOT_FOLDER . DIRECTORY_SEPARATOR . '/uploads/');

$mail_config = [
    'host' => 'smtp-mail.outlook.com',
    'port' => 587,
    'username' => 'meine@mail.net',
    'password' => 'Mein Passwort',
    'sec' => 'tls',
    'admin_mail' => 'meine@mail.net',
    'debug' => (DEV_MODE ? 2 : 0)
];