<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{$Think.lang.POST_LISTING}</h1>
        </div>
    </div>

    <if condition="isAuthenticated()">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar" role="toolbar" aria-label="post-toolbar">
                <div class="btn-group" role="group" aria-label="post-filter">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {$Think.lang.FILTER_BY_AUTHOR}
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="{:U('/posts')}">{$Think.lang.ALL_POSTS}</a></li>
                        <li><a href="{:U('/posts', ['filter' => 'author|me'])}">{$Think.lang.MY_POSTS}</a></li>
                    </ul>
                </div>
                <div class="btn-group" role="group" aria-label="create-post">
                    <a href="{:U('/posts/create')}" class="btn btn-primary">{$Think.lang.CREATE_POST}</a>
                </div>
            </div>
        </div>
    </div>
    </if>

    <div class="post-listing row">
        <div class="col-md-10">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{$Think.lang.SERIAL_NO}</th>
                        <th class="text-center">{$Think.lang.TITLE}</th>
                        <th class="text-center">{$Think.lang.CONTENT}</th>
                        <th class="text-center">{$Think.lang.AUTHOR}</th>
                        <th class="text-center">{$Think.lang.ACTION}</th>
                    </tr>
                </thead>
                <tbody>
                <volist name="posts['data']" id="post" key="no" empty="{:$Think.lang.NO_DATA_FOUND}">
                    <tr>
                        <td>{$no}</td>
                        <td>{$post.title}</td>
                        <td>{$post.content}</td>
                        <td>{:getUserFullName($post['author'])}</td>
                        <td>
                            <a href="{:U('/posts/update/' . $post['id'])}" class="btn btn-primary"
                                <if condition="!isAuthenticated() || getCurrentUser()['id'] != $post['author_user_id']">
                                    disabled="true"
                                </if>
                            >
                                {$Think.lang.CHANGE}
                            </a>
                            <button type="button" class="btn btn-danger"
                                    data-toggle="confirmation" data-popout="true"
                                    data-singleton="true" data-html="true"
                                    data-btn-ok-class="btn-success"
                                    data-btn-cancel-class="btn-danger"
                                    data-title="<h5 class='text-center'><strong>{$Think.lang.CONFIRM_TITLE}</strong></h5>"
                                    data-content="{:L('CONFIRM_TO_DELETE', ['model' => strtolower(L('POST'))])}"
                                    data-delete-url="{:U('/posts/delete/' . $post['id'])}"
                                    <if condition="!isAuthenticated() || getCurrentUser()['id'] != $post['author_user_id']">
                                        disabled="true"
                                    </if>
                            >
                                {$Think.lang.DELETE}
                            </button>
                        </td>
                    </tr>
                </volist>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-center">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    {:$posts['pagination']}
                                </ul>
                            </nav>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <form class="delete-post-form form-inline" action="#" method="POST">
        </form>
    </div>
</div>
