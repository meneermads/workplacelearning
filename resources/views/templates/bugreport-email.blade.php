<html>
<head></head>
<body>
<p>
        <table>
        <tbody>
        <tr>
                <td><strong>{{ Lang::get('dashboard.name') }} student:</strong></td>
                <td>{{ $student_name }}</td>
        </tr>
        <tr>
                <td><strong>{{ Lang::get('general.program') }}</strong></td>
                <td>{{ $education->ep_name }}</td>
        </tr>
        <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $student_email }}</td>
        </tr>
        <tr>
                <td><strong>{{ Lang::get('general.subject') }}:</strong></td>
                <td>{{ $subject }}</td>
        </tr>
        </tbody>
</table>
<h4>Detail:</h4>
<p>
    {{ $content }}
</p>
</body>
</html>