<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Password Reset</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
    .container { background: #fff; margin: 40px auto; padding: 20px; max-width: 600px; border-radius: 5px; }
    .btn { display: inline-block; padding: 12px 20px; margin: 20px 0; background-color: #1a73e8; color: white; text-decoration: none; border-radius: 5px; }
    p { font-size: 16px; color: #333; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Password Reset Request</h2>
    <p>Hello {{ $name }},</p>
    <p>You recently requested to reset your password. Click the button below to proceed:</p>
    <p style="text-align:center;">
      <a href="{{ url('password/reset', $token) }}" class="btn">Reset Password</a>
    </p>
    <p>If you didn’t request this, please ignore this email or contact support if you need help.</p>
    <p>Thanks,<br>The Team</p>
  </div>
</body>
</html>
