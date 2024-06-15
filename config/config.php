<?php
return [
    'db_host' => htmlspecialchars(getent('DB_HOST'), ENT_QUOTES, 'UTF-8'),
    'db_user' => getent('DB_USER'),
    'db_pass' => getent('DB_PASS'),
    'db_name' => getent('DB_NAME'),
    'env_msg' => getent('MSG'),
    'env_value1' => getent('VALUE1')
];
?>
