<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 18.05.2018
 * Time: 11:08 PM
 */

namespace Tests;

use App\Models\WebResource;
use \Illuminate\Foundation\Testing\TestResponse;

trait AssertsWebResources
{
    /**
     * @param int $expected
     */
    protected function assertWebResourcesCount($expected)
    {
        $actual = WebResource::all()->count();

        $this->assertTrue($expected == $actual, "Expected $expected items. $actual found instead");
    }

    /**
     * @param \Illuminate\Foundation\Testing\TestResponse $response
     * @param array $keys
     */
    protected  function assertResponseHasKeys(TestResponse $response, $keys)
    {
        $content = $response->getContent();
        $jsonResp = $response->json();
        $respKeys = array_keys($jsonResp);

        $this->assertArrayContains($keys, $respKeys, "\nServer response $content");
    }

    protected function assertArrayContains($expected, $actual, $extraMessage = "")
    {
        $actual_str = implode(", ", $actual);
        foreach($expected as $item)
        {
            $this->assertTrue(in_array($item, $actual), "Expected to find $item in [$actual_str]. $extraMessage");
        }
    }

    /**
     * @param \Illuminate\Foundation\Testing\TestResponse $response
     * @param int $total_count
     */
    protected function assertResponseContainsWebResourceCount(TestResponse $response, $total_count)
    {
        $data = $response->json();
        $webResourceKeys = ["id", "status", "url", "file_name", "download_name", "started_at"];

        $this->assertTrue(is_array($data), "Expected response to be array");

        $dataCount = count($data);
        $this->assertTrue(count($data) == $total_count, "Expected response to contain $total_count items; $dataCount found instead");

        for($i = 0; $i < $total_count; $i++)
        {
            $this->assertArrayContains($webResourceKeys, array_keys($data[$i]));
        }
    }
}