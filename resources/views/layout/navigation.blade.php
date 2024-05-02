<div class="main-left  d-none d-md-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        {{--                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>--}}
        <img class="bi me-2 logo-img" src="{{ asset('assets/imgs/logo.jpg') }}" alt="Dido Cars">
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/clients" class="nav-link {{ Request::is('clients*') ? 'active' : 'text-white'}}"
               aria-current="{{ Request::is('/clients') ? 'page' : ''}}">
                <img class="bi me-2" src="{{ asset('assets/imgs/person.svg') }}" alt="clients">
                Клиенты
            </a>
        </li>
        <li class="nav-item">
            <a href="/visits" class="nav-link {{ Request::is('visits*') ? 'active' : 'text-white'}}"
               aria-current="{{ Request::is('/') ? 'page' : ''}}">
                <img class="bi me-2" src="{{ asset('assets/imgs/person-vcard.svg') }}" alt="visits">
                Посещения
            </a>
        </li>
        @if(auth()->user()->isAdmin())
            <li class="nav-item">
                <a href="/managers" class="nav-link {{ Request::is('managers*') ? 'active' : 'text-white'}}"
                   aria-current="{{ Request::is('/') ? 'page' : ''}}">
                    <img class="bi me-2" src="{{ asset('assets/imgs/people.svg') }}" alt="managers">
                    Менеджеры
                </a>
            </li>
            {{--            <li class="nav-item">--}}
            {{--                <a href="/payments" class="nav-link {{ Request::is('payments*') ? 'active' : 'text-white'}}"--}}
            {{--                   aria-current="{{ Request::is('/') ? 'page' : ''}}">--}}
            {{--                    <img class="bi me-2" src="{{ asset('assets/imgs/cash.svg') }}" alt="cash">--}}
            {{--                    Платежи--}}
            {{--                </a>--}}
            {{--            </li>--}}
        @endif
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
           id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            {{--                <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">--}}
            <strong>{{ auth()->user()-> name }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            {{--                <li><a class="dropdown-item" href="#">New project...</a></li>--}}
            @if(auth()->user()->isAdmin())
                <li><a class="dropdown-item" href="/payments">Платежи</a></li>
                <li><a class="dropdown-item" href="/prices">Тариф</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
            @endif
            <li><a class="dropdown-item" href="{{ route('auth.logout') }}" id="logout-btn">Выйти</a></li>
        </ul>
    </div>
</div>

<div class="d-md-none d-flex flex-column flex-shrink-0 bg-dark" style="width: 4.5rem;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        {{--                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>--}}
        <img class="bi logo-img" src="{{ asset('assets/imgs/logo.jpg') }}" alt="Dido Cars">
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/clients" class="nav-link {{ Request::is('clients*') ? 'active' : 'text-white'}}"
               aria-current="{{ Request::is('/clients') ? 'page' : ''}}">
                <img src="{{ asset('assets/imgs/person.svg') }}" alt="clients">
            </a>
        </li>
        <li class="nav-item">
            <a href="/visits" class="nav-link {{ Request::is('visits*') ? 'active' : 'text-white'}}"
               aria-current="{{ Request::is('/') ? 'page' : ''}}">
                <img src="{{ asset('assets/imgs/person-vcard.svg') }}" alt="visits">
            </a>
        </li>
        @if(auth()->user()->isAdmin())
            <li class="nav-item">
                <a href="/managers" class="nav-link {{ Request::is('managers*') ? 'active' : 'text-white'}}"
                   aria-current="{{ Request::is('/') ? 'page' : ''}}">
                    <img src="{{ asset('assets/imgs/people.svg') }}" alt="managers">
                </a>
            </li>
            {{--            <li class="nav-item">--}}
            {{--                <a href="/payments" class="nav-link {{ Request::is('payments*') ? 'active' : 'text-white'}}"--}}
            {{--                   aria-current="{{ Request::is('/') ? 'page' : ''}}">--}}
            {{--                    <img src="{{ asset('assets/imgs/cash.svg') }}" alt="cash">--}}
            {{--                </a>--}}
            {{--            </li>--}}
        @endif
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
           id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            {{--                <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">--}}
            <strong>{{ auth()->user()-> name }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            @if(auth()->user()->isAdmin())
                <li><a class="dropdown-item" href="/payments">Платежи</a></li>
                <li><a class="dropdown-item" href="/prices">Тариф</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
            @endif
            <li><a class="dropdown-item" href="{{ route('auth.logout') }}" id="logout-btn">Выйти</a></li>
        </ul>
    </div>
</div>
