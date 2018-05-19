<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebResourceRequest;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

    public function createResource(WebResourceRequest $request)
    {
        $resource = WebResource::create($request->all());
        Log::info("Pending resource added {$request->url}");

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
