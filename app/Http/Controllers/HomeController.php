<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebResourceRequest;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Log;

use App\Models\WebResource;
use App\Events\EnqueueDownloading;


class HomeController extends Controller
{
    public function index(Request $request) {
        $resources = WebResource::all();

        if($request->wantsJson()) {
            return response()->json($resources);
        }

        return view("home")
                ->with("resources", $resources)
                ->with("message", session()->pull("message", ""));
    }

    public function enqueueResource(WebResourceRequest $request)
    {
        $url = $request->get("url");
        Log::info("Pending resource added {$url}");

        $resource = WebResource::create($request->all());

        if($request->wantsJson()) {
            return response()->json($resource, 201, [
                "Location" => url("/resource/{$resource->id}"),
            ]);
        }

        return response()->redirectTo("/");
    }

    public function getResource(Request $request, $id)
    {
        $resource = WebResource::findOrFail($id);

        return response()->json($resource);
    }

    public function downloadResource(Request $request, $id)
    {
        $resource = WebResource::findOrFail($id);
        $file = Storage::disk("downloads")->path($resource->file_name);

        return response()->download($file, $resource->download_name);
    }

    public function retryDownload(Request $request, $id)
    {
        $resource = WebResource::findOrFail($id);
        $resource->update([
            "status" => "pending"
        ]);

        event(new EnqueueDownloading($resource));

        return response("", 200);
    }

    public function deleteResource(Request $request, $id)
    {
        $resource = WebResource::findOrFail($id);
        $resource->delete();

        session()->flash("message", "File {$resource->download_name} removed");

        return response()->redirectTo("/");
    }
}
