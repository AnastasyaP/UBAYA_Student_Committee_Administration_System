<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h2>Invitation Confirmation</h2>

    <p>
    You are invited as {{ $registration->position }}
    </p>

    <form method="POST" action="/invitation/accept/{{ $registration->invitation_token }}">
    @csrf
    <button type="submit">Accept</button>
    </form>

</body>
</html>