<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; LrfRequestMakeCommand
 * Date: May 06, 2020
 * Time: 01:49:26 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class LrfRequestMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lrf:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new api request class based on Laravel Rest Framework';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lrf:request {name} {--model=}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/request.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Requests';
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = str_replace('/', '\\', $name);

        $defaultNameSpaceToRoot = $this->getDefaultNamespace(trim($rootNamespace, '\\'));
        return $this->qualifyClass(
            $defaultNameSpaceToRoot . '\\' . $this->option('model') . '\\' . $name
        );
    }

        /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model.'],
        ];
    }
}
