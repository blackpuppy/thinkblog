<?php
namespace Home\Controller;

use Think\Controller;
use Think\Model;

class PostController extends Controller
{
    /**
     * 文章列表。
     * @return void
     */
    public function index()
    {
        $msg = PHP_EOL . 'Home\Controller\PostController::index():';

        try {
            $Post = D('Post');
            $posts = $Post->order(['id' => 'desc'])->select();

            $msg .= PHP_EOL . '  $posts = ' . print_r($posts, true);

            $title = L('POST_LISTING');

            $this->assign(compact('title', 'posts'));

            $this->display();
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            // \Think\Log::write($msg, 'INFO');
        }
    }

    /**
     * 添加文章。
     * @return void
     */
    public function create()
    {
        if (!IS_GET && !IS_POST) {
            $this->redirect(U('/posts'));
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\PostController::create():';

        $this->assign('title', L('CREATE_POST'));

        if (IS_GET) {
            $this->display();
        } elseif (IS_POST) {
            try {
                $Post = D('Post');

                $newPost = $Post->create();

                $msg .= PHP_EOL . '  $newPost = ' . print_r($newPost, true);

                if (!$newPost) {
                    $msg .= PHP_EOL . '  validation error: ' . $Post->getError();

                    $data = [
                        'post' => I('post.'),
                        'validationError' => $Post->getError(),
                    ];
                    $this->assign($data);

                    $this->display();
                } else {
                    $result = $Post->add();

                    $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

                    if ($result !== false) {
                        $this->success(L('SAVE_POST_SUCCESS'), U('/posts'), 3);
                    } else {
                        $this->error(L('SAVE_POST_FAILURE'), U('/posts'), 5);
                    }
                }
            } catch (Exception $e) {
                $msg .= PHP_EOL . '  error: ' . $e->getMessage();
                throw $e;
            } finally {
                $msg .= PHP_EOL . str_repeat('-', 80);
                \Think\Log::write($msg, 'DEBUG');
            }
        }
    }

    /**
     * 修改文章。
     * @return void
     */
    public function update($id)
    {
        if (!IS_GET && !IS_POST) {
            $this->redirect(U('/posts'));
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\PostController::update():'
            . PHP_EOL . '  $id = ' . $id
            . PHP_EOL . '  REQUEST_METHOD = ' . REQUEST_METHOD;

        $this->assign('title', L('CHANGE_POST'));

        try {
            $Post = D('Post');
            $oldPost = $Post->find($id);

            $msg .= PHP_EOL . '  $oldPost = ' . print_r($oldPost, true);

            if (!$oldPost) {
                $msg .= PHP_EOL . '  ' . L('POST_NOT_FOUND');

                $this->error(L('POST_NOT_FOUND'), U('/posts'), 5);
            }

            if (IS_GET) {
                $this->assign('post', $oldPost);
                $this->display();
            } elseif (IS_POST) {
                $updatedPost = $Post->create();

                $msg .= PHP_EOL . '  $updatedPost = ' . print_r($updatedPost, true);

                if (!$updatedPost) {
                    $msg .= PHP_EOL . '  validation error: ' . $Post->getError();

                    $data = [
                        'post' => I('post.'),
                        'validationError' => $Post->getError(),
                    ];
                    $this->assign($data);

                    $this->display();
                } else {
                    $msg .= PHP_EOL . '  validation passed';

                    $result = $Post->save();

                    $msg .= PHP_EOL . '  $result = ' . print_r($result, true);
                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'INFO');

                    if ($result !== false) {
                        $this->success(L('SAVE_POST_SUCCESS'), U('/posts'), 3);
                    } else {
                        $this->error(L('SAVE_POST_FAILURE'), U('/posts'), 5);
                    }
                }
            }
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'INFO');
        }
    }

    /**
     * 删除文章。
     * @return void
     */
    public function delete($id)
    {
        if (!IS_POST) {
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\PostController::delete():'
            . PHP_EOL . '  $id = ' . $id;

        try {
            $Post = D('Post');
            $post = $Post->find($id);

            $msg .= PHP_EOL . '  $post = ' . print_r($post, true);

            if (!$post) {
                $msg .= PHP_EOL . '  ' . L('POST_NOT_FOUND');

                $this->error(L('POST_NOT_FOUND'), U('/posts'), 5);
            }

            $result = $Post->delete();

            $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

            if ($result !== false) {
                $this->success(L('DELETE_POST_SUCCESS'), U('/posts'), 3);
            } else {
                $this->error(L('DELETE_POST_FAILURE'), U('/posts'), 5);
            }
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'INFO');
        }
    }
}
