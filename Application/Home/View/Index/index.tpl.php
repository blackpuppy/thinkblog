<div class="container homepage">
    <h1>{$Think.lang.APPLICATION_NAME}</h1>

    <h4>{$Think.lang.APPLICATION_SHORT_DESC}</h4>

    <p>{$Think.lang.TECH_DESC}</p>

    <ul>
        <li>
            <input type="checkbox" id="remove_entry_in_url" name="remove_entry_in_url" disabled checked>
            <label for="remove_entry_in_url">{:L('REMOVE_ENTRY_IN_URL', ['entry_file' => 'index.php'])}</label>
        </li>
        <li>
            <input type="checkbox" id="dev_using_docker" name="dev_using_docker" disabled checked>
            <label for="dev_using_docker">{$Think.lang.DEV_USING_DOCKER}</label>
        </li>
        <li>
            <input type="checkbox" id="config_in_env" name="config_in_env" disabled checked>
            <label for="config_in_env">{$Think.lang.CONFIG_IN_ENV}</label>
        </li>
        <li>
            <input type="checkbox" id="basic_crud" name="basic_crud" disabled checked>
            <label for="basic_crud">{$Think.lang.BASIC_CRUD}</label>
        </li>
        <li>
            <input type="checkbox" id="build_with_laravel_mix" name="build_with_laravel_mix" disabled checked>
            <label for="build_with_laravel_mix">{$Think.lang.BUILD_WITH_LARAVEL_MIX}</label>
        </li>
        <li>
            <input type="checkbox" id="user_authentication" name="user_authentication" disabled checked>
            <label for="user_authentication">{$Think.lang.USER_AUTHENTICATION}</label>
            <ul>
                <li>
                    <input type="checkbox" id="function_registration" name="function_registration" disabled checked>
                    <label for="function_registration">{$Think.lang.FUNCTION_REGISTRATION}</label>
                </li>
                <li>
                    <input type="checkbox" id="function_login" name="function_login" disabled checked>
                    <label for="function_login">{$Think.lang.FUNCTION_LOGIN}</label>
                </li>
                <li>
                    <input type="checkbox" id="function_logout" name="function_logout" disabled checked>
                    <label for="function_logout">{$Think.lang.FUNCTION_LOGOUT}</label>
                </li>
                <li>
                    <input type="checkbox" id="function_remember_me" name="function_remember_me" disabled>
                    <label for="function_remember_me">{$Think.lang.FUNCTION_REMEMBER_ME}</label>
                </li>
                <li>
                    <input type="checkbox" id="function_recaptcha" name="function_recaptcha" disabled>
                    <label for="function_recaptcha">{$Think.lang.FUNCTION_RECAPTCHA}</label>
                </li>
                <li>
                    <input type="checkbox" id="function_forget_password" name="function_forget_password" disabled>
                    <label for="function_forget_password">{$Think.lang.FUNCTION_FORGET_PASSWORD}</label>
                </li>
            </ul>
        </li>
        <li>
            <input type="checkbox" id="user_authorization" name="user_authorization" disabled checked>
            <label for="user_authorization">{$Think.lang.USER_AUTHORIZATION}</label>
        </li>
        <li>
            <input type="checkbox" id="multi_languages" name="multi_languages" disabled checked>
            <label for="multi_languages">{$Think.lang.MULTI_LANGUAGES}</label>
        </li>
        <li>
            <input type="checkbox" id="model_association" name="model_association" disabled>
            <label for="model_association">{$Think.lang.MODEL_ASSOCIATION}</label>
            <ul>
                <li>
                    <input type="checkbox" id="relationship_user_profile" name="relationship_user_profile" disabled checked>
                    <label for="relationship_user_profile">{$Think.lang.RELATIONSHIP_USER_PROFILE}</label>
                </li>
                <li>
                    <input type="checkbox" id="relationship_user_post" name="relationship_user_post" disabled checked>
                    <label for="relationship_user_post">{$Think.lang.RELATIONSHIP_USER_POST}</label>
                </li>
                <li>
                    <input type="checkbox" id="relationship_post_tag" name="relationship_post_tag" disabled>
                    <label for="relationship_post_tag">{$Think.lang.RELATIONSHIP_POST_TAG}</label>
                </li>
                <li>
                    <input type="checkbox" id="relationship_user_comment" name="relationship_user_comment" disabled>
                    <label for="relationship_user_comment">{$Think.lang.RELATIONSHIP_USER_COMMENT}</label>
                </li>
                <li>
                    <input type="checkbox" id="relationship_post_comment" name="relationship_post_comment" disabled>
                    <label for="relationship_post_comment">{$Think.lang.RELATIONSHIP_POST_COMMENT}</label>
                </li>
            </ul>
        </li>
        <li>
            <input type="checkbox" id="web_api" name="web_api" disabled>
            <label for="web_api">{$Think.lang.WEB_API}</label>
            <ul>
                <li>
                    <input type="checkbox" id="web_api_authentication" name="web_api_authentication" disabled checked>
                    <label for="web_api_authentication">{$Think.lang.WEB_API_AUTHENTICATION}</label>
                </li>
                <li>
                    <input type="checkbox" id="web_api_authorization" name="web_api_authorization" disabled>
                    <label for="web_api_authorization">{$Think.lang.WEB_API_AUTHORIZATION}</label>
                </li>
                <li>
                    <input type="checkbox" id="web_api_others" name="web_api_others" disabled>
                    <label for="web_api_others">{$Think.lang.WEB_API_OTHERS}</label>
                </li>
            </ul>
        </li>
        <li>
            <input type="checkbox" id="angularjs_1_client" name="angularjs_1_client" disabled>
            <label for="angularjs_1_client">{$Think.lang.ANGULARJS_1_CLIENT}</label>
        </li>
        <li>
            <input type="checkbox" id="using_https" name="using_https" disabled>
            <label for="using_https">{$Think.lang.USING_HTTPS}</label>
        </li>
        <li>
            <input type="checkbox" id="using_https" name="using_https" disabled>
            <label for="using_https">{$Think.lang.UNIT_TESTING}</label>
        </li>
        <li>
            <input type="checkbox" id="using_https" name="using_https" disabled>
            <label for="using_https">{$Think.lang.MULTI_LANGUAGES_IN_DATA}</label>
        </li>
    </ul>

    <p>{$Think.lang.SOURCE_DESC}</p>

    <p>{$Think.lang.WELCOME_FEEDBACK}</p>
</div>
