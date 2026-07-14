<?php

function insertWorkflowStage(
    $conn,
    $application_id,
    $actor_role,
    $status
){

    $sql = "
    INSERT INTO workflow_stages(
        land_applications_id,
        actor_role,
        application_status
    )
    VALUES (?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iss",
        $application_id,
        $actor_role,
        $status
    );

    return $stmt->execute();
}