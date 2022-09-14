<div class="sidebar__nav border-top border-left">
    <span class="bars d-none padding-0-18"></span>
    <a class="header__logo  d-none" href="https://webamooz.net"></a>
    <x-user-user-photo />
    <ul>
        @foreach(config('sidebar.items') as $item)
            @if(is_null(array_get($item,'permissions')) || auth()->user()->canany(array_get($item,'permissions')))
            <li class="item-li {{ array_get($item,'icon') }} @if(request()->routeIs(array_get($item,'routes'))) is-active @endif">
                <a href="{{ route(array_get($item,'route')) }}">{{ array_get($item,'name') }}</a>
            </li>
            @endif
        @endforeach
    </ul>
</div>
