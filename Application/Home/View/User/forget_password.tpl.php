<div class="container">
    <h1>{$Think.lang.FORGET_PASSWORD}</h1>

    <if condition="!empty($validationError)">
    <div class="alert alert-danger">
        <ul>
            <li>{$validationError}</li>
        </ul>
    </div>
    </if>

    <form action="" method="post" class="form-horizontal">
        <div class="form-group">
            <label for="name" class="control-label col-sm-2">
                {$Think.lang.USER_NAME}{$Think.lang.COLON}
            </label>
            <div class="col-md-3">
                <input type="text" class="form-control" id="name" name="name"
                    placeholder="{$Think.lang.USER_NAME}"
                    value="{:isset($passwordReset['name']) ? $passwordReset['name'] : ''}"
                    autofocus="true">
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="control-label col-sm-2">
                {$Think.lang.EMAIL}{$Think.lang.COLON}
            </label>
            <div class="col-md-6">
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="{$Think.lang.EMAIL}" value="{$passwordReset['email']}">
            </div>
        </div>

        <div class="form-group">
            <label for="recaptcha_img" class="control-label col-sm-2">
                {$Think.lang.RECAPTCHA}{$Think.lang.COLON}
            </label>
            <div class="col-md-4">
                <img id="recaptcha_img" alt="点击更换" title="点击更换"
                    src="{:U('/recaptcha',array())}" class=""
                >
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-md-2">
                <input type="text" class="form-control" id="recaptcha" name="recaptcha"
                    placeholder="{$Think.lang.RECAPTCHA_INPUT}"
                >
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-4">
                <input type="submit" class="btn btn-success"
                    value="{$Think.lang.RESET_PASSWORD}" disabled
                >
                <a href="{:U('/login')}" class="btn btn-default">{$Think.lang.LOGIN}</a>
                <a href="{:U('/signup')}" class="btn btn-default">{$Think.lang.SIGN_UP}</a>
            </div>
        </div>
    </form>
</div>
