<?php
use Home\Model\ConfigListModel;
$genders = ConfigListModel::getConfigList(ConfigListModel::LIST_NAME_GENDER);
?>

<div class="container">
    <h1>{$Think.lang.VIEW_PROFILE}</h1>

    <if condition="!empty($validationError)">
    <div class="alert alert-danger">
        <ul>
            <li>{$validationError}</li>
        </ul>
    </div>
    </if>

    <div class="form form-horizontal">
        <div class="form-group">
            <label for="title" class="control-label col-sm-2">
                {$Think.lang.USER_NAME}{$Think.lang.COLON}
            </label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="user[name]"
                    placeholder="{$Think.lang.USER_NAME}" disabled="true"
                    value="{$user['name']}" autofocus="true">
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="control-label col-sm-2">
                {$Think.lang.EMAIL}{$Think.lang.COLON}
            </label>
            <div class="col-md-6">
                <input type="email" class="form-control" name="user[email]"
                    placeholder="{$Think.lang.EMAIL}" value="{$user['email']}"
                    disabled="true">
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
                    value="{$profile['last_name']}" disabled="true" readonly>
                <input type="text" name="profile[first_name]"
                    placeholder="{$Think.lang.FIRST_NAME}"
                    value="{$profile['first_name']}" disabled="true" readonly>
            <else />
                <input type="text" name="profile[first_name]"
                    placeholder="{$Think.lang.FIRST_NAME}" autofocus="true"
                    value="{$profile['first_name']}" disabled="true" readonly>
                <input type="text" name="profile[last_name]"
                    placeholder="{$Think.lang.LAST_NAME}"
                    value="{$profile['last_name']}" disabled="true" readonly>
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
                    class="form-control" disabled="true">
            </div>
        </div>

        <div class="form-group">
            <label for="postal_code" class="control-label col-sm-2">
                {$Think.lang.POSTAL_CODE}{$Think.lang.COLON}
            </label>
            <div class="col-md-2">
                <input type="text" id="postal_code" name="profile[postal_code]"
                    value="{:isset($profile['postal_code']) ? $profile['postal_code'] : ''}"
                    class="form-control" disabled="true">
            </div>
        </div>

        <div class="form-group">
            <label for="gender_key" class="control-label col-sm-2">
                {$Think.lang.GENDER}{$Think.lang.COLON}
            </label>
            <div class="col-md-2">
                <select id="gender_key" name="profile[gender_key]"
                    class="form-control" disabled="true"
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
                <a href="{:U('/profile/edit')}" class="btn btn-primary">{$Think.lang.CHANGE}</a>
                <a href="{:U('/posts')}" class="btn btn-default">{$Think.lang.CANCEL}</a>
            </div>
        </div>
    </div>
</div>
