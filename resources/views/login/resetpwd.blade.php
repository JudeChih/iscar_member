<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding resetpwd-left noUse">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">密碼重置</div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="resetpwd">
            
            {{ Form::open(array('url' => 'resetpwd/'.$parameter, 'method' => 'post', 'class' => 'resetpwd-form')) }}
            
            <!-- 內容 -->
            <div class="page-content resetpwd-content animated fadeIn">
                <img src="app/image/mem_img_passwd.png" class="logo animated bounceIn">

                <div class="title animated fadeIn">密碼重置</div>

                <div class="list-block">
                    <ul>
                        <li class="align-top verifycode-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-registered" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input class="verifycode" name="verifycode" type="number" placeholder="驗證碼" onkeydown="input_limit(this, 6);" onkeyup="input_limit(this, 6);">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top password-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input name="password" class="password" type="password" placeholder="新密碼">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top passwordconfirm-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-lock" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        <input name="passwordconfirm" class="passwordconfirm" type="password" placeholder="新密碼確認">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="noUse">

                        </li>
                    </ul>
                </div>
                <div class="send">
                    <a class="button button-big reset_pwd_btn">送出</a>
                </div>
            </div>

            {{ Form::close() }}

        </div>
    </div>
</div>
