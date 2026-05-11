<?php

declare(strict_types=1);

/**
 * Older SQL dumps only define a minimal `submissions` table. The app expects energy-provider columns.
 * Adds any missing columns once per request (cheap SHOW COLUMNS + conditional ALTER).
 */
function energy_providers_ensure_submissions_schema(PDO $conn): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    $existing = [];
    try {
        foreach ($conn->query('SHOW COLUMNS FROM `submissions`')->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $existing[$row['Field']] = true;
        }
    } catch (PDOException $e) {
        return;
    }

    $add = [
        'idd' => 'INT NULL',
        'score' => 'DECIMAL(8,2) NOT NULL DEFAULT 0',
        'business_activeness' => 'INT NOT NULL DEFAULT 0',
        'date' => 'VARCHAR(64) NOT NULL DEFAULT \'\'',
        'category' => 'VARCHAR(128) NOT NULL DEFAULT \'\'',
        'image_url' => 'VARCHAR(2048) NOT NULL DEFAULT \'\'',
        'image' => 'VARCHAR(255) NOT NULL DEFAULT \'\'',
        'website' => 'VARCHAR(2048) NOT NULL DEFAULT \'\'',
        'tel' => 'VARCHAR(128) NOT NULL DEFAULT \'\'',
        'address' => 'VARCHAR(512) NOT NULL DEFAULT \'\'',
        'co2' => 'VARCHAR(64) NOT NULL DEFAULT \'\'',
        'nuclear_waste' => 'VARCHAR(64) NOT NULL DEFAULT \'\'',
        'coal' => 'VARCHAR(64) NOT NULL DEFAULT \'\'',
        'gas' => 'VARCHAR(64) NOT NULL DEFAULT \'\'',
        'nuclear' => 'VARCHAR(64) NOT NULL DEFAULT \'\'',
        'renewable' => 'VARCHAR(64) NOT NULL DEFAULT \'\'',
        'iepn' => 'VARCHAR(64) NOT NULL DEFAULT \'\'',
    ];

    foreach ($add as $col => $definition) {
        if (isset($existing[$col])) {
            continue;
        }
        try {
            $conn->exec("ALTER TABLE `submissions` ADD COLUMN `$col` $definition");
        } catch (PDOException $e) {
            // 1060: Duplicate column — race or manual add
            $msg = $e->getMessage();
            if (! preg_match('/1060|42S21|duplicate column/i', $msg)) {
                throw $e;
            }
        }
    }

    if (isset($existing['submission_id'])) {
        try {
            $conn->exec('UPDATE `submissions` SET `idd` = `submission_id` WHERE `idd` IS NULL');
        } catch (PDOException $e) {
            // ignore if idd column couldn't be updated
        }
    }
}
