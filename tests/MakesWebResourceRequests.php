<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 18.05.2018
 * Time: 11:03 PM
 */

namespace Tests;

trait MakesWebResourceRequests
{
    protected $postData = [ "url" => "http://path/to/file", "download_name" => "download.name" ];
    protected $_lastPostData;

    /**
     * @param bool $acceptJson
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function getIndexResponse($acceptJson = false)
    {
        $headers = [];
        if($acceptJson) $headers["Accept"] = "application/json";

        $route = route("index");
        return $this->get($route, $headers);
    }

    /**
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function postCreateResource($data = [], $headers = [])
    {
        $route = route("create");
        $postData = $this->getPostData($data);
        return $this->post($route, $postData, $headers);
    }

    /**
     * @param int $id WebResource id
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function getWebResourceById($id, $headers = [])
    {
        $route = route("get", $id);
        return $this->get($route, $headers);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getPostData($data = [])
    {
        $this->_lastPostData = array_merge(
            $this->postData,
            $data
        );
        return $this->_lastPostData;
    }

    /**
     * @param int $id
     * @param array $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function downloadWebResourceById($id, $headers = [])
    {
        $route = route("download", $id);
        return $this->get($route, $headers);
    }
}