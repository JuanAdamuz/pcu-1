@extends('layouts.pcu')

@section('title', 'Elección de nombre')

@section('content')
    <div id="app">
        <br>
        <div class="container">
            @include('setup.breadcrumb')
            <br>
            <h5>Nombre</h5>
            <p>Escoje el nombre que usarás en el juego y en el foro.</p>
            <div class="row">

                <div v-if="!success" class="col s12 l6">
                    <div v-if="! rulesClosed" class="card-panel">
                        <b>Reglas sobre los nombres</b>
                        <p>
                            Los nombres <b>deben ser realistas</b>, <b>serios</b> y apropiados.
                            <br>
                            <small>Mal: Aitor Tilla, Aquiles Castro, Adolf Hitler.</small>
                        </p>
                        <p>
                            Los nombres no deben ser diminutivos.
                            <br>
                            <small>Mal: Dani, Asi. Solo se permiten diminutivos de nombres compuestos: Josemari, Juanma.</small>
                        </p>
                        <p>
                            Los nombres deben estar <b>en español</b> o romanizados y fáciles de escribir.
                            <br>
                            <small>Mal: 毛泽东, Владимр, Björk Guðmundsdóttir, Juan Houmuloufmejnr</small>
                        </p>
                        <p>
                            Sin apóstrofes, guiones ni demás caracteres especiales. (tildes en nombres sí)
                            <br>
                            <small>Mal: O'Donell, Xing-Min</small>
                        </p>
                        <p>
                            <b>Un nombre, un apellido.</b> Sin segundos nombres ni nombres compuestos.
                        </p>
                        <p>En resumen, se buscan nombres que podrías encontrarte de forma normal por la calle en España.</p>
                        <a @click.prevent="dismissRules()" class="btn waves-effect white blue-text">Aceptar</a>
                    </div>
                    <form action="" method="POST" @submit.prevent="check()">
                        <div class="card-panel">
                            <div class="row">
                                <div class="col s12">
                                    <div class="input-field">
                                        <input :disabled="!rulesClosed || loading" v-model="firstName" type="text" id="firstName" data-length="14" placeholder="Manolo">
                                        <label for="firstName">Nombre <span class="red-text">*</span></label>
                                    </div>
                                </div>
                                <div class="col s12">
                                    <div class="input-field">
                                        <input :disabled="!rulesClosed || loading" v-model="lastName" type="text" id="lastName" data-length="14" placeholder="Pérez">
                                        <label for="lastName">Apellido <span class="red-text">*</span></label>
                                    </div>
                                </div>
                                <div class="col s12">

                                    <div v-if="!nameChecked || taken">
                                        <p v-if="fullname.length > 17"><small>El límite de caracteres es 17. Tu nombre tiene @{{ fullname.length }}.</small></p>
                                        <button :disabled="!rulesClosed || !changed || loading || firstName === '' || lastName === '' || fullname.length > 17" class="btn blue waves-effect">Comprobar disponibilidad</button>
                                        <p>
                                            <small>Generar nombre:</small>
                                            <br>
                                            <a class="right"><a :disabled="!rulesClosed || loading" class="btn white black-text dropdown-button waves-effect" @click.prevent="shuffle(true)"><i class="mdi mdi-human-male"></i></a></a>
                                            <a class="right"><a :disabled="!rulesClosed || loading" class="btn white black-text dropdown-button waves-effect" @click.prevent="shuffle(false)"><i class="mdi mdi-human-female"></i></a></a>
                                        </p>
                                        {{--<span class="right"><a :disabled="!rulesClosed || loading" data-activates="dropdownshuffle" class="btn-flat dropdown-button">Generar</a></span>--}}
                                    </div>
                                    <div v-cloak v-if="nameChecked && !taken">
                                        <p>
                                            <span class="green-text"><i class="material-icons tiny">check_circle</i> El nombre está disponible.</span>
                                            <br>
                                            <small>Una vez elegido, <b>el nombre no se puede cambiar</b>.</small>
                                        </p>
                                        <button :disabled="loading" @click.prevent="choose()" class="btn green waves-effect">Solicitar nombre</button>
                                        <br><small>Si quieres probar con otro, edítalo o genéralo de nuevo.</small>
                                    </div>

                                    <div v-cloak v-if="nameChecked && taken">
                                        <br>
                                        <p>
                                            <span class="red-text">La combinación de nombre y apellido no está disponible.</span>
                                            <br>
                                            <small>Cámbia algo e inténtalo de nuevo. Si te quedas sin ideas, usa el generador.</small>
                                        </p>
                                    </div>

                                    <div v-if="loading">
                                        <br>
                                        <br>
                                        <div class="progress">
                                            <div class="indeterminate"></div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>
                    <div v-cloak v-if="rulesClosed" class="card-panel">
                        <b>Reglas sobre los nombres</b>
                        <p>
                            Los nombres <b>deben ser realistas</b>, <b>serios</b> y apropiados.
                            <br>
                            <small>Mal: Aitor Tilla, Aquiles Castro, Adolf Hitler.</small>
                        </p>
                        <p>
                            Los nombres no deben ser diminutivos.
                            <br>
                            <small>Mal: Dani, Asi. Solo se permiten diminutivos de nombres compuestos: Josemari, Juanma.</small>
                        </p>
                        <p>
                            Los nombres deben estar <b>en español</b> o romanizados y fáciles de escribir.
                            <br>
                            <small>Mal: 毛泽东, Владимр, Björk Guðmundsdóttir, Juan Houmuloufmejnr</small>
                        </p>
                        <p>
                            Sin apóstrofes, guiones ni demás caracteres especiales. (tildes en nombres sí)
                            <br>
                            <small>Mal: O'Donell, Xing-Min</small>
                        </p>
                        <p>
                            <b>Un nombre, un apellido.</b> Sin segundos nombres ni nombres compuestos.
                        </p>
                        <p>En resumen, se buscan nombres que podrías encontrarte de forma normal por la calle en España.</p>
                    </div>
                </div>
                <div class="col hide-on-med-and-down m6">
                    <div id="dni">
                        <img src="/img/dni2.png" alt="">
                        <b v-model="name" id="dni_name">@{{ fullnameDNI }}</b>
                        <b id="dni_id">{{ $user->dni }}</b>
                    </div>
                </div>
                <div v-cloak v-if="success" class="col l6 s12 ">
                    <div class="card-panel green white-text">
                        <h5>¡Estupendo!</h5>
                        <p>Has elegido <b>@{{ fullname }}</b>.</p>
                        <p><small>El nombre deberá ser revisado por un moderador.
                                <br>Te notificaremos cuando haya sido revisado.</small></p>
                        <a href="" class="btn white green-text waves-effect">Continuar <i class="material-icons right">navigate_next</i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Dropdown Structure -->
    <ul id='dropdownshuffle' class='dropdown-content'>
        <li><a @click.prevent="shuffle(true)">Hombre</a></li>
        <li><a @click.prevent="shuffle(false)">Mujer</a></li>
    </ul>
@endsection
@section('head')
    <style>
        #dni {
            position: relative;
        }
        #dni_name {
            font-family: Helvetica;
            position:absolute;
            top: 56px;
            left: 164px
        }
        #dni_id {
            font-size: 85%;
            font-family: Helvetica;
            position:absolute;
            top: 105px;
            left: 164px
        }
    </style>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.0/vue.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var app = new Vue({
            el: '#app',
            data: {
                nameChecked: false,
                taken: true,
                loading: false,
                firstName: "",
                lastName: "",
                rulesClosed: false,
                changed: false,
                success: false,
                maleNames: [
                    'Antonio',
                    'Jose',
                    'Manuel',
                    'Francisco',
                    'Juan',
                    'David',
                    'Javier',
                    'Jesús',
                    'Daniel',
                    'Carlos',
                    'Miguel',
                    'Alejandro',
                    'Rafael',
                    'Ángel',
                    'Fernando',
                    'Pablo',
                    'Luis',
                    'Sergio',
                    'Jorge',
                    'Alberto',
                    'Álvaro',
                    'Diego',
                    'Adrián',
                    'Raúl',
                    'Enrique',
                    'Ramón',
                    'Vicente',
                    'Iván',
                    'Rubén',
                    'Óscar',
                    'Andrés',
                    'Joaquín',
                    'Santiago',
                    'Eduardo',
                    'Víctor',
                    'Roberto',
                    'Jaime',
                    'Mario',
                    'Ignacio',
                    'Alfonso',
                    'Salvador',
                    'Ricardo',
                    'Marcos',
                    'Jordi',
                    'Emilio',
                    'Julián',
                    'Julio',
                    'Guillermo',
                    'Gabriel',
                    'Tomás',
                    'Agustín',
                    'Marc',
                    'Gonzalo',
                    'Félix',
                    'Hugo',
                    'Ismael',
                    'Cristian',
                    'Mariano',
                    'Josep',
                    'Domingo',
                    'Aitor',
                    'Martín',
                    'Alfredo',
                    'Felipe',
                    'Héctor',
                    'César',
                    'Iker',
                    'Gregorio',
                    'Alex',
                    'Rodrigo',
                    'Albert',
                    'Xavier',
                    'Lorenzo'
                ],
                femaleNames: [
                    'María',
                    'Carmen',
                    'Isabel',
                    'Ana',
                    'Laura',
                    'Cristina',
                    'Antonia',
                    'Marta',
                    'Dolores',
                    'Lucía',
                    'Pilar',
                    'Elena',
                    'Sara',
                    'Paula',
                    'Mercedes',
                    'Raquel',
                    'Beatriz',
                    'Nuria',
                    'Silvia',
                    'Julia',
                    'Patricia',
                    'Irene',
                    'Andrea',
                    'Rocío',
                    'Mónica',
                    'Rocío',
                    'Alba',
                    'Ángela',
                    'Sonia',
                    'Alicia',
                    'Sandra',
                    'Susana',
                    'Marina',
                    'Yolanda',
                    'Natalia',
                    'Eva',
                    'Noelia',
                    'Claudia',
                    'Verónica',
                    'Amparo',
                    'Carolina',
                    'Carla',
                    'Nerea',
                    'Lorena',
                    'Sofía'
                ],
                lastNames: [
                    'García',
                    'López',
                    'Pérez',
                    'González',
                    'Sánchez',
                    'Martínez',
                    'Rodríguez',
                    'Fernández',
                    'Gómez',
                    'Martín',
                    'Hernández',
                    'Ruiz',
                    'Díaz',
                    'Álvarez',
                    'Moreno',
                    'Muñoz',
                    'Alonso',
                    'Gutiérrez',
                    'Sanz',
                    'Torres',
                    'Suárez',
                    'Ramírez',
                    'Vázquez',
                    'Navarro',
                    'Domínguez',
                    'Ramos',
                    'Castro',
                    'Gil',
                    'Flores',
                    'Morales',
                    'Blanco',
                    'Serrano',
                    'Molina',
                    'Ortiz',
                    'Santos',
                    'Ortega',
                    'Morrell',
                    'Delgado',
                    'Méndez',
                    'Castillo',
                    'Márquez',
                    'Cruz',
                    'Medina',
                    'Herrera',
                    'Marín',
                    'Núñez',
                    'Vega',
                    'Iglesias',
                    'Rojas',
                    'Reyes',
                    'Luna',
                    'Campos',
                    'Rubio',
                    'Peña',
                    'Ferrer',
                    'Lozano',
                    'Garrido',
                    'León',
                    'Aguilar',
                    'Cano',
                    'Arias',
                    'Herrero',
                    'Giménez',
                    'Fuentes',
                    'Díez',

                ]
            },
            methods: {
                dismissRules: function() {
                    this.rulesClosed = true;
                },
                shuffle: function(gender) {
                    app.loading = true;
                    if(gender) {
                        this.firstName = this.maleNames[Math.round(Math.random() * this.maleNames.length)];
                    } else {
                        this.firstName = this.femaleNames[Math.round(Math.random() * this.femaleNames.length)];
                    }
                    this.lastName = this.lastNames[Math.round(Math.random() * this.lastNames.length)];
                    app.loading = false;
                    if(app.fullname.length > 17) {
                        app.shuffle(gender);
                    }
                },
                update: function() {
                    this.checked = false;
                },
                check: function() {
                    if(this.nameChecked && !this.taken) {
                        return;
                    }
                    this.loading = true;
                    this.checked = false;
                    this.taken = true;
                    this.changed = false;
                    this.success = false,
                    axios.post('{{ route('setup-name-check') }}', {
                        firstName: this.firstName,
                        lastName: this.lastName
                    })
                    .then(function(response) {
                        app.loading = false;
                        if(response.data === "taken") {
                            app.taken = true;
                            app.nameChecked = true;
                        } else if(response.data === "OK") {
                            app.taken = false;
                            app.nameChecked = true;
                        }
                    }).catch(function(error) {
                        Materialize.toast(error.response.data.message, 4000);
                        app.loading = false;
                    });
                },
                choose: function() {
                    this.loading = true;
                    axios.post('{{ route('setup-name') }}',{
                        firstName: this.firstName,
                        lastName: this.lastName
                    })
                    .then(function(response) {
                        app.loading = false;
                        if(response.data === "taken") {
                            app.taken = true;
                            app.nameChecked = true;
                        } else if(response.data === "OK") {
                            app.success = true;
                        }
                    }).catch(function(error) {
                    Materialize.toast(error.response.data, 4000);
                    app.loading = false;
                    });
                }
            },
            computed: {
                fullname: function() {
                    return this.firstName + " " + this.lastName;
                },
                fullnameDNI: function() {
                    try {
                        var name = this.firstName.toUpperCase() + " " + this.lastName.toUpperCase();
                        if(name.length > 17) {
                            return "";
                        }
                        return name;
                    }catch (error) {
                        return "";
                    }
                },
            },
            watch: {
                firstName: function() {
                    this.nameChecked = false;
                    this.taken = true;
                    this.changed = true;
                },
                lastName: function() {
                    this.nameChecked = false;
                    this.taken = true;
                    this.changed = true;
                }
            }
        });
    </script>
@endsection