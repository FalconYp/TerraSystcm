<?php

$user_id = $_SESSION['users_id'];

$query = $conn->prepare("
SELECT * FROM notifications
WHERE users_id = ?
ORDER BY created_at DESC
");

$query->bind_param("i", $user_id);
$query->execute();

$result = $query->get_result();

?>

<div class="notification-box">

    <h5>Notifications</h5>

    <?php while($row = $result->fetch_assoc()): ?>

        <div class="alert alert-info">

            <?php echo $row['n_message']; ?>

        </div>

    <?php endwhile; ?>

</div>