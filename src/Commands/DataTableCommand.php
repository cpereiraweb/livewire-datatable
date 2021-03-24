<?php

namespace LuanFreitasDev\LivewireDataTables\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class DataTableCommand extends Command
{
    protected $signature = 'make:table {--name= : name of class component}
    {--model= : model Class}
    {--publish : publish stubs file}
    {--template= : name of the file that will be used as a template}
    {--force : oOverwrite any existing files}';

    protected $description = 'Make a new Laravel Livewire table component.';

    public function handle()
    {
        if ($this->option('publish')) {
            if (! is_dir($stubsPath = $this->laravel->basePath('stubs'))) {
                (new Filesystem)->makeDirectory($stubsPath);
            }

            $files = [
                __DIR__.'/../../resources/stubs/table.stub' => $stubsPath.'/table.stub',
                __DIR__.'/../../resources/stubs/table.collection.stub' => $stubsPath.'/table.collection.stub',
            ];

            foreach ($files as $from => $to) {
                if (! file_exists($to) || $this->option('force')) {
                    file_put_contents($to, file_get_contents($from));
                }
            }

            $this->info('Stubs published successfully.');

        } else {

            $modelName = $this->option('model');
            $modelNameArr = explode('\\', $modelName);

            if (!empty($this->option('model'))) {

                if (!empty($this->option('template'))) {
                    $stub = File::get(base_path($this->option('template')));
                } else {
                    $stub = File::get(__DIR__ . '/../../resources/stubs/table.stub');
                }

                $stub = str_replace('{{ componentName }}', $this->option('name'), $stub);
                $stub = str_replace('{{ modelName }}', $modelName, $stub);
                $stub = str_replace('{{ modelLastName }}', Arr::last($modelNameArr), $stub);
            } else {

                if (!empty($this->option('template'))) {
                    $stub = File::get(base_path($this->option('template')));
                } else {
                    $stub = File::get(__DIR__ . '/../../resources/stubs/table.collection.stub');
                }

                $stub = str_replace('{{ componentName }}', $this->option('name'), $stub);
            }

            $path = app_path('Http/Livewire/' . $this->option('name') . '.php');

            File::ensureDirectoryExists(app_path('Http/Livewire'));

            if (!File::exists($path) || $this->confirm($this->option('name') . ' already exists. Overwrite it?')) {
                File::put($path, $stub);
                $this->info($this->option('name') . ' was made!');
            }
        }

    }
}
