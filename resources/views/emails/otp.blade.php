<!DOCTYPE html>
<html>

<head>
    <title>Password Reset OTP</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; text-align: center;">
    <div style="max-width: 500px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); margin: auto;">

        <h2 style="color: #e63946; font-size: 24px;">🔒 Password Reset Request</h2>

        <p style="color: #333; font-size: 18px;"><strong>Dear User,</strong></p>

        <p style="color: #555; font-size: 16px;">We received a request to reset your password. Use the OTP below to proceed:</p>

        <div style="background: #e63946; color: white; padding: 15px; font-size: 24px; font-weight: bold; letter-spacing: 4px; display: inline-block; border-radius: 5px;">
            {{ $otp }}
        </div>

        <p style="color: #555; font-size: 16px; margin-top: 20px;">Enter this OTP to verify and reset your password.</p>

        <hr style="border: 0; height: 1px; background: #ddd; margin: 20px 0;">

        <p style="color: #444; font-size: 14px;">For any queries, feel free to contact <a href="https://github.com/Abdulwaheed78" style="color: #1d72b8; text-decoration: none; font-weight: bold;">Abdul Waheed</a>. We are always here to help! 😊</p>

        <p style="color: #1d72b8; font-weight: bold; font-size: 18px;">Best Regards,<br> {{ config('app.name') }}</p>
    </div>
</body>

</html>
