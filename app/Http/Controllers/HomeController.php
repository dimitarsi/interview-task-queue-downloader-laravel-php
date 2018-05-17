<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebResourceRequest;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\WebResource;
use Ramsey\Uuid\Uuid;

class HomeController extends Controller
{
    public function Index(Request $request) {
        return view("home");
    }

    public function EnqueueResource(Request $request)
    {
        $resource = new WebResource;
        $resource->url = $request->get("url");
        $resource->file_name = Uuid::uuid4()->toString();

        $resource->save();

        // Return Created at Route
        return response(json_encode($resource), 201, [
            "Location" => url("/resource/{$resource->id}"),
            "Content-Type" => "application/json"
        ]);
    }

    public function GetResource(Request $request)
    {
        $resource = WebResource::findOrFail($request->get("id"));
        return response()->json($resource);
    }

    public function DownloadResource(Request $request)
    {
        $resource = WebResource::findOrFail($request->get("id"));
        return response()->download($resource->file_name);
    }
}
