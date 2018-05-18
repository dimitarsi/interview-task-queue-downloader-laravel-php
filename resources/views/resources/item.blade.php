<div class="flex-c row">
    <span><img src="{{asset("imgs/{$item->status}.png")}}" alt="{{$item->status}}" title="{{$item->status}}"/></span>
    <span>{{$item->url}}</span>
    <span>
    @if($item->status == "complete")
        <a href="{{route("download", $item->id)}}">Download</a>
    @elseif($item->status == "error")
        <a href="{{route("retry", $item->id)}}">Retry</a>
    @else
        <a href="#" class="disabled">Download</a>
    @endif
    </span>
    <span>
        @if($item->status == "error" || $item->status == "complete")
            <a href="{{route("delete", $item->id)}}">
                <img src="{{asset("imgs/trash.png")}}" alt="remove" />
            </a>
        @else
            <a href="#" class="disabled">
                <img src="{{asset("imgs/trash.png")}}" alt="remove" />
            </a>
        @endif
    </span>
</div>