<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('admin.v1.dashboard.index') }}">
                <small><em>{{ trans('admin.common.title') }}</em></small>
            </a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    {{ Auth::user()->first_name.' '.Auth::user()->last_name }} <span class="caret"></span>
                </a>

                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ route('admin.v1.password.changeForm') }}">Change Password</a></li>
                    <li><a href="{{ route('admin.v1.auth.logout.get') }}">Logout</a></li>
                </ul>
            </li>
         </ul>

    </div>
</nav>