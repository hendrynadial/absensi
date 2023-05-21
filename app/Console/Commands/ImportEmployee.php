<?php

namespace App\Console\Commands;
use App\Imports\EmployeeImport;
use Illuminate\Console\Command;

class ImportEmployee extends Command
{
    protected $signature = 'import:excel';
    protected $description = 'Laravel Excel importer';

    public function handle()
    {
        $this->output->title('Starting import');
        (new EmployeeImport)->withOutput($this->output)->import('Employee.xlsx','local', \Maatwebsite\Excel\Excel::XLSX);
        $this->output->success('Import successful');
    }
}
