<?php

namespace migrations;

require_once __DIR__ .'/lib.inc.php';

// Write you code here
//
// You can use
// db_query('some sql');  for quering
// db_pdo()->...;         some pdo functions

db_query('UPDATE `entities` SET `config`=\'{\"type\":\"datetime\",\"element\":{\"tag\":\"bs.varchar\",\"class\":\"datetimepicker-input\"}}\' WHERE `system_name`=\'datetime\'');