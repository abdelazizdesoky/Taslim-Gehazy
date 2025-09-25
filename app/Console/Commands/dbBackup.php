<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class dbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--type=1}'; // 1-manual, 2-auto

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        

        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $port = env('DB_PORT');

        $this->info("Database: $database, Username: $username, Host: $host, Password: $password");


  
        
        $backupDir = storage_path('app/backups');
        $backupFile = $database . '_' . date('Y-m-d_H-i-s') . '.sql';
        $backupPath = $backupDir . '/' . $backupFile;

        // Ensure the backup directory exists
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        // Build the mysqldump command
       // mysqldump --column-statistics=0 -h 127.0.0.1 -P 3306 -u root -ptaslimpass taslim_app > /mnt/srv/taslim/storage/app/backups/taslim_app_2025-03-27_08-02-40.sql

        $command = "mysqldump --column-statistics=0 -h $host -P  $port -u $username -p$password --ignore-table=taslim_app.activity_log  $database  > {$backupPath}";
        
    
        // Execute the command
        exec($command . ' 2>&1', $output, $result);

        if ($result === 0) {
            // Insert backup info into the database
            DB::table('backups')->insert([
                'path' => $backupPath,
                'size' => File::size($backupPath),
                'type' => $this->option('type'),
                'created_at' => now(),
            ]);

            $this->info("Database backup was successful. File saved at: {$backupPath}");
            return Command::SUCCESS;
        } else {
            // Log and display error details
            $this->error('Database backup failed.');
            $this->error('Error Details: ' . implode("\n", $output));
            $this->error('Command Executed: ' . $command);
            
        }
    }
}
