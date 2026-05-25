<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SystemToolsController extends Controller
{
    public function index()
    {
        return view('admin.pages.system-tools.index');
    }

    public function clearCache(Request $request)
    {
        $type = $request->type ?? 'all';
        $messages = [];

        try {
            if ($type === 'all' || $type === 'view') {
                Artisan::call('view:clear');
                $messages[] = 'Views cache cleared';
            }
            if ($type === 'all' || $type === 'config') {
                Artisan::call('config:clear');
                $messages[] = 'Config cache cleared';
            }
            if ($type === 'all' || $type === 'route') {
                Artisan::call('route:clear');
                $messages[] = 'Route cache cleared';
            }
            if ($type === 'all' || $type === 'app') {
                Artisan::call('cache:clear');
                $messages[] = 'Application cache cleared';
            }

            return response()->json([
                'success' => true,
                'message' => implode(', ', $messages),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function systemInfo()
    {
        try {
            $dbVersion = DB::select('SELECT VERSION() as version')[0]->version ?? 'Unknown';

            // Table sizes
            $tables = DB::select("
                SELECT table_name, table_rows, 
                       ROUND((data_length + index_length) / 1024, 2) AS size_kb
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
                ORDER BY (data_length + index_length) DESC
                LIMIT 20
            ");

            // Total DB size
            $dbSize = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = DATABASE()
            ")[0]->size_mb ?? 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'php_version'    => PHP_VERSION,
                    'laravel_version'=> app()->version(),
                    'db_version'     => $dbVersion,
                    'db_size_mb'     => $dbSize,
                    'tables'         => $tables,
                    'env'            => app()->environment(),
                    'debug_mode'     => config('app.debug') ? 'On' : 'Off',
                    'timezone'       => config('app.timezone'),
                    'memory_limit'   => ini_get('memory_limit'),
                    'max_upload'     => ini_get('upload_max_filesize'),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getLogs(Request $request)
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (!File::exists($logFile)) {
                return response()->json(['success' => true, 'data' => ['lines' => [], 'size' => 0]]);
            }

            $lines = 100;
            $content = File::get($logFile);
            $allLines = array_filter(explode("\n", $content));
            $recent = array_slice(array_values($allLines), -$lines);

            $parsed = [];
            foreach ($recent as $line) {
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+)$/', $line, $m)) {
                    $parsed[] = [
                        'time'    => $m[1],
                        'env'     => $m[2],
                        'level'   => strtolower($m[3]),
                        'message' => substr($m[4], 0, 200),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'lines' => array_reverse($parsed),
                    'size'  => round(File::size($logFile) / 1024, 1),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function clearLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (File::exists($logFile)) {
                File::put($logFile, '');
            }
            return response()->json(['success' => true, 'message' => 'Log file cleared']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
