<?php
$title = "密碼重置";
?>
<table style="width:600px;margin: 0 auto;font-family:微軟正黑體; font-size:14px;">
    <thead>
        <tr>
            <th style="text-align: center;font-size:25px;padding-bottom:20px;">申請密碼重置通知</th>
        </tr>
    </thead>
    <tbody>
        {{-- <tr>
            <td style="padding:5px 0 5px 40px;">密碼重置路徑：{{ $resetPwdUri }}</td>
        </tr> --}}
        <tr>
            <td> <a href="{{ $resetPwdUri }}">密碼重置路徑</a> </td>
        </tr>
    </tbody>
</table>