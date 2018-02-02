<div>
    <p>{{ $question->question }}</p>
    @if($question->type == 'text')
        <textarea v-model="answer" data-length="500" maxlength="500" minlength="15" :disabled="loading" class="materialize-textarea" name="answer" id="answer" cols="30" rows="10" placeholder="Introduce aquí tu respuesta"></textarea>
    @endif
    @if($question->type == 'short')
        <input v-model="answer" :disabled="loading" type="text" id="answer" name="answer" placeholder="Introduce aquí tu respuesta">
    @endif
    @if($question->type == 'single')
        @php
            $shuffled = collect($question->options)->shuffle()->all();
        @endphp
        @foreach($shuffled as $option)
            <p>
                <input value="{{ $option['id'] }}" v-model="answer" :disabled="loading" class="with-gap" name="answer" type="radio" id="{{ $option['id'] }}" />
                <label for="{{ $option['id'] }}">{{ $option['text'] }}</label>
            </p>
        @endforeach
        <small v-cloak v-if="answer != null"><a href="#" @click.prevent="answer = null">Desmarcar respuesta</a></small>
    @endif
    @if($question->type == 'multiple')
        @php
            $shuffled = collect($question->options)->shuffle()->all();
        @endphp
        @foreach($shuffled as $option)
            <p>
                <input value="{{ $option['text'] }}" v-model="answer" :disabled="loading" class="filled-in" name="answer" type="checkbox" id="{{ $option['id'] }}"  />
                <label for="{{ $option['id'] }}">{{ $option['text'] }}</label>
            </p>
        @endforeach
    @endif
    @if($question->type == 'select')
        <select name="" id="" class="browser-default">
            @foreach($question->options as $option)
                <option value="{{ $option['id'] }}">{{ $option['text'] }}</option>
            @endforeach
        </select>
    @endif
</div>