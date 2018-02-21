<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding regiest-left">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">註冊</div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="regiest">

            <!-- 內容 -->
            <div class="page-content regiest-content">

                <img src="app/image/mem_img_signup.png" class="logo animated bounceIn">
                <div class="title">選擇註冊方式</div>
                <div class="row no-gutter">
                    <div class="animated zoomIn button button-big iscar_regiest_btn" onclick="goTo('regiestiscar')"><img src="app/image/mem_btn_signup_iscar.png"><div>註冊新帳號</div></div>
                    {!! $button !!}
                    {{-- <div class="animated zoomIn button button-big fb-regiest fb_regiest_btn"><img src="app/image/mem_btn_signup_fb.png"><div>Facebook</div></div> --}}
                </div>
                <!--<div class="row no-gutter">
                    <div class="col-50 animated zoomIn google-regiest"><img src="app/image/google_icon.png"></div>
                    <div class="col-50 animated zoomIn wechat-regiest"><img src="app/image/wechat_icon.png"></div>
                </div>-->

            </div>

        </div>
    </div>
</div>
