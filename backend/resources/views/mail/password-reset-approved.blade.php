<!DOCTYPE html>
<html lang="en">
<body style="margin: 0; padding: 24px; background-color: #f3f5f7; font-family: Arial, Helvetica, sans-serif; color: #1a1d21;">
    <div style="max-width: 480px; margin: 0 auto; background: #ffffff; border: 1px solid #e3e7ea; border-radius: 8px; padding: 24px;">
        <p style="margin: 0 0 4px; font-size: 12px; color: #5c6670;">Hospital Management System</p>
        <h1 style="margin: 0 0 16px; font-size: 18px;">Your password was reset</h1>

        <p style="margin: 0 0 12px; font-size: 14px; line-height: 1.6;">
            Hello {{ $recipientName }},
        </p>
        <p style="margin: 0 0 12px; font-size: 14px; line-height: 1.6;">
            An administrator approved your password reset request. Your new sign-in
            password is below — you will be asked to change it after signing in.
        </p>

        <p style="margin: 16px 0; padding: 12px 16px; background: #f3f5f7; border: 1px solid #e3e7ea; border-radius: 6px; font-family: 'Courier New', monospace; font-size: 15px; font-weight: bold; letter-spacing: 1px;">
            {{ $newPassword }}
        </p>

        <p style="margin: 0 0 8px; font-size: 14px; line-height: 1.6;">
            If you did not request this reset, contact the hospital administrator
            immediately.
        </p>

        <p style="margin: 24px 0 0; font-size: 11px; color: #5c6670;">
            This is an automated message from the Hospital Management System.
        </p>
    </div>
</body>
</html>
