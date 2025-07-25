<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Approved</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <table width="100%" style="max-width: 600px; margin: auto; background-color: #fff; padding: 20px; border-radius: 6px;">
        <tr>
            <td>
                <h2 style="color: #2d3748;">Hello {{ $user->name }},</h2>
                <p>We are excited to let you know that your account has been <strong>approved</strong> by our admin team.</p>

                <p>You can now log in using the link below:</p>

                <p style="text-align: center; margin: 30px 0;">
                    <a href="{{ url('/login') }}" style="background-color: #38a169; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">
                        Login Now
                    </a>
                </p>

                <p>Thank you for joining us!</p>
                <p>– The Team</p>
            </td>
        </tr>
    </table>
</body>
</html>
