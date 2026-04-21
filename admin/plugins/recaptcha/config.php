<?php

// plugin information

$pluginname = "recaptcha";
$description = "Use Google recaptcha to protect your forms";
$link_parent = "recaptcha";

// query to create and drop the table

// REMEMBER: add all pages to section tables and also settings pages

$query_create_table = "CREATE TABLE IF NOT EXISTS " . $prefix . "verify (
   `id` INT ( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   `public` varchar(250) NOT NULL,
   `secret` varchar(250) NOT NULL);
   INSERT INTO " . $prefix . "verify
   (id, public, secret) 
   VALUES ('1','PUBLIC_KEY', 'SECRET_KEY');";

$menu_link = [[
   'link' => 'setRecaptcha',
   'label' => 'Recaptcha',
   'icon' => 'lock-fill',
   'show_menu' => '1'
]];

$query_drop_table = "DROP TABLE  " . $prefix . "verify;";
