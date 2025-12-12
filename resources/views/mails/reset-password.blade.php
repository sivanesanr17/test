<!DOCTYPE html>
<html>
<body>
    <h2>Hello {{ $user->name }},</h2>
    <p>You requested a password reset. Click the button below to reset your password:</p>

    <a href="{{ url('password/reset', $token) }}" style="padding:10px 20px; background:#3490dc; color:white; text-decoration:none; border-radius:5px;">Reset Password</a>

    <p>If you did not request a password reset, ignore this email.</p>
</body>
</html>
