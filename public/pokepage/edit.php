<?php

$id = $_GET['id'] ?? '';
$query = ['page' => 'pokemon-edit'];
if (is_string($id)) {
    $query['id'] = $id;
}
header('Location: /?' . http_build_query($query), true, 303);
exit;
