<?php

namespace Fitztrev\QueryTracer\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

class QueryTracer implements ScopeInterface
{

    private function isEnabled()
    {
        return Config::get('app.debug');
    }

    public function apply(Builder $builder)
    {
        if (! $this->isEnabled()) {
            return;
        }

        $traces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        foreach ($traces as $trace) {
            // Find the first non-vendor-dir file in the backtrace
            if (isset($trace['file']) && ! str_contains($trace['file'], DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR)) {
                $file = '"query.file" <> "' . $trace['file'] . '"';
                $line = '"query.line" <> "' . $trace['line'] . '"';

                return $builder->whereRaw($file)->whereRaw($line);
            }
        }
    }

    public function remove(Builder $builder) {

    }

}
