<?php

use Fitztrev\QueryTracer\Scopes\QueryTracer;

class QueryTracerTest extends Orchestra\Testbench\TestCase
{

    public function testQueryTracerIsEnabledWhenDebugIsOn()
    {
        config(['app.debug' => true]);
        $model = new QueryTracerDefaultTestModel();
        $query = $model->newQuery();
        $this->assertEquals('select * from "table" where "query.file" <> "'.__FILE__.'" and "query.line" <> "'.__LINE__.'"', $query->toSql());
    }

    public function testQueryTracerIsDisabledWhenDebugIsOff()
    {
        config(['app.debug' => false]);
        $model = new QueryTracerDefaultTestModel();
        $query = $model->newQuery();
        $this->assertEquals('select * from "table"', $query->toSql());
    }

    public function testQueryTracerIsExplicitlyEnabled()
    {
        $model = new QueryTracerEnabledTestModel();
        $query = $model->newQuery();
        $this->assertEquals('select * from "table" where "query.file" <> "'.__FILE__.'" and "query.line" <> "'.__LINE__.'"', $query->toSql());
    }

    public function testQueryTracerIsExplicitlyDisabled()
    {
        $model = new QueryTracerDisabledTestModel();
        $query = $model->newQuery();
        $this->assertEquals('select * from "table"', $query->toSql());
    }

    public function testQueryTracerCanBeRemoved()
    {
        $model = new QueryTracerEnabledTestModel();
        $query = $model->newQuery()->withoutGlobalScope(QueryTracer::class);
        $this->assertEquals('select * from "table"', $query->toSql());
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup testing database
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [Fitztrev\QueryTracer\Providers\QueryTracerServiceProvider::class];
    }
}

class QueryTracerDefaultTestModel extends Illuminate\Database\Eloquent\Model
{
    protected $table = 'table';
}

class QueryTracerEnabledTestModel extends Illuminate\Database\Eloquent\Model
{
    protected $table = 'table';

    public function enableQueryTracer()
    {
        return true;
    }
}

class QueryTracerDisabledTestModel extends Illuminate\Database\Eloquent\Model
{
    protected $table = 'table';

    public function enableQueryTracer()
    {
        return false;
    }
}
