<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding logout-left noUse">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding"></div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="logout">

            <!-- 內容 -->
            <div class="page-content logout-content">

                @if($errors->any())
                <input class="logout_error" type="hidden" value="{{ $errors->first() }}">
                @endif

            </div>


        </div>
    </div>
</div>
