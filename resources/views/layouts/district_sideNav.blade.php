<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-primary">
    <div class="position-sticky">
        <!-- Sidebar content goes here -->

        <span id="nav_close" class="fs-2"><i class="fa-solid fa-xmark"></i></span>

        <div class="d-flex my-4 py-2 bg-primary justify-content-center">
            <img src="{{ asset('images/logo.png') }}" alt="" style="width:70px;height:70px">
        </div>

        <ul class="nav flex-column">
            <li class="nav-item ">
                <a class="nav-link link_tag bg-white active" href="/district_dashboard">
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link_tag bg-white" href="/district_delay_com">
                    Delay Compensation Pending Form
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link_tag bg-white" href="/district_unemp_allow">
                    Unemployement Allowance Pending Form
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link_tag bg-white" href="/district_delay_com/approval_list">
                    Approval Delay Compensation
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link_tag bg-white" href="/district_unemp_allow/approval_list">
                    Approval Unemployement Allowance
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link_tag bg-white" href="/district_notification">
                    Notification <br><span id="count_new"></span>
                </a>
            </li>
            <!-- Add more links as needed -->
        </ul>
    </div>
</nav>
