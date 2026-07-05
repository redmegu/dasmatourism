<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

class DatabaseBackupController extends Controller
{
    public function download()
    {
        $database = env('DB_DATABASE');

        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

        $sqlDump = "";
        foreach ($tables as $table) {
            // Drop + Create
            $createTable = DB::select("SHOW CREATE TABLE `$table`")[0]->{'Create Table'};
            $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n$createTable;\n\n";

            // Insert rows
            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                $vals = array_map(function ($value) {
                    return $value === null ? 'NULL' : "'" . addslashes($value) . "'";
                }, (array)$row);

                $sqlDump .= "INSERT INTO `$table` VALUES (" . implode(',', $vals) . ");\n";
            }
            $sqlDump .= "\n\n";
        }

        $filename = "backup_" . $database . "_" . date('Y-m-d_H-i-s') . ".sql";

        return Response::make($sqlDump, 200, [
            "Content-Type" => "application/sql",
            "Content-Disposition" => "attachment; filename=$filename",
        ]);
    }
}
