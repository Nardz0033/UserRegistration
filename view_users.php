<?php

require_once __DIR__ . '/includes/config.php';
session_init();
require_login();

if (($_SESSION['user_level'] ?? '') !== 'admin') {
    http_response_code(403);
    redirect('index.php');
}

$dbc    = get_db();
$result = $dbc->query('SELECT user_id, email, first_name, last_name, active, registration_date, user_level FROM users ORDER BY registration_date DESC');
$users  = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users &mdash; <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.php'; ?>
<main>
    <div class="table-wrap">
        <h1>Registered Users</h1>
        <p class="table-count"><?= count($users) ?> user<?= count($users) !== 1 ? 's' : '' ?> found.</p>

        <?php if (empty($users)): ?>
            <p>No users registered yet.</p>
        <?php else: ?>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Active</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $row): ?>
                            <tr>
                                <td><?= (int) $row['user_id'] ?></td>
                                <td><?= h($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                <td><?= h($row['email']) ?></td>
                                <td><span class="badge badge-<?= $row['user_level'] === 'admin' ? 'admin' : 'user' ?>"><?= h($row['user_level']) ?></span></td>
                                <td><?= $row['active'] ? '<span class="badge badge-active">Yes</span>' : '<span class="badge badge-inactive">No</span>' ?></td>
                                <td><?= h($row['registration_date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
