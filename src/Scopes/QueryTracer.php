<?php

namespace Fitztrev\QueryTracer\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class QueryTracer implements Scope
{

    private function isEnabled(Model $model)
    {
        if (method_exists($model, 'enableQueryTracer')) {
            return $model->enableQueryTracer();
        }

        return config('app.debug');
    }

    public function apply(Builder $builder, Model $model)
    {
        if (! $this->isEnabled($model)) {
            return;
        }

        $traces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        foreach ($traces as $trace) {
            // Find the first non-vendor-dir file in the backtrace
            if (isset($trace['file']) && ! str_contains($trace['file'], '/vendor/')) {
                $file = '"query.file" <> "' . $trace['file'] . '"';
                $line = '"query.line" <> "' . $trace['line'] . '"';

                return $builder->whereRaw($file)->whereRaw($line);
            }
        }
    }
}
