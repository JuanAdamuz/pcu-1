<li class="collection-item @if(isset($notification->data['official']) && $notification->data['official']) yellow lighten-4 @endif">
    <div class="row">
        <div class="col s12 hide-on-med-and-up">
            <i style="padding-top: 8px" class="material-icons small">{{ $notification->data['icon'] }}</i><br>
            <small class="tooltipped" data-tooltip="{{ $notification->created_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}">{{ $notification->created_at->setTimezone(Auth::user()->timezone)->diffForHumans() }}</small>
            @if(isset($notification->data['official']) && $notification->data['official'])
                <br><small class="red-text"><b>Advertencia</b></small>
            @endif
        </div>
        <div class="col m2 hide-on-small-only">
            <center>
                <i style="padding-top: 8px" class="material-icons small">{{ $notification->data['icon'] }}</i><br>
                <small class="tooltipped" data-tooltip="{{ $notification->created_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}">{{ $notification->created_at->diffForHumans() }}</small>
                @if(isset($notification->data['official']) && $notification->data['official'])
                    <br><small class="red-text"><b>Advertencia</b></small>
                @endif
            </center>
        </div>
        <div class="col s12 m10">
            <b>{{ $notification->data['title']  }}</b><br>
            {{ $notification->data['message'] }}
            @if(isset($notification->data['url']) && isset($notification->data['button_text']))
                <br><a href="{{ $notification->data['url'] }}">{{ $notification->data['button_text'] }}</a>
            @endif
        </div>
    </div>
</li>