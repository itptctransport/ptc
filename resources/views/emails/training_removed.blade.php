<!DOCTYPE html>
<html>
<head>
    <title>Training Assignment Cancelled</title>
</head>
<body>
    <h1>Dear {{ $driver->name }},</h1>
    <p>We would like to inform you that your assignment to the following training course has been <strong>cancelled</strong>.</p>

    <h2>Training Details:</h2>
    <ul>
        <li>Training Type: {{ $training->trainingType->name }}</li>
        <li>Course Name: {{ $training->trainingCourse->name }}</li>
        <li>Company: {{ $training->company->name }}</li>
        <li>Start Date: {{ \Carbon\Carbon::parse($training->from_date)->format('d/m/Y') }}</li>
        <li>End Date: {{ \Carbon\Carbon::parse($training->to_date)->format('d/m/Y') }}</li>
    </ul>

    <p>This may have been done in error or due to a scheduling change. If you have any questions, please contact our support team.</p>

    <p>We apologise for any inconvenience caused.</p>

    <p>Best regards,<br>
    Support Team<br>
    PTC Support Team<br>
    Suite #31, Unimix House, Abbey Road Park Royal, London, NW10 7TR</p>
    <p><img src="https://erp.c4u-online.co.uk/storage/uploads/logo/email%20footer%20unimix_small.png" style="vertical-align:middle;border-style:none"></p>
    <p><img src="https://erp.c4u-online.co.uk/storage/uploads/logo/Email%20Footer%20logo%20small.png" style="vertical-align:middle;border-style:none"></p>
</body>
</html>
