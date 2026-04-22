<?php

// plugin information

$pluginname = "account_register";
$description = "Allow user to register with a simple form";
$link_parent = "settings";

// query to create and drop the table

// REMEMBER: add all pages to section tables and also settings pages

$query_create_table = "CREATE TABLE IF NOT EXISTS  " . $prefix . "register_account_temp (
   id INT ( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   email VARCHAR(250) NOT NULL,
   username VARCHAR(250) NOT NULL,
   password VARCHAR(250) NOT NULL,
   avatar VARCHAR(255) DEFAULT 'default.png',
   details TEXT DEFAULT NULL,
   details_opt TEXT DEFAULT NULL,
   token VARCHAR(250) NOT NULL,
   expDate DATETIME NOT NULL);
   INSERT INTO " . $prefix . "settings
   (name,value)
   VALUES ('reg_role','Manager');";

$menu_link = [[
   'link' => 'link_parent',
   'child' => [
      [
         'link' => 'setRegister',
         'label' => 'Account register',
         'icon' => 'person-badge',
         'show_menu' => '1'
      ]
   ]
]];

$query_drop_table = "DROP TABLE  " . $prefix . "register_account_temp;
                     DELETE FROM " . $prefix . "settings WHERE name = 'reg_role'";
