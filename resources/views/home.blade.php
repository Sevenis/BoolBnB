@extends('layouts.app')
@section('content')
    <header class="jumbotron">
        <div class="container-center container-fullheight">
            <div class="jumbotron__text">
                <p class="jumbotron__text-banner">Vicino e bello</p>
                <form action="{{ route('search.store') }}" method="POST">
                @csrf
                @method('POST')
                <button type="submit" class="jumbotron__text-button">Esplora i soggiorni nei dintorni</button>
                <input type="hidden" name="address" id="ip-home-search" value="">
                <input type="hidden" name="range" id="ip-home-search" value="100">
                </form>
            </div>
        </div>
    </header>
    <div class="container-center">
        <section class="highlited">
            <p class="sponsor__home-title">In evidenza</p>
            <div class="sponsor__home">
                @if(count($apartment) > 0)
                @for ($i = 0; $i < 4 && $i < count($apartment); $i++)
                    <div class="sponsor__home-card">
                        <div class="sponsor__home-card-img">
                            <img src="{{$apartment[$i]->images[0]->path}}" alt="{{$apartment[$i]->title}}"
                                alt="">
                        </div>
                        <div class="sponsor__home-card-text">
                            <p>{{ $apartment[$i]->title }}</p>
                        </div>
                    </div>
                @endfor
                @endif
        </section>
    </div>
@endsection
