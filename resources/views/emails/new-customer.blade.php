<h1>Welcome {{ $user->present()->name }},</h1>

<p>
    You can now download our app and login using your registered phone number:
    {{ $user->phone }}
</p>
<p>
    Thanks!<br>
    Whatsloan team.
</p>