<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding forgetpwd-left">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">忘記密碼</div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="forgetpwd">
            
            {{ Form::open(array('url' => 'forgetpwd/'.$parameter, 'method' => 'post', 'class' => 'forgetpwd-form')) }}

            <!-- 內容 -->
            <div class="page-content forgetpwd-content animated fadeIn">
                <div class="font_style">請輸入註冊的Email</div>
                <div class="content-block">

                            <div class="item_input">
                                <input name="account" class="account" type="text">
                            </div>
                        
                            <div class="item-content">
                                <div class="item-inner">
                                    <div style="color: firebrick; font-size: .9em;">* 系統將發送密碼重置信到Email信箱</div>
                                </div>
                            </div>
                        
                </div>
                <div class="send">
                    <a class="button button-big forget_pwd_btn">送出</a>
                </div>
            </div>



            {{ Form::close() }}

        </div>
    </div>
</div>
