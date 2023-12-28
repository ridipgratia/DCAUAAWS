<div class="d-flex flex-wrap col-md-3 col-9 send_notification_div ">
    <p class="col-12 notify_para_1">NOTIFICATION</p>
    <button class="col-12 notify_btn_1" id="notification_btn"><i class="fa-solid fa-plus"></i> Add Notification</button>
    <p class="col-12 notify_para_2">VIEW NOTIFICATIONS </p>
    <div class="d-flex flex-wrap col-12 main_view_notification_div ">
        @php
            $count = 1;
        @endphp
        @foreach ($notifications as $notify)
            <button class="d-flex col-12 justify-content-around view_notification_btn view_notification_div "
                value="{{ $notify->id }}">
                <p class="col-1 notify_para_3">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                </p>
                <p class="col-9 notify_para_3">{{ $count . '. ' . $notify->description }}</p>
                <p class="col-1 notify_para_3">
                    <i class="fa-solid fa-bell"></i>
                </p>
                @php
                    $count++;
                @endphp
            </button>
        @endforeach
    </div>
</div>
