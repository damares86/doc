<?php

// plugin information

$pluginname = "calendar";
$description = "Manages events and shows in a calendar";
$link_parent = "Calendar";

// query to create and drop the table

// REMEMBER: add all pages to section tables and also settings pages

$query_create_table = "CREATE TABLE " . $prefix . "calendar_events (
      id INT AUTO_INCREMENT PRIMARY KEY,
      title VARCHAR(255) NOT NULL,
      start DATETIME NOT NULL,
      end DATETIME NOT NULL,
      note TEXT DEFAULT NULL,
      url VARCHAR(255) DEFAULT NULL,
      cat_id INT(5) DEFAULT NULL
      );
      CREATE TABLE IF NOT EXISTS " . $prefix . "calendar_cat
      ( id INT ( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      cat_name VARCHAR(255) NOT NULL,
      cat_color VARCHAR(7) DEFAULT '#008db1');
      INSERT INTO " . $prefix . "calendar_cat
      (id, cat_name, cat_color)
      VALUES ('1','default','#008db1')";

$menu_link = [[
      'link' => 'calendar',
      'label' => 'Calendar',
      'icon' => 'calendar-week',
      'child' => [
            [
                  'link' => 'calendar',
                  'label' => 'Show calendar',
                  'icon' => 'calendar3',
                  'show_menu' => 1
            ],
            [
                  'link' => 'allCalendars',
                  'label' => 'All categories',
                  'icon' => 'calendar2-range',
                  'show_menu' => 1
            ],
            [
                  'link' => 'addCalendar',
                  'label' => 'Add a category',
                  'icon' => 'calendar-plus',
                  'show_menu' => 1
            ],
            [
                  'link' => 'editCalendar',
                  'label' => 'Edit a category',
                  'icon' => 'calendar-plus',
                  'show_menu' => 0
            ]
      ]
]];

$query_drop_table = "DROP TABLE  " . $prefix . "calendar_cat," . $prefix . "calendar_events";
