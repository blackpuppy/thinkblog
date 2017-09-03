<div class="container">
    <h1>{$Think.lang.SIGN_UP}</h1>

    <if condition="!empty($validationError)">
    <div class="alert alert-danger">
        <ul>
            <li>{$validationError}</li>
        </ul>
    </div>
    </if>

    <form action="" method="post" class="form-horizontal">
        <div class="form-group">
            <label for="title" class="control-label col-sm-2">
                {$Think.lang.USER_NAME}{$Think.lang.COLON}
            </label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="name"
                    placeholder="{$Think.lang.USER_NAME}"
                    value="{$user['name']}" autofocus="true">
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="control-label col-sm-2">
                {$Think.lang.PASSWORD}{$Think.lang.COLON}
            </label>
            <div class="col-md-3">
                <input type="password" class="form-control"
                    name="password" placeholder="{$Think.lang.PASSWORD}">
            </div>
        </div>

        <div class="form-group">
            <label for="confirm_password" class="control-label col-sm-2">
                {$Think.lang.CONFIRM_PASSWORD}{$Think.lang.COLON}
            </label>
            <div class="col-md-3">
                <input type="password" class="form-control" name="confirm_password"
                    placeholder="{$Think.lang.CONFIRM_PASSWORD}">
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="control-label col-sm-2">
                {$Think.lang.EMAIL}{$Think.lang.COLON}
            </label>
            <div class="col-md-6">
                <input type="email" class="form-control" name="email"
                    placeholder="{$Think.lang.EMAIL}" value="{$user['email']}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-4">
                <input type="submit" value="{$Think.lang.SIGNUP}" class="btn btn-success">
                <a href="{:U('/login')}" class="btn btn-default">{$Think.lang.LOGIN}</a>
            </div>
        </div>
    </form>
</div>
