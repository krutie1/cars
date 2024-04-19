<div class="main-left d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        {{--                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>--}}
        <span class="fs-4">Cars</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/client" class="nav-link {{ Request::is('client*') ? 'active' : 'text-white'}}" aria-current="{{ Request::is('/client') ? 'page' : ''}}">
                {{--                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#home"></use></svg>--}}
                Клиенты
            </a>
        </li>
        <li>
            <a href="/visits" class="nav-link {{ Request::is('visits*') ? 'active' : 'text-white'}}" aria-current="{{ Request::is('/') ? 'page' : ''}}">
                {{--                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>--}}
                Посещения
            </a>
        </li>
        <li>
            <a href="/managers" class="nav-link {{ Request::is('managers*') ? 'active' : 'text-white'}}" aria-current="{{ Request::is('/') ? 'page' : ''}}">
                {{--                        <svg class="bi me-2" width="16" height="16"><use xlink:href="#table"></use></svg>--}}
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
