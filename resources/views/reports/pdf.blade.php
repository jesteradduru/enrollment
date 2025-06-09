<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>List of Enrollees PDF</title>
    <style>
        @page { size: A4; margin: 20mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 16px; }
        .header .admin { font-weight: bold; }
        .header .school { font-size: 1.1em; font-weight: bold; }
        .header .list { font-weight: bold; }
        .header .sy { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 3px 4px; text-align: left; }
        th { background: #eee; font-size: 10px; }
        td { font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school">ABARIONGAN UNEG ELEMENTARY SCHOOL</div>
        <div class="list">LIST OF ENROLLEES</div>
        <div class="sy">SY. {{ $schoolYear }}</div>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:2%">#</th>
                <th style="width:10%">Learner's ID</th>
                <th style="width:7%">Code</th>
                <th style="width:20%">Student Name</th>
                <th style="width:7%">Level</th>
                <th style="width:7%">Grade</th>
                <th style="width:7%">Section</th>
                <th style="width:5%">Sex</th>
                <th style="width:12%">Birthdate</th>
                <th style="width:5%">Age</th>
                <th style="width:13%">Date enrolled</th>
                <th style="width:13%">Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollees as $i => $student)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $student->school_id }}</td>
                    <td>{{ $student->level }}</td>
                    <td>{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }} {{ $student->extension_name }}</td>
                    <td>{{ $student->level_type }}</td>
                    <td>{{ $student->grade }}</td>
                    <td>{{ $student->classroom_name }}</td>
                    <td>{{ ucfirst($student->gender[0]) }}</td>
                    <td>{{ \Carbon\Carbon::parse($student->date_of_birth)->format('m-d-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($student->date_of_birth)->age }}</td>
                    <td>{{ \Carbon\Carbon::parse($student->enrolled_at)->format('m-d-Y') }}</td>
                    <td>{{ $student->type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
