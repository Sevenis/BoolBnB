@extends('layouts.app')
@section('content')
    <div class="container-center">
        @if (session('status'))
        {{session('status')}}
        @endif
        <section class="top-section">
            <span class="hidden" id="app-id">{{ $apartment->id }}</span>
            <div class="title-apt">
                <p class="title">{{ $apartment->title }}</p>
                <a class="address-apt" href="#">{{ $apartment->address }}, {{ $apartment->city }},
                    {{ $apartment->country }}</a>
            </div>
        </section>
        <div class="container-slider-app">
            <section class="slider-section">
                <div class="apt-images">
                     <i class="far fa-arrow-alt-circle-left arrow-slider-sx"></i>
                    @for ($i = 0; $i < $apartment->images->count('id'); $i++)
                        <img class="apt-image {{ $i == 0 ? 'active first' : ($i == $apartment->images->count('id') - 1 ? 'hidden last' : 'hidden') }}"
                            src="{{asset('storage/'.$apartment->images[$i]->path) }}" alt="{{ $apartment->title }}">
                    @endfor
                    <i class="far fa-arrow-alt-circle-right arrow-slider-dx"></i>
                </div>
            </section>
        </div>

        <section class="info-apt-section">
            <div class="info-box-sx">
                <div class="info-apt-summary">
                    <div class="apt-summary">
                        <p class="info-host">Host: {{ $apartment->user->name }} - Prezzo:
                            €{{ $apartment->daily_price }}/giorno</p>
                        <p class="info-apt"> nr. stanze: {{ $apartment->rooms }}, nr. letti: {{ $apartment->beds }} - nr.
                            bagni: {{ $apartment->bathrooms }} - mq: {{ $apartment->sm }}</p>
                        {{-- <p class="price">Prezzo giornaliero:
                            €{{ $apartment->daily_price }}</p> --}}
                    </div>
                    <div class="host-logo">
                        <img src="" alt="">
                    </div>
                </div>
                <hr class="split-line">
                <div class="apt-description">
                    <p>{{ $apartment->description }}</p>
                </div>
                <hr class="split-line">
                <div class="services-box">
                    <p class="services-title">Servizi</p>
                    <ul class="services">
                        @foreach ($apartment->services as $service)
                            <li class="service">
                                <div class="service-head">
                                    <i class="service-icon {{ $service->icon }}"></i>
                                    <p>{{ $service->service }}</p>
                                </div>
                                <span>{{ $service->description }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="info-box-dx">
                <div class="send-message-box">
                    <p class="message-title">Contatta l'Host!</p>
                    <div class="message-form">
                        <form class="" action="{{ route('send.message') }}" method="POST">
                            @method('POST')
                            @csrf
                           <p class="firstname-message">
                                <label for="fname">Nome:</label>
                                <input type="text" id="firstname" name="name" value="{{ Auth::check() ? Auth::user()->name : '' }}"
                                    name="firstname">
                            </p>
                            <p class="lastname-message">
                                <label for="lname">Cognome:</label>
                                <input type="text" id="lastname" name="lastname" value="{{ Auth::check() ? Auth::user()->lastname : '' }}"
                                    name="lastname">
                            </p>
                            <p class="email-message">
                                <label for="email">Email:</label>
                                <input type="email" id="email" value="{{ Auth::check() ? Auth::user()->email : '' }}"
                                    name="email">
                            </p>

                            <label for="message">Messaggio</label>
                            <textarea  name="message" id="message"  rows="10">{{ Auth::check() ?'Buongiorno sono '. Auth::user()->name : '' }}</textarea>
                            <input type="hidden" value="{{ $apartment->id }}" name="apartment_id">
                            <p class "send-message">
                                <input type="submit"></input>
                            </p>
                        </form>
                    </div>

                </div>
            </div>
        </section>


        <section class="map-section">

            <div class="map-apartment" id="map"></div>

            {{-- <div class="message-box">

            </div> --}}
        </section>
    </div>
<script src="{{ asset('js/apt.js') }}"></script>
@endsection
