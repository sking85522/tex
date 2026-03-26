<?php
session_start();
include '../includes/db.php';

// PandaPHP Dataframe Simulation for exporting intelligent project reports
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'super_admin') {
    die("Access Denied.");
}

$stmt = $pdo->query("SELECT cp.id, u.username as client, cp.project_name, cp.status_phase, cp.progress_percent, cp.total_cost, cp.paid_amount FROM client_projects cp JOIN users u ON cp.user_id = u.id");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($data)) {
    die("No data to export.");
}

// Simulation of PandaPHP groupby('status_phase')->sum('total_cost')
$aggregated = [];
foreach($data as $row) {
    $phase = $row['status_phase'];
    if (!isset($aggregated[$phase])) {
        $aggregated[$phase] = ['Total_Projects' => 0, 'Total_Revenue' => 0];
    }
    $aggregated[$phase]['Total_Projects']++;
    $aggregated[$phase]['Total_Revenue'] += $row['total_cost'];
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="TechElevateX_Project_Report_'.date('Ymd').'.csv"');

$output = fopen('php://output', 'w');

// Write Raw DataFrame
fputcsv($output, ['--- RAW PROJECT DATAFRAME ---']);
fputcsv($output, array_keys($data[0]));
foreach($data as $row) {
    fputcsv($output, $row);
}

// Write Aggregated DataFrame
fputcsv($output, []);
fputcsv($output, ['--- PANDAPHP AGGREGATED ANALYTICS (By Phase) ---']);
fputcsv($output, ['Phase', 'Total Projects', 'Total Expected Revenue']);
foreach($aggregated as $phase => $stats) {
    fputcsv($output, [$phase, $stats['Total_Projects'], $stats['Total_Revenue']]);
}

fclose($output);
exit();
?>
