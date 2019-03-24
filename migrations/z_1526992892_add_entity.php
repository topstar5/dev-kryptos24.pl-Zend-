<?php

namespace migrations;

require_once __DIR__ .'/lib.inc.php';

// Write you code here
//
// You can use
// db_query('some sql');  for quering
// db_pdo()->...;         some pdo functions

$stm = db_pdo()->prepare(
	"
	INSERT INTO `entities` ( `author_id`, `system_name`, `title`, `config`, `created_at`, `updated_at`) VALUES
( 0, 'datagrid', 'Siatka danych', '{\"type\":\"entry\",\"element\":{\"tag\":\"bs.datagrid\"}}', '0000-00-00 00:00:00', NULL)
	"
);
$stm->execute();

