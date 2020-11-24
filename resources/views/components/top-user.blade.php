<ul class="nav navbar-nav navbar-right">
    <li>
        <i class="fas fa-user-tie user-top-icon"></i>
        <span class="user-top-name">{{ $user->name }}</span>
        <a class="btn btn-default btn-sm user-logout-btn" href="{{ url('/logout') }}">
            Logout
        </a>
    </li>
</ul>
