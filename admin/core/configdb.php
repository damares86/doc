<?php

require '../vendor/autoload.php';    // If installed via composer
$debug = new \bdk\Debug(array(
  'collect' => true,
  'output' => true,
));


##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################



// create Database class
if (!is_file('../class/Database.php')) {
  $db_name = filter_input(INPUT_POST, "dbname");
  $username = filter_input(INPUT_POST, "username");
  $db_password = filter_input(INPUT_POST, "db_password");
  $host = filter_input(INPUT_POST, "host");

  $file_handle = fopen('../class/Database.php', 'w');

  $content = <<<PHP
<?php
class Database {
    public \$db_name = "{$db_name}";
    public \$username = "{$username}";
    public \$password = "{$db_password}";
    public \$host = "{$host}";
    public \$conn;
    public \$prx;

    public function getConnection() {
        \$this->conn = null;
        try {
            \$this->conn = new PDO(
                "mysql:host=" . \$this->host . ";dbname=" . \$this->db_name . ";charset=utf8mb4",
                \$this->username,
                \$this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException \$exception) {
            echo "Connection error: " . \$exception->getMessage();
        }
        return \$this->conn;
    }
}
?>
PHP;

  fwrite($file_handle, $content);
  fclose($file_handle);
}


chmod('../class/Database.php', 0777);

include("../class/Database.php");

$database = new Database();
$db = $database->getConnection();

$prefix = "";
if ($_POST['prefix']) {
  $prefix = $_POST['prefix'] . "_";
}
// recall of all the classes
$files = glob("../class/*.php", GLOB_BRACE);
rsort($files);
// creation of the file with all the initialization of the classes
if (!is_file('../inc/class_initialize.php')) {
  $file_handle = fopen('../inc/class_initialize.php', 'w');
  fwrite($file_handle, '<?php');
  fwrite($file_handle, "\n");
  foreach ($files as $filename) {
    $nomefile = pathinfo($filename);
    $file = $nomefile['filename'];
    $file_var = strtolower($file);
    fwrite($file_handle, '$' . $file_var . ' = new ' . $file . '($db);');
    fwrite($file_handle, "\n");
  }
  if ($prefix) {
    fwrite($file_handle, '$common->prx = "' . $prefix . '_";');
    fwrite($file_handle, "\n");
  }
  fwrite($file_handle, "?>");
  chmod('../inc/class_initialize.php', 0777);
}


// store the data given by user

$user_email = $_POST['email'];
$password = $_POST['password'];
$password_hash = password_hash($password, PASSWORD_BCRYPT);


// prefix optionally given by user
// and save it in a file



$file_handle = fopen('../core/prefix.php', 'w');
fwrite($file_handle, '<?php');
fwrite($file_handle, "\n");
fwrite($file_handle, '$prefix="' . $prefix . '";');
fwrite($file_handle, "\n");
fwrite($file_handle, '?>');

chmod('../core/prefix.php', 0777);


// TODO: check on URL in order to avoid multiple use of an istance of cms


/////////////////////////////////////////////////////////////

// create the db tables if not exists

/////////////////////////////////////////////////////////////
$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "files (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    label VARCHAR(255) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "accounts (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT 'default.png',
    details TEXT DEFAULT NULL,
    details_opt TEXT DEFAULT NULL,
    auth_token VARCHAR(255) DEFAULT 'none',
    last_login datetime DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "roles (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    rolename VARCHAR(255) NOT NULL,
    redirect VARCHAR(255) DEFAULT 'none'
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "accountsRoles (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    account_id INT(5) NOT NULL,
    role_id INT(5) NOT NULL,
    FOREIGN KEY (account_id) REFERENCES " . $prefix . "accounts(id),
    FOREIGN KEY (role_id) REFERENCES " . $prefix . "roles(id)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "sectionParent (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    link VARCHAR(255) NOT NULL,
    label VARCHAR(255) NOT NULL,
    icon VARCHAR(255) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "sectionChild (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    link VARCHAR(255) NOT NULL,
    label VARCHAR(255) NOT NULL,
    icon VARCHAR(255) NOT NULL,
    parent_id INT(5) NOT NULL,
    show_menu INT(1) DEFAULT 1
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "rolesSection (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    section_id VARCHAR(255) NOT NULL,
    role_id INT(5) DEFAULT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "rolesSectionChild (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    section_id VARCHAR(255) NOT NULL,
    role_id INT(5) DEFAULT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "password_reset_temp (
    email VARCHAR(250) NOT NULL PRIMARY KEY,
    token VARCHAR(250) NOT NULL,
    expDate DATETIME NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "settings (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    value VARCHAR(255) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "plugins (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pluginname VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    installed INT(1) DEFAULT 0,
    active INT(1) DEFAULT 0
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->query("CREATE TABLE IF NOT EXISTS " . $prefix . "home (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    content TEXT,
    size INT(2) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

/////////////////////////////////////////////////////////////

// insert data in the db tables

/////////////////////////////////////////////////////////////

$db->query("INSERT INTO " . $prefix . "accounts
(id, username, password,email, avatar)
VALUES ('1','dmweblab', '$2y$10\$EibgaewcRGXQhrMQswfqRuJQeWKMkGxHcWC62HV1g39CmtuU3lG9.','davidemasera@gmail.com','sd.png')");

$db->query("INSERT INTO " . $prefix . "accounts
(id, username, password,email)
VALUES ('2','admin', '" . $password_hash . "','" . $user_email . "')");

$db->query("INSERT INTO " . $prefix . "roles
                            (id, rolename)
                            VALUES ('1','Root')");

$db->query("INSERT INTO " . $prefix . "roles
                            (id, rolename)
                            VALUES ('2','Admin')");

// $db->query("INSERT INTO ".$prefix."roles
//                             (id, rolename)
//                             VALUES ('3','Manager')");

// $db->query("INSERT INTO ".$prefix."roles
//                             (id, rolename)
//                             VALUES ('4','Subscriber')");

$db->query("INSERT INTO " . $prefix . "accountsRoles
                            (id, account_id,role_id)
                            VALUES ('1','1','1')");

$db->query("INSERT INTO " . $prefix . "accountsRoles
                            (id, account_id,role_id)
                            VALUES ('2','2','2')");

$db->query("INSERT INTO " . $prefix . "settings
                            (id, name,value)
                            VALUES ('1','lang','en')");

$db->query("INSERT INTO " . $prefix . "settings
                            (id, name,value)
                            VALUES ('2','noreply','noreply@mail.com')");

$db->query("INSERT INTO " . $prefix . "settings
                            (id, name,value)
                            VALUES ('3','license','none')");

$db->query("INSERT INTO " . $prefix . "settings
                            (id, name,value)
                            VALUES ('4','debug','0')");

$db->query("INSERT INTO " . $prefix . "settings
                            (id, name,value)
                            VALUES ('5','layout','v')");

$db->query("INSERT INTO " . $prefix . "settings
                            (id, name,value)
                            VALUES ('6','role_redirect','0')");

// insert the section for the sidebar / home link management

$db->query("INSERT INTO " . $prefix . "sectionParent
                            (link,label,icon)
                            VALUES ('index','Dashboard','grid-fill')");

$db->query("INSERT INTO " . $prefix . "sectionParent
                            (link,label,icon)
                            VALUES ('accounts','Accounts','people-fill')");

$db->query("INSERT INTO " . $prefix . "sectionChild
                            (link,label,icon,parent_id)
                            VALUES ('allAccounts','All accounts','people-fill','2')");

$db->query("INSERT INTO " . $prefix . "sectionChild
                            (link,label,icon,parent_id)
                            VALUES ('addAccount','Add account','person-plus-fill','2')");

$db->query("INSERT INTO " . $prefix . "sectionChild
                            (link,label,icon,parent_id,show_menu)
                            VALUES ('editAccount','Edit account','icon','2','0')");

$db->query("INSERT INTO " . $prefix . "sectionChild
                            (link,label,icon,parent_id)
                            VALUES ('allRoles','All Roles','key-fill','2')");

$db->query("INSERT INTO " . $prefix . "sectionChild
                            (link,label,icon,parent_id,show_menu)
                            VALUES ('addRole','Add role','icon','2','0')");

$db->query("INSERT INTO " . $prefix . "sectionChild
                            (link,label,icon,parent_id,show_menu)
                            VALUES ('editRole','Edit role','icon','2','0')");

$db->query("INSERT INTO " . $prefix . "sectionParent
                            (link,label,icon)
                            VALUES ('allFiles','Files','folder-fill')");

$db->query("INSERT INTO " . $prefix . "sectionParent
                            (link,label,icon)
                            VALUES ('allSettings','Settings','tools')");

$db->query("INSERT INTO " . $prefix . "sectionParent
                            (link,label,icon)
                            VALUES ('damares','Damares','dice-6-fill')");

$db->query("INSERT INTO " . $prefix . "sectionParent
                            (link,label,icon)
                            VALUES ('allPlugins','Modules','plus-circle-fill')");

///////////////////////////////////////////////////////////////

///  ADD THE SECTION PERMISSION FOR THE ROLES ROOT AND ADMIN

///////////////////////////////////////////////////////////////    

$db->query("INSERT INTO " . $prefix . "rolesSection
                            (id, section_id,role_id)
                            VALUES ('1','1,2,3,4,5,6','1')");

$db->query("INSERT INTO " . $prefix . "rolesSection
                            (id, section_id,role_id)
                            VALUES ('2','1,2,3','2')");

$db->query("INSERT INTO " . $prefix . "rolesSectionChild
                            (id, section_id,role_id)
                            VALUES ('1','1,2,3,4,5,6,7,8,9,10','1')");

$db->query("INSERT INTO " . $prefix . "rolesSectionChild
                            (id, section_id,role_id)
                            VALUES ('2','1,2,3,4,5,6,7,8,9,10','2')");
// homepage blocks                            

$db->query("INSERT INTO " . $prefix . "home
                            (id, content,size)
                            VALUES ('1','welcome.php','6')");

$db->query("INSERT INTO " . $prefix . "home
                            (id, content,size)
                            VALUES ('2','manuals.php','3')");

$db->query("INSERT INTO " . $prefix . "home
                              (id, content,size)
                              VALUES ('3','last_login.php','3')");

// scan the plugin directory and insert the plugin by folder's name

$plugins = scandir('../plugins');
$exclude = array('..', '.', ".gitkeep", "base_module");
$plugin_id = 1;

foreach ($plugins as $key => $value) {
  if (!in_array($value, $exclude)) {
    require "../plugins/$value/config.php";
    $db->query("INSERT INTO " . $prefix . "plugins
                            (id, pluginname,description,installed,active)
                            VALUES ('" . $plugin_id . "','" . $value . "','" . $description . "','0','0')");
    $plugin_id++;
  }
}




header("Location: ../../login/auth-login.php");
