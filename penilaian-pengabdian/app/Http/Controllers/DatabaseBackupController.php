<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Symfony\Component\Process\Process;

class DatabaseBackupController extends Controller
{
    public function index()
    {
        $backupPath = storage_path('app/backups');
        $files = [];

        if (File::isDirectory($backupPath)) {
            $allFiles = File::glob($backupPath . '/*.sql');
            foreach ($allFiles as $file) {
                $files[] = [
                    'name' => basename($file),
                    'size' => File::size($file),
                    'modified' => File::lastModified($file),
                ];
            }
            usort($files, fn($a, $b) => $b['modified'] <=> $a['modified']);
        }

        return view('admin.database.index', compact('files'));
    }

    public function backup(Request $request)
    {
        $backupPath = storage_path('app/backups');

        if (!File::isDirectory($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $filename = 'backup_penilaian_pengabdian_' . now()->format('Ymd_His') . '.sql';
        $filepath = $backupPath . DIRECTORY_SEPARATOR . $filename;

        try {
            $dbConfig = config('database.connections.mysql');
            $host = $dbConfig['host'];
            $port = $dbConfig['port'];
            $database = $dbConfig['database'];
            $username = $dbConfig['username'];
            $password = $dbConfig['password'];

            $success = false;

            // Method 1: Try mysqldump (most reliable)
            $mysqldumpPath = $this->findMysqldump();
            if ($mysqldumpPath) {
                $success = $this->backupWithMysqldump($mysqldumpPath, $host, $port, $username, $password, $database, $filepath);
            }

            // Method 2: PHP-based backup (fallback)
            if (!$success) {
                $success = $this->phpBackup($filepath, $database);
            }

            if ($success && File::exists($filepath) && File::size($filepath) > 100) {
                $sizeKb = round(File::size($filepath) / 1024, 1);
                return back()->with('success', "Backup berhasil! File: {$filename} ({$sizeKb} KB)");
            }

            return back()->with('error', 'Gagal membuat backup. Pastikan mysqldump tersedia atau gunakan metode manual.');
        } catch (\Throwable $e) {
            \Log::error('Backup error: ' . $e->getMessage());
            return back()->with('error', 'Gagal backup: ' . $e->getMessage());
        }
    }

    public function download(string $filename)
    {
        if (!$this->isValidBackupFile($filename)) {
            abort(403);
        }

        $filepath = storage_path('app/backups/' . $filename);

        if (!File::exists($filepath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        return Response::download($filepath, $filename, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function destroy(string $filename)
    {
        if (!$this->isValidBackupFile($filename)) {
            abort(403);
        }

        $filepath = storage_path('app/backups/' . $filename);

        if (!File::exists($filepath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        File::delete($filepath);

        return back()->with('success', 'File backup berhasil dihapus: ' . $filename);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt|max:102400',
        ]);

        try {
            $file = $request->file('backup_file');
            $content = file_get_contents($file->getRealPath());

            if (empty($content) || strlen($content) < 50) {
                return back()->with('error', 'File backup kosong atau tidak valid.');
            }

            // Drop all tables first
            $this->dropAllTables();

            // Execute the SQL
            $this->executeSqlFile($content);

            return back()->with('success', 'Restore database berhasil! Silakan refresh halaman.');
        } catch (\Throwable $e) {
            \Log::error('Restore error: ' . $e->getMessage());
            return back()->with('error', 'Gagal restore: ' . $e->getMessage());
        }
    }

    // ─── Private Methods ──────────────────────────────────────────

    private function isValidBackupFile(string $filename): bool
    {
        return str_ends_with($filename, '.sql')
            && !str_contains($filename, '..')
            && !str_contains($filename, '/')
            && !str_contains($filename, '\\');
    }

    private function backupWithMysqldump(string $mysqldumpPath, string $host, string $port, string $username, string $password, string $database, string $filepath): bool
    {
        try {
            // Write password to temporary file to avoid shell escaping issues
            $tempCnf = tempnam(sys_get_temp_dir(), 'mycnf');
            file_put_contents($tempCnf, "[client]\npassword=\"{$password}\"\n");

            $command = sprintf(
                '"%s" --defaults-file="%s" --host=%s --port=%s --user=%s --single-transaction --routines --triggers --result-file="%s" %s 2>&1',
                $mysqldumpPath,
                $tempCnf,
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                $filepath,
                escapeshellarg($database)
            );

            exec($command, $output, $returnCode);

            // Clean up temp file
            @unlink($tempCnf);

            return $returnCode === 0 && File::exists($filepath) && File::size($filepath) > 100;
        } catch (\Throwable $e) {
            \Log::warning('mysqldump failed: ' . $e->getMessage());
            return false;
        }
    }

    private function phpBackup(string $filepath, string $database): bool
    {
        try {
            $tables = DB::select("SHOW TABLES");
            $tableKey = "Tables_in_" . $database;

            $sql = "-- ============================================\n";
            $sql .= "-- Backup Penilaian Pengabdian\n";
            $sql .= "-- Generated: " . now()->format('Y-m-d H:i:s') . "\n";
            $sql .= "-- Database: {$database}\n";
            $sql .= "-- ============================================\n\n";
            $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
            $sql .= "SET AUTOCOMMIT = 0;\n";
            $sql .= "START TRANSACTION;\n";
            $sql .= "SET time_zone = \"+00:00\";\n";
            $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;

                // Get CREATE TABLE statement
                $createTable = DB::select("SHOW CREATE TABLE `" . $tableName . "`");
                if (!empty($createTable)) {
                    $sql .= "-- --------------------------------------------------------\n";
                    $sql .= "-- Table: `{$tableName}`\n";
                    $sql .= "-- --------------------------------------------------------\n";
                    $sql .= "DROP TABLE IF EXISTS `" . $tableName . "`;\n";
                    $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
                }

                // Get all data
                $rows = DB::table($tableName)->get();
                if ($rows->isEmpty()) {
                    $sql .= "-- (no data)\n\n";
                    continue;
                }

                $sql .= "-- Data for table `{$tableName}` (" . $rows->count() . " rows)\n";

                // Build batch INSERT statements (500 rows per statement for efficiency)
                $batchSize = 500;
                $chunks = $rows->chunk($batchSize);

                foreach ($chunks as $chunk) {
                    $sql .= "INSERT INTO `" . $tableName . "` VALUES\n";
                    $rowStatements = [];

                    foreach ($chunk as $row) {
                        $values = array_map(function ($value) {
                            if ($value === null) return 'NULL';
                            if (is_int($value)) return (string) $value;
                            if (is_float($value)) return rtrim(rtrim(number_format($value, 10, '.', ''), '0'), '.');
                            if (is_bool($value)) return $value ? '1' : '0';
                            // Escape special characters for MySQL
                            $value = (string) $value;
                            $value = str_replace(
                                ["\\", "\0", "\n", "\r", "\t", "'", "\x1a"],
                                ["\\\\", "\\0", "\\n", "\\r", "\\t", "\\'", "\\Z"],
                                $value
                            );
                            return "'" . $value . "'";
                        }, (array) $row);

                        $rowStatements[] = "(" . implode(', ', $values) . ")";
                    }

                    $sql .= implode(",\n", $rowStatements) . ";\n\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
            $sql .= "COMMIT;\n";

            File::put($filepath, $sql);

            return File::exists($filepath) && File::size($filepath) > 100;
        } catch (\Throwable $e) {
            \Log::error('PHP backup error: ' . $e->getMessage());
            return false;
        }
    }

    private function dropAllTables(): void
    {
        $database = config('database.connections.mysql.database');
        $tables = DB::select("SHOW TABLES");
        $tableKey = "Tables_in_" . $database;

        DB::unprepared('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            DB::unprepared("DROP TABLE IF EXISTS `" . $tableName . "`");
        }

        DB::unprepared('SET FOREIGN_KEY_CHECKS=1');
    }

    private function executeSqlFile(string $content): void
    {
        // Remove comments and clean up
        $lines = explode("\n", $content);
        $cleanLines = [];
        foreach ($lines as $line) {
            $trimmed = ltrim($line);
            // Skip pure comment lines (but keep inline comments)
            if (str_starts_with($trimmed, '--') || str_starts_with($trimmed, '#')) {
                continue;
            }
            $cleanLines[] = $line;
        }
        $content = implode("\n", $cleanLines);

        // Split by semicolons that are NOT inside quotes
        $statements = $this->splitSqlStatements($content);

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (empty($statement)) continue;

            // Skip SET commands that are handled separately
            if (preg_match('/^\s*SET\s+(FOREIGN_KEY_CHECKS|AUTOCOMMIT|time_zone|SQL_MODE)/i', $statement)) {
                try {
                    DB::unprepared($statement);
                } catch (\Throwable $e) {
                    // Ignore SET errors
                }
                continue;
            }

            try {
                DB::unprepared($statement);
            } catch (\Throwable $e) {
                \Log::warning('SQL statement error: ' . $e->getMessage() . "\nStatement: " . substr($statement, 0, 200));
            }
        }
    }

    private function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';
        $len = strlen($sql);

        for ($i = 0; $i < $len; $i++) {
            $char = $sql[$i];
            $next = ($i + 1 < $len) ? $sql[$i + 1] : '';

            if ($inString) {
                $current .= $char;
                if ($char === '\\' && $next !== '') {
                    // Escaped character - skip next
                    $current .= $next;
                    $i++;
                } elseif ($char === $stringChar) {
                    $inString = false;
                }
            } else {
                if ($char === "'" || $char === '"') {
                    $inString = true;
                    $stringChar = $char;
                    $current .= $char;
                } elseif ($char === ';' ) {
                    $trimmed = trim($current);
                    if (!empty($trimmed)) {
                        $statements[] = $trimmed;
                    }
                    $current = '';
                } else {
                    $current .= $char;
                }
            }
        }

        // Last statement without trailing semicolon
        $trimmed = trim($current);
        if (!empty($trimmed)) {
            $statements[] = $trimmed;
        }

        return $statements;
    }

    private function findMysqldump(): ?string
    {
        $paths = [
            'mysqldump',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\wamp64\\bin\\mysql\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.4\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
            'C:\\ProgramData\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
        ];

        foreach ($paths as $path) {
            try {
                $process = new Process([$path, '--version']);
                $process->setTimeout(5);
                $process->run();
                if ($process->isSuccessful()) {
                    return $path;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return null;
    }
}
