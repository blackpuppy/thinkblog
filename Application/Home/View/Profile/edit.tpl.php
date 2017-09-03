<?php
use Home\Model\ConfigListModel;
$genders = ConfigListModel::getConfigList(ConfigListModel::LIST_NAME_GENDER);
?>

<div class="container">
    <h1>{$Think.lang.EDIT_PROFILE}</h1>

    <if condition="!empty($userValidationError) || !empty($profileValidationError)">
    <div class="alert alert-danger">
        <ul>
            <if condition="!empty($userValidationError)">
            <li>{$userValidationError}</li>
            </if>
            <if condition="!empty($profileValidationError)">
            <li>{$profileValidationError}</li>
            </if>
        </ul>
    </div>
    </if>

    <form action="{:U('/profile/edit')}" method="post" class="form-horizontal">
        <input type="hidden" name="user[id]" value="{$user['id']}">
        <input type="hidden" name="profile[id]" value="{$profile['id']}">
        <input type="hidden" name="profile[user_id]" value="{$profile['user_id']}">

        <div class="form-group">
            <label for="title" class="control-label col-sm-2">
                {$Think.lang.USER_NAME}{$Think.lang.COLON}
            </label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="user[name]"
                    placeholder="{$Think.lang.USER_NAME}" readonly
                    value="{$user['name']}">
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="control-label col-sm-2">
                {$Think.lang.PASSWORD}{$Think.lang.COLON}
            </label>
            <div class="col-md-3">
                <input type="password" class="form-control" name="user[password]"
                    placeholder="{$Think.lang.PASSWORD}" autofocus="true">
            </div>
        </div>

        <div class="form-group">
            <label for="confirm_password" class="control-label col-sm-2">
                {$Think.lang.CONFIRM_PASSWORD}{$Think.lang.COLON}
            </label>
            <div class="col-md-3">
                <input type="password" class="form-control" name="user[confirm_password]"
                    placeholder="{$Think.lang.CONFIRM_PASSWORD}">
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="control-label col-sm-2">
                {$Think.lang.EMAIL}{$Think.lang.COLON}
            </label>
            <div class="col-md-6">
                <input type="email" class="form-control" name="user[email]"
                    placeholder="{$Think.lang.EMAIL}" value="{$user['email']}">
            </div>
        </div>

        <div class="form-group">
            <label for="title" class="control-label col-sm-2">
                {$Think.lang.FULL_NAME}{$Think.lang.COLON}
            </label>
            <div class="col-md-6">
            <if condition="substr(LANG_SET, 0, 2) === 'zh'">
                <input type="text" name="profile[last_name]"
                    placeholder="{$Think.lang.LAST_NAME}" autofocus="true"
                    value="{$profile['last_name']}">
                <input type="text" name="profile[first_name]"
                    placeholder="{$Think.lang.FIRST_NAME}"
                    value="{$profile['first_name']}">
            <else />
                <input type="text" name="profile[first_name]"
                    placeholder="{$Think.lang.FIRST_NAME}" autofocus="true"
                    value="{$profile['first_name']}">
                <input type="text" name="profile[last_name]"
                    placeholder="{$Think.lang.LAST_NAME}"
                    value="{$profile['last_name']}">
            </if>
            </div>
        </div>

        <div class="form-group">
            <label for="address" class="control-label col-sm-2">
                {$Think.lang.ADDRESS}{$Think.lang.COLON}
            </label>
            <div class="col-md-8">
                <input type="text" id="address" name="profile[address]"
                    value="{:isset($profile['address']) ? $profile['address'] : ''}"
                    class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="postal_code" class="control-label col-sm-2">
                {$Think.lang.POSTAL_CODE}{$Think.lang.COLON}
            </label>
            <div class="col-md-2">
                <input type="text" id="postal_code" name="profile[postal_code]"
                    value="{:isset($profile['postal_code']) ? $profile['postal_code'] : ''}"
                    class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="gender_key" class="control-label col-sm-2">
                {$Think.lang.GENDER}{$Think.lang.COLON}
            </label>
            <div class="col-md-2">
                <select id="gender_key" name="profile[gender_key]"
                    class="form-control"
                >
                    <option value="">
                        -- {$Think.lang.SELECT_ONE} --
                    </option>
                    <volist name="genders" id="gender">
                    <option value="{:$gender['list_key']}" <if condition="$gender['list_key'] === $profile['gender_key']">selected="selected"</if>>
                        {:L(strtoupper($gender['list_value_desc']))}
                    </option>
                    </volist>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="{$Think.lang.SAVE}" class="btn btn-success">
                <a href="{:U('/')}" class="btn btn-default">{$Think.lang.CANCEL}</a>
            </div>
        </div>
    </form>
</div>
