<h1>Hi {{ $user->present()->name }},</h1>

<p>
    A new document was added to your Whatsloan account.
</p>
<ul>
    <li>{{ $document->name }}</li>
</ul>
<p>
    Thanks!<br>
    Whatsloan team.
</p>