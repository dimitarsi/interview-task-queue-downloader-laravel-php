<?php

namespace Tests\Unit\app\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\AssertsWebResources;
use Tests\MakesWebResourceRequests;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class HomeControllerUnitTest extends TestCase
{
    use DatabaseMigrations;
    use MakesWebResourceRequests;
    use AssertsWebResources;

    protected $acceptJsonHeaders = ["Accept" => "application/json"];
    /**
     * Test home page returns correct view and status code
     *
     * @return void
     */
    public function test_index_returns_correct_view()
    {
        $resp = $this->getIndexResponse();

        $resp->assertStatus(200);
        $resp->assertViewIs("home");
    }

    public function test_index_returns_all_webresources_when_wants_json()
    {
        Queue::fake();

        $create_count = 10;
        factory(\App\Models\WebResource::class, $create_count)->create();

        $resp = $this->getIndexResponse(true);
        $resp->assertStatus(200);

        $this->assertResponseContainsWebResourceCount($resp, 10);
    }

    public function test_create_route_add_new_webresource_and_redirects_to_index()
    {
        Queue::fake();

        $resp = $this->postCreateResource();

        $resp->assertRedirect(route("index"));
        $this->assertWebResourcesCount(1);
    }

    public function test_create_route_returns_created_response_when_wants_json()
    {
        Queue::fake();
        $resp = $this->postCreateResource([], ["Accept" => "application/json"]);
        $expected = array_merge(
            $this->_lastPostData,
            [
                "status" => "pending"
            ]
        );

        $resp->assertStatus(201);
        $resp->assertJsonFragment($expected);
    }

    public function test_create_route_redirects_to_index_on_invalid_input()
    {
        Queue::fake();
        $resp = $this->postCreateResource(["data" => "invalid_url"]);

        $resp->assertRedirect(route("index"));
    }

    public function test_create_route_returns_error_on_invalid_input()
    {
        Queue::fake();
        // Test valid download_name, invalid url
        $resp = $this->postCreateResource(["url" => "invalid_url"], ["Accept" => "application/json"]);
        $this->assertTrue($resp->isClientError(), "Expected to fail with status 400 <= [Status Code] < 500. Status code {$resp->status()} returned instead.");

        // Test valid url, invalid download_name
        $resp = $this->postCreateResource(["download_name" => ""], ["Accept" => "application/json"]);
        $this->assertTrue($resp->isClientError(), "Expected to fail with status 400 <= [Status Code] < 500. Status code {$resp->status()} returned instead.");
    }

    public function test_get_route_returns_webresource_by_id()
    {
        Queue::fake();
        factory(\App\Models\WebResource::class)->create();

        $resp = $this->getWebResourceById(1);

        $resp->assertStatus(200);
        $this->assertResponseHasKeys($resp, ["id", "status", "url", "file_name", "download_name", "started_at"]);
    }

    public function test_get_route_returns_not_found()
    {
        $resp = $this->getWebResourceById(9999);
        $resp->assertStatus(404);
    }

    public function test_download_route_returns_file()
    {
        Storage::fake("downloads");
        Queue::fake();

        $resource = factory(\App\Models\WebResource::class)->create();
        $fileContents = "Fake file contents";
        Storage::disk("downloads")->put($resource->file_name, $fileContents);

        $resp = $this->downloadWebResourceById(1);
        $resp->assertStatus(200);
        $resp->assertHeader("Content-Disposition", "attachment; filename=\"{$resource->download_name}\"");
        $resp->assertHeader("Content-Type", "text/plain");
        $resp->assertHeader("Content-Length", strlen($fileContents));
    }

    public function test_download_route_returns_not_found()
    {
        Storage::fake("downloads");
        $resp = $this->downloadWebResourceById(1);

        $resp->assertStatus(404);
    }

    public function test_retry_route_to_be_ok()
    {
        Queue::fake();
        factory(\App\Models\WebResource::class)->create();

        $resp = $this->get(route("retry", 1));
        $resp->assertSuccessful();
    }

    public function test_retry_to_returns_not_found()
    {
        Queue::fake();
        $resp = $this->get(route("retry", 1));

        $this->assertTrue($resp->isNotFound(), "Expect retry route to return 404 Not Found");
    }
}
