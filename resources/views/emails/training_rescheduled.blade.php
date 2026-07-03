<!DOCTYPE html>
<html>
<head>
    <title>Training Rescheduled</title>
</head>
<body>
    <h1>Dear {{ $driver->name }},</h1>
    <p>Please be advised that your upcoming training course has been <strong>rescheduled</strong>. Please find the updated details below.</p>

    <h2>Updated Training Details:</h2>
    <ul>
        <li>Training Type: {{ $training->trainingType->name }}</li>
        <li>Course Name: {{ $training->trainingCourse->name }}</li>
        <li>Company: {{ $training->company->name }}</li>
        <li>Description: {{ $training->description }}</li>
        <li>New Start Date: {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }}</li>
        <li>New End Date: {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</li>
        <li>From Time: {{ $training->from_time }}</li>
        <li>To Time: {{ $training->to_time }}</li>
        <li>Duration: {{ $training->trainingCourse->duration }} days</li>
    </ul>

    <p>A calendar invite with the updated schedule is attached. Please update your calendar accordingly.</p>

    <p>Should you have any questions or require further assistance, feel free to contact our support team.</p>

    <p>Best regards,<br>
    Support Team<br>
    PTC Support Team<br>
    Suite #31, Unimix House, Abbey Road Park Royal, London, NW10 7TR</p>
    <p><img src="https://erp.c4u-online.co.uk/storage/uploads/logo/email%20footer%20unimix_small.png" style="vertical-align:middle;border-style:none"></p>
    <p><img src="https://erp.c4u-online.co.uk/storage/uploads/logo/Email%20Footer%20logo%20small.png" style="vertical-align:middle;border-style:none"></p>
</body>
</html>
