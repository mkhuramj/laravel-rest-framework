<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; LrfControllerMakeCommand
 * Date: May 06, 2020
 * Time: 02:01:49 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class LrfControllerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lrf:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class based on Laravel Rest Framework';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $stub = $this->files->get($this->getStub());
        $classLower = Str::lower(class_basename($this->argument('name')));
        $stub = $this->replaceNamespace($stub, $name)
                     ->replaceModelClass($stub, $classLower)
                     ->replaceSnakeModelClass($stub, $classLower)
                     ->replaceClass($stub, $name);
        return $stub;
    }

    /**
     * Replace the class name lower for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceSnakeModelClass(&$stub, $name)
    {
        $classSnake = Str::snake(class_basename($this->option('model')));
        $stub = str_replace('DummySnakeModelClass', $classSnake, $stub);

        return $this;
    }

    /**
     * Replace the class name lower for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceModelClass(&$stub, $name)
    {
        $model = str_replace($this->getNamespace($name) . '\\', '', $this->option('model'));
        $stub = str_replace('DummyModelClass', $model, $stub);

        return $this;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given model.'],

            ['resource', 'r', InputOption::VALUE_NONE, 'Generate a resource controller class.'],

            ['parent', 'p', InputOption::VALUE_OPTIONAL, 'Generate a nested resource controller class.'],
        ];
    }
}
