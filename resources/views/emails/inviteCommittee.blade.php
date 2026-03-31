<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; background:#f6f6f6; padding:20px;">

    <div style="max-width:500px; margin:auto; background:white; padding:30px; border-radius:8px; text-align:center; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
        
        <h2 style="margin-bottom:20px;">Committee Invitation</h2>

        <p>You are invited to join <strong>{{ $committee }}</strong>.</p>

        <p>As a <strong>{{ $position }}</strong> of <strong>{{ $division}}</strong> division</p>

        <div style="margin-top:30px;">
            <a href="{{ $link }}"
               style="
               display:inline-block;
               background-color:#4CAF50;
               color:white;
               text-decoration:none;
               padding:14px 28px;
               font-size:16px;
               font-weight:bold;
               border-radius:6px;
               text-align:center;
               ">
                Accept Invitation
            </a>
        </div>

    </div>

</body>
</html>