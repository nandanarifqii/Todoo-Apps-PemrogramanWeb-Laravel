<!DOCTYPE html>
<html>

<head>
    <title>Undang Pengguna</title>
</head>

<body>
    <h1>Undang Pengguna</h1>

    @if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('invites.send') }}">
        @csrf

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <button type="submit">Kirim Undangan</button>
    </form>
    <br>
    <br>

</body>
<form action="{{ route('groups.index') }}">
    <input type="submit" value="<-- Go Back" />
</form>

</html>