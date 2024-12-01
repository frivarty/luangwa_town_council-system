<!DOCTYPE html>
<html lang="en">
    <head>
     <title>

     </title>   
    </head>
    <body>

    <?php
include('db_config.php');

try {
    $stmt = $pdo->query("SELECT * FROM audit_log ORDER BY change_time DESC");
    $auditLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display audit log information
    echo "<h2>Audit Log</h2>";
    echo "<table>
            <tr>
                <th>Action</th>
                <th>Table</th>
                <th>Record ID</th>
                <th>Change Time</th>
            </tr>";
    foreach ($auditLogs as $log) {
        echo "<tr>
                <td>{$log['action']}</td>
                <td>{$log['table_name']}</td>
                <td>{$log['record_id']}</td>
                <td>{$log['change_time']}</td>
              </tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>


<!-- Include this script in your HTML, preferably at the end of the body -->
<script src="inactivity.js"></script>

    </body>
</html>