<?php

include '../config/database.php';

$target_dir = "../uploads/";

$file_name = time() . '_' . basename($_FILES['document']['name']);

$target_file = $target_dir . $file_name;

move_uploaded_file($_FILES['document']['tmp_name'], $target_file);

mysqli_query($conn,
"INSERT INTO documents(
land_applications_id,
document_type,
file_path
)
VALUES(
'1',
'ID_CARD',
'$file_name'
)");

header('Location: dashboard.php');

?>