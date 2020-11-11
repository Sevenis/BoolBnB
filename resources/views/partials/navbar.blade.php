
    <nav class="nav-container">
        <div class="container-center">
            <div class="nav">
                <div class="nav__logo">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/69/Airbnb_Logo_B%C3%A9lo.svg/1024px-Airbnb_Logo_B%C3%A9lo.svg.png"
                        alt="">
                </div>
                <div class="nav__search">
                    <button class="nav__search-button">
                        Inizia la ricerca
                        <div class="nav__search-icon">
                        </div>
                    </button>
                </div>
                <div class="nav__user">
                    <div class="nav__user">
                        <div class="nav__user-button">
                            
                        </div>
                        <ul class="nav__user__menu">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav__user__menu-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav__user__menu-item">
                                        <a class="nav__use-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav__user__menu-item">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>
    
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
    
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>