<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; LrfPermissionMakeCommand
 * Date: May 06, 2020
 * Time: 02:35:23 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class LrfPermissionMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lrf:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new permission based on Laravel Rest Framework';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Permission';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        $classSnake = Str::snake(class_basename($this->argument('name')));
        $stub = $this->replaceNamespace($stub, $name)
                     ->replaceClassSnake($stub, $classSnake)
                     ->replaceClass($stub, $name);
        return $stub;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $name .= 'Permission';
        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Replace the class name (snake case) for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClassSnake(&$stub, $name)
    {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        $stub = str_replace('DummyClassSnake', $class, $stub);

        return $this;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/permission.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Permissions';
    }
}
