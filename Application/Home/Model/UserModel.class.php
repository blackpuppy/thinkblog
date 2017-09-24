<?php
namespace Home\Model;

use Carbon\Carbon;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Home\Model\BaseModel;

class UserModel extends BaseModel
{
    // protected $tableName = 'user';

    protected $_validate = [
        ['name', 'require', '{%NAME_REQUIRED}', self::MUST_VALIDATE],
        ['name', '', '{%NAME_DUPLICATE}', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT],

        ['password', 'require', '{%PASSWORD_REQUIRED}', self::MUST_VALIDATE,   '',       self::MODEL_INSERT],
        ['password', '5,72',    '{%PASSWORD_LENGTH}',   self::MUST_VALIDATE,   'length', self::MODEL_INSERT],
        ['password', 'require', '{%PASSWORD_REQUIRED}', self::VALUE_VALIDATE,  '',       self::MODEL_UPDATE],
        ['password', '5,72',    '{%PASSWORD_LENGTH}',   self::VALUE_VALIDATE,  'length', self::MODEL_UPDATE],

        ['confirm_password', 'password', '{%CONFIRM_PASSWORD_DISMATCH}', self::EXISTS_VALIDATE, 'confirm'],

        ['email',
            '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',
            '{%EMAIL_INVALID}', self::EXISTS_VALIDATE, 'regex'],
        ['email', '', '{%EMAIL_DUPLICATE}', self::EXISTS_VALIDATE, 'unique'],

        // 用户登录时的验证规则
        ['name',     'require', '{%NAME_REQUIRED}',     self::MUST_VALIDATE, '',       self::MODEL_LOGIN],
        ['password', 'require', '{%PASSWORD_REQUIRED}', self::MUST_VALIDATE, '',       self::MODEL_LOGIN],
        ['password', '5,72',    '{%PASSWORD_LENGTH}',   self::MUST_VALIDATE, 'length', self::MODEL_LOGIN],
    ];

    protected $_filter = [
        'password' => ['encryptPassword'],
    ];

    protected $_link = [
        'Profile' => [
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'Profile',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'profile',
        ],
        'Post' => [
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'Post',
            'foreign_key'   => 'author_user_id',
            'mapping_name'  => 'posts',
            'mapping_order' => 'create_at desc',
        ],
        // 'Role' => [
        //     'mapping_type'         => self::MANY_TO_MANY,
        //     'class_name'           => 'Role',
        //     'mapping_name'         => 'roles',
        //     'foreign_key'          => 'user_id',
        //     'relation_foreign_key' => 'role_id',
        //     'relation_table'       => 'user_group',
        // ],
        'Role' => [
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'UserRole',
            'mapping_name'  => 'roles',
            'foreign_key'   => 'user_id',
            'mapping_order' => 'id asc',
        ],
    ];

    /**
     * 包含敏感信息的属性/字段。
     */
    protected $_sensitive = [
        'password',
        'confirm_password',
    ];

    /**
     * 尝试用给定的用户名和密码登录系统。
     * @param  string $username 给定用户名
     * @param  string $password 给定密码
     * @return bool|array       若登录成功，则返回用户模型数据；否则返回false
     */
    public function login($username, $password)
    {
        $msg = PHP_EOL . 'Home\Model\UserModel::login():'
            . PHP_EOL . '  $username = ' . $username
            . PHP_EOL . '  $password = ' . $password;

        $authenticated = false;

        $user = $this->relation(true)->where(['name' => $username])->find();

        $msg .= PHP_EOL . '  $user = ' . print_r($user, true);

        if ($user &&
            password_verify($password, $user['password'])
        ) {
            $authenticated = $user;
        }

        $msg .= PHP_EOL . '  $authenticated = ' . print_r($authenticated, true);
        $msg .= PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $authenticated;
    }

    /**
     * 用给定或当前用户数据生成JWT令牌。
     * @param array $user 给定用户数据
     * @return string     用给定或当前用户数据生成的经过编码的JWT令牌
     */
    public function generateJwtToken($user = null)
    {
        $tokenId    = base64_encode(mcrypt_create_iv(32));
        $issuedAt   = time();
        $notBefore  = $issuedAt;                    // 令牌有效起始时间
        $expire     = $notBefore + C('JWT_EXPIRE'); // 令牌过期时间
        $serverName = I('server.SERVER_NAME');      // Retrieve the server name

        if (!$user) {
            $user = $this->data();
        }

        if (!is_array($user)) {
            $user = json_decode(json_encode($user), true);
        }

        // \Think\Log::write(
        //     'UserModel::generateJwtToken(): $user = ' . print_r($user, true)
        //         . PHP_EOL . str_repeat('-', 80),
        //     'DEBUG'
        // );

        $data = [
            'iat'  => $issuedAt,              // Issued at: time when the token was generated
            'jti'  => $tokenId,               // Json Token Id: an unique identifier for the token
            'iss'  => $serverName,            // Issuer
            'nbf'  => $notBefore,             // Not before
            'exp'  => $expire,                // Expire
            'data' => [                       // Data related to the signer user
                'userId'   => $user['id'],    // userid from the users table
                'userName' => $user['name'],  // User name
            ]
        ];

        $secretKey = base64_decode(C('JWT_KEY'));

        $encodedToken = JWT::encode(
            $data,                  // Data to be encoded in the JWT
            $secretKey,             // The signing key
            C('JWT_HASH_ALGORITHM') // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );

        return $encodedToken;
    }

    /**
     * 解码JWT令牌来认证用户。
     * @param string $encodedToken  经过编码的JWT令牌
     * @return bool|string          若认证成功，则返回解码JWT令牌得到的用户数据；否则返回false
     */
    public function parseJwtToken($encodedToken)
    {
        $msg = PHP_EOL . 'Api\Model\UserModel::parseJwtToken():'
            . PHP_EOL . '  $encodedToken = ' . $encodedToken;

        $user = false;

        try {
            // decode the jwt using the key from config
            $secretKey = base64_decode(C('JWT_KEY'));

            $token = JWT::decode($encodedToken, $secretKey, [C('JWT_HASH_ALGORITHM')]);
            $token = json_decode(json_encode($token), true);
            $userId = $token['data']['userId'];
            $user = $this->find($userId);
            $this->protect($user);

            $msg .= PHP_EOL . '  $token = ' . print_r($token, true)
                . PHP_EOL . '  $user = ' . print_r($user, true);
        } catch (ExpiredException $ee) {
            // deal with different exceptions differently
            $msg .= PHP_EOL . '  ExpiredException: ' .  $ee->getMessage();
            throw $ee;
        } catch (Exception $e) {
            $msg .= PHP_EOL . '  Exception: ' . $e->getMessage();
        }

        // $msg .= PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $user;
    }

    /**
     * 保存制定用户的记住我令牌。
     * @param int    $id            给定用户ID
     * @param string $rememberToken 记住我令牌
     * @param string $expiredAt     记住我令牌过期时间
     * @return boolean
     */
    public function saveRememberMe($id, $rememberToken, $expiredAt)
    {
        $msg = 'UserModel.saveRememberMe():'
            . PHP_EOL . '  id = ' . $id
            . PHP_EOL . '  rememberToken = ' . $rememberToken
            . PHP_EOL . '  expiredAt = ' . $expiredAt;

        $data['remember_token'] = $rememberToken;
        $dt = new Carbon;
        $dt->timestamp = $expiredAt;
        $data['remember_expired_at'] = $dt->toDateTimeString('Y-m-d H:i:s');
        $data['updated_by'] = getCurrentUserId();
        $data['updated_at'] = getNow();

        $msg .= PHP_EOL . '  $data = ' . print_r($data, true);

        $User = M('User');
        $where = ['id' => $id];
        $result = $User->data($data)->where($where)->save();

        $msg .= PHP_EOL . '  $result = ' . print_r($result, true);
        $msg .= PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $result;
    }
}
