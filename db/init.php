<?php

/**
 * db/init.php — Auto-bootstrap the database schema.
 *
 * This file is included once per request via WebAPI::__construct().
 * It reads db/schema.sql and executes each statement against the
 * active MySQL connection.  All CREATE TABLE statements use
 * IF NOT EXISTS, so this is completely safe to run every request
 * (the overhead is negligible after the first boot).
 */

function photogram_init_schema(): void
{
    static $initialized = false;
    if ($initialized) return;
    $initialized = true;

    try {
        $conn = Database::getConnection();
        $schema = file_get_contents(__DIR__ . '/schema.sql');

        if ($schema === false) {
            error_log('photogram_init_schema: could not read schema.sql');
            return;
        }

        // Strip comments and split on semicolons
        $statements = array_filter(
            array_map('trim', explode(';', $schema)),
            fn($s) => strlen($s) > 5 && !str_starts_with(ltrim($s), '--')
        );

        foreach ($statements as $sql) {
            if (!$conn->query($sql)) {
                error_log('photogram_init_schema error: ' . $conn->error . ' | SQL: ' . substr($sql, 0, 100));
            }
        }

        // Ensure every auth row has a matching users profile row
        $conn->query(
            "INSERT IGNORE INTO `users` (`id`) SELECT `id` FROM `auth`"
        );

    } catch (Exception $e) {
        error_log('photogram_init_schema exception: ' . $e->getMessage());
    }
}

photogram_init_schema();
