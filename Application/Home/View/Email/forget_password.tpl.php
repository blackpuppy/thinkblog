<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body>
    <h2>{$Think.lang.RESET_PASSWORD}</h2>

    <div>
        <p>{:L('RESET_DEAR_USER', ['full_name' => getUserFullName($user)])}</p>

        <p>{$Think.lang.RESET_OPEN_DESC}</p>
        <p><a href="{:U('/reset_password', null, true, true)}">{:U('/reset_password', null, true, true)}</a></p>
        <p>{$Think.lang.RESET_CLOSING_DESC}</p>

        <p>{$Think.lang.RESET_THANKS}</p>
        <p><a href="https://github.com/blackpuppy/thinkblog">{$Think.lang.RESET_TEAM}</a></p>
    </div>
</body>

</html>
