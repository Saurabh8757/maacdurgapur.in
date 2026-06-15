
<!DOCTYPE html>
<html>
<head>
    <title>Your Email Title</title>
</head>
<body>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table width="600" cellspacing="0" cellpadding="0">
                    <!-- Header -->
                    <tr>
                        <td align="center" bgcolor="#f7f7f7" style="padding: 20px;">
                            <h1>Your Company Name</h1>
                        </td>
                    </tr>
                    <!-- Email Content -->
                    <tr>
                        <td style="padding: 20px;">

                            # New Counselling Request

                            A user has submitted a counselling request with the following details:

                            <p>Name: {{ $name }}</p>
                            <p>Phone: {{ $phone }}</p>
                            <p>Email: {{ $email }}</p>
                            <p>Course ID: {{ $course }}</p>

                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td align="center" bgcolor="#f7f7f7" style="padding: 20px;">
                            &copy; 2023 Your Company. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
