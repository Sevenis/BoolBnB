@extends('layouts.app')

@section('content')

    <div class="signup_container">
        <h2>Registra il tuo appartamento</h2>



        <form id="creazione" action="{{ route('host.store') }}" name="creazione" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method("POST")

            <!-- TITOLO -->
            <div class="input-row">
                <label for="title">Titolo</label>
                <input type="text" id="title" name="title" placeholder="Inserisci il titolo" class="" required />
                <p class="message"></p>
            </div>

            <!-- INDIRIZZO -->
            <div class="input-row">
                <label for="address">Indirizzo</label>
                <input type="text" id="address" name="address" placeholder="l'indirizzo del tuo appartamento" class=""
                    required />
                <p class="message"></p>
            </div>

            <div class="input-row">
                <div class="input-group">
                    <div class="label-input">
                        <label for="city">Città</label>
                        <input id="city" type="text" name="city">
                        <p class="message"></p>
                    </div>
                    <div class="label-input">
                        <label for="postal_code">Codice Postale</label>
                        <input id="postal-code" type="text" name="postal_code">
                        <p class="message"></p>
                    </div>
                    <div class="label-input">
                        <label for="country">Nazione</label>
                        <input id="country" type="text" name="country">
                        <p class="message"></p>
                    </div>
                </div>
            </div>

            <div class="input-row">
                <label for="description">Descrizione dell'appartamento</label>
                <textarea id="description" name="description" rows="10"></textarea>
                <p class="message"></p>
            </div>

            <!-- CARATTERISTICHE-->
            <div class="input row">
                <div class="input-group">
                    <div class="label-input">
                        <label for="daily_price">Prezzo per notte</label>
                        <input id="daily-price" type="number" name="daily_price">
                        <p class="message"></p>
                    </div>
                    <div class="label-input">
                        <label for="sm">Metri quadri</label>
                        <input id="sm" type="number" name="sm">
                        <p class="message"></p>
                    </div>
                    <div class="label-input">
                        <label for="rooms">Stanze</label>
                        <input id="rooms" type="number" name="rooms">
                        <p class="message"></p>
                    </div>
                    <div class="label-input">
                        <label for="beds">Posti letto</label>
                        <input id="beds" type="number" name="beds">
                        <p class="message"></p>
                    </div>
                    <div class="label-input">
                        <label for="bathrooms">Bagni</label>
                        <input id="bathrooms" type="number" name="bathrooms">
                        <p class="message"></p>
                    </div>
                </div>

                <!-- SERVIZI -->
                <div class="input-group">
                    @foreach ($services as $service)
                        <div class="label-input">
                            <span><i class="{{ $service->icon }}"></i></span>
                            <label for="services">{{ $service->service }}</label>
                            <input type="checkbox" name="services" value="{{ $service->id }}">
                        </div>
                    @endforeach

                </div>

                <!-- IMMAGINI -->
                <h5>aggiungi le tue immagini</h5>
                <div class="container-upload">
                    {{-- <input type="file" name="img" enctype="multipart/form-data"
                        class="img-input form-control-file" id="img" accept="image/*"> --}}
                    <input type="file" name="img[]" id="img" accept="image/*" multiple>
                </div>
                <a id="add-img" href="#"> <i class="fas fa-plus-circle"></i> </a>


                <!-- CAMPI HIDDEN -->

                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                <input id="latitude" type="hidden" name="latitude" value="">
                <input id="longitude" type="hidden" name="longitude" value="">





                <input type="submit" id="crea">



            </div>
        </form>





    </div>
@endsection
