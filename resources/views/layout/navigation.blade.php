<div class="main-left  d-none d-md-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        {{--                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>--}}
        <span class="fs-4">Cars</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/client" class="nav-link {{ Request::is('client*') ? 'active' : 'text-white'}}"
               aria-current="{{ Request::is('/client') ? 'page' : ''}}">
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
        <li class="nav-item">
            <a href="/managers" class="nav-link {{ Request::is('managers*') ? 'active' : 'text-white'}}"
               aria-current="{{ Request::is('/') ? 'page' : ''}}">
                <img class="bi me-2" src="{{ asset('assets/imgs/people.svg') }}" alt="managers">
                Менеджеры
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
           id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            {{--                <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">--}}
            <strong>[Имя пользователя]</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            {{--                <li><a class="dropdown-item" href="#">New project...</a></li>--}}
            {{--                <li><a class="dropdown-item" href="#">Settings</a></li>--}}
            {{--                <li><a class="dropdown-item" href="#">Profile</a></li>--}}
            {{--                <li><hr class="dropdown-divider"></li>--}}
            <li><a class="dropdown-item" href="#">Выйти</a></li>
        </ul>
    </div>
</div>

<div class="d-md-none d-flex flex-column flex-shrink-0 bg-dark" style="width: 4.5rem;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        {{--                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>--}}
        <span class="fs-4">Cars</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/client" class="nav-link {{ Request::is('client*') ? 'active' : 'text-white'}}"
               aria-current="{{ Request::is('/client') ? 'page' : ''}}">
                <img src="{{ asset('assets/imgs/person.svg') }}" alt="clients">
            </a>
        </li>
        <li class="nav-item">
            <a href="/visits" class="nav-link {{ Request::is('visits*') ? 'active' : 'text-white'}}"
               aria-current="{{ Request::is('/') ? 'page' : ''}}">
                <img src="{{ asset('assets/imgs/person-vcard.svg') }}" alt="visits">
            </a>
        </li>
        <li class="nav-item">
            <a href="/managers" class="nav-link {{ Request::is('managers*') ? 'active' : 'text-white'}}"
               aria-current="{{ Request::is('/') ? 'page' : ''}}">
                <img src="{{ asset('assets/imgs/people.svg') }}" alt="managers">
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
           id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            {{--                <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">--}}
            <strong>[Имя пользователя]</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            {{--                <li><a class="dropdown-item" href="#">New project...</a></li>--}}
            {{--                <li><a class="dropdown-item" href="#">Settings</a></li>--}}
            {{--                <li><a class="dropdown-item" href="#">Profile</a></li>--}}
            {{--                <li><hr class="dropdown-divider"></li>--}}
            <li><a class="dropdown-item" href="#">Выйти</a></li>
        </ul>
    </div>
</div>
