<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Notification</title>
</head>
<body class="text-center">
<form method="post" action="{{route('email.notification')}}">
    @csrf
    <label for="email">Enter email:</label>
    <input type="email" name="email" id="email">
    <button type="submit" value="Notification">Send Notification</button>
</form>
</body>
</html>
