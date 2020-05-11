<?php

namespace LaravelRestFramework\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class LrfModelMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lrf:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class based on Laravel Rest Framework';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return;
        }


        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('resource', true);
            // $this->input->setOption('resource-collection', true);
            // $this->input->setOption('request', true);
            // $this->input->setOption('permission', true);
            // $this->input->setOption('filter', true);
            // $this->input->setOption('controller', true);
        }

        if ($this->option('factory'))
            $this->createFactory();

        if ($this->option('migration'))
            $this->createMigration();

        // collect all the following if 'r' option is givien
        //  request
        //  permission
        //  Filter
        //  resource, collection
        //  controller
        if ($this->option('resource')) {
            $this->createResource();
            $this->createResourceCollection();
            $this->createCreateApiRequest();
            $this->createUpdateApiRequest();
            $this->createPermission();
            $this->createFilter();
            $this->createController();
        }
            
        // if ($this->option('resource-collection'))
        //     $this->createResourceCollection();
    
        // if ($this->option('request')) {
        //     $this->createCreateApiRequest();
        //     $this->createUpdateApiRequest();
        // }

        // if ($this->option('permission'))
        //     $this->createPermission();

        // if ($this->option('filter'))
        //     $this->createFilter();

        // if ($this->option('controller'))
        //     $this->createController();
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory()
    {
        $this->call('make:factory', [
            'name' => $this->argument('name').'Factory',
            '--model' => $this->argument('name'),
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::plural(Str::snake(class_basename($this->argument('name'))));

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createResource()
    {
        // $resource = Str::studly(class_basename($this->argument('name')));
        $modelName = $this->getNameInput();
        $this->call('lrf:resource', [
            'name' => "{$modelName}Resource"
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createResourceCollection()
    {
        // $resourceCollection = Str::studly(class_basename($this->argument('name')));
        $modelName = $this->getNameInput();

        $this->call('lrf:resource', [
            'name' => "{$modelName}Collection"
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createCreateApiRequest()
    {
        // $resource = Str::studly(class_basename($this->argument('name')));
        $modelName = $this->getNameInput();
        // Need to specify path to create it in a separate folder
        $this->call('lrf:request', [
            'name' => "Create{$modelName}ApiRequest",
            '--model' => $modelName
        ]);
    }
    
    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createUpdateApiRequest()
    {
        // $resource = Str::studly(class_basename($this->argument('name')));
        $modelName = $this->getNameInput();
        // Need to specify path to create it in a separate folder
        $this->call('lrf:request', [
            'name' => "Update{$modelName}ApiRequest",
            '--model' => $modelName
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createPermission()
    {
        // $resource = Str::studly(class_basename($this->argument('name')));
        $modelName = $this->getNameInput();
        // Need to specify path to create it in a separate folder
        $this->call('lrf:permission', [
            'name' => $modelName
        ]);
    }
    
    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createFilter()
    {
        // $resource = Str::studly(class_basename($this->argument('name')));
        $modelName = $this->getNameInput();
        // Need to specify path to create it in a separate folder
        $this->call('lrf:filter', [
            'name' => $modelName
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->getNameInput();

        $this->call('lrf:controller', [
            'name' => "{$controller}Controller",
            '--model' => $modelName
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('pivot')) {
            return __DIR__.'/stubs/pivot.model.stub';
        }

        return __DIR__.'/stubs/model.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, factory, and resource controller for the model'],

            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],

            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],

            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists.'],

            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model.'],

            ['pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model.'],

            ['resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller.'],
        ];
    }
}
