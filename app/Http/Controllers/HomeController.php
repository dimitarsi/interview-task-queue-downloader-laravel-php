<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebResourceRequest;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Log;

use App\Models\WebResource;
use App\Events\EnqueueDownloading;


class HomeController extends Controller
{
    public function Index(Request $request) {
        $resources = WebResource::all();

        return view("home")
                ->with("resources", $resources)
                ->with("message", session()->pull("message", ""));
    }

    public function EnqueueResource(WebResourceRequest $request)
    {
        $url = $request->get("url");
        Log::info("Pending resource added {$url}");

        $request->merge([
            "file_name" => Uuid::uuid4()->toString()
        ]);

        $resource = WebResource::create($request->all());

        if($request->wantsJson()) {
            // Return Created at Route
            return response(json_encode($resource), 201, [
                "Location" => url("/resource/{$resource->id}"),
                "Content-Type" => "application/json"
            ]);
        }

        return response()->redirectTo("/");
    }

    public function GetResource(Request $request, $id)
    {
        $resource = WebResource::findOrFail($id);
        return response()->json($resource);
    }

    public function DownloadResource(Request $request, $id)
    {
        $resource = WebResource::findOrFail($id);
        $file = Storage::disk("downloads")->path($resource->file_name);
        return response()->download($file, $resource->download_name);
    }

    public function RetryDownload(Request $request, $id)
    {
        $resource = WebResource::findOrFail($id);
        $resource->update([
            "status" => "pending"
        ]);

        event(new EnqueueDownloading($resource));

        return response("", 200);
    }

    public function DeleteResource(Request $request, $id)
    {
        $resource = WebResource::findOrFail($id);
        $resource->delete();

        session()->flash("message", "File {$resource->download_name} removed");

        return response()->redirectTo("/");
    }
}
