<?php

namespace Tests\Unit\app\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Queue;

class WebResourceUnitTest extends TestCase
{
    use DatabaseMigrations;

    public function test_isComplete_returns_true()
    {
        Queue::fake();
        $model = factory(\App\Models\WebResource::class)->create(["status" => "complete"]);

        $this->assertTrue($model->isComplete(), "Expect model to be completed");
    }

    public function test_isComplete_returns_false()
    {
        Queue::fake();
        $model1 = factory(\App\Models\WebResource::class)->create(["status" => "error"]);
        $model2 = factory(\App\Models\WebResource::class)->create(["status" => "downloading"]);
        $model3 = factory(\App\Models\WebResource::class)->create(["status" => "pending"]);

        $this->assertFalse($model1->isComplete(), "Expect model not to be completed");
        $this->assertFalse($model2->isComplete(), "Expect model not to be completed");
        $this->assertFalse($model3->isComplete(), "Expect model not to be completed");
    }
}
