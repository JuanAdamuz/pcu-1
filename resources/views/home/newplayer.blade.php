<div class="col s12">
    {{-- Teaser --}}
    <div class="card-panel teaser">
        <div class="container ">
            <div class="layer">
                <br>
                <img src="/img/logopoptrim.png" height="70px" alt="Logo POPLife">
                @if(!config('pcu.pop_opened'))
                    <p class="white-text flow-text">Próximamente, <b>POPLife Reborn</b>. <br> Estamos trabajando en ello.</p>
                    <p class="white-text flow-text">
                        @if($opening->format('d/m/Y H\h') != \Carbon\Carbon::now()->format('d/m/Y H\h'))
                            <small>Abrimos:</small>
                            <br><b v-cloak>@{{ opening.tz(timezone).format('dddd D [de] MMMM [a las] kk:mm') }} (@{{ timezone }})</b>
                        @else
                            Aún no hay fecha de apertura.
                            <br><small>Estate atento a esta página y al <u><a class="white-text" href="https://foro.poplife.wtf/faqreborn">foro</a></u>.</small>
                        @endif
                    </p>
                @else
                    <br>
                    <a href="/play" class="btn-large green waves-effect">Jugar <i class="material-icons right">play_circle_filled</i></a>
                @endif
            </div>
        </div>
    </div>
</div>