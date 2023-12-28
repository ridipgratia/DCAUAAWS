<div
    class="d-flex flex-column align-content-center flex-wrap col-12 justify-content-center border main_recive_notify_div ">
    <div class="d-flex col-md-6 col-11 mt-2 justify-content-center  recive_notiy_div border">
        @php
            $count = count($notifications);
        @endphp
        <p class="d-flex align-items-center recive_notify_head col-11"><span>Notifications </span> &nbsp;<span
                class="recive_count">{{ $count }}</span>
        </p>
    </div>
    @foreach ($notifications as $notification)
        <div class="d-flex flex-wrap col-md-6 col-11 justify-content-center main_recive_notify_text_div border">
            <div class="d-flex flex-wrap col-12  justify-content-around align-items-center recive_notify_text_div">
                <p class=" recive_notify_para"><i class="fa-solid fa-envelope"></i></p>
                <p class="d-flex flex-wrap flex-column recive_notify_para"><span>admin@gamil.com
                        (State)
                    </span><span>{{ $notification->subject }}</span>
                </p>
                <p class="col-1 recive_notify_para new_notify">{{ $notification->new }}</p>
            </div>
            <div class="d-flex col-12 justify-content-around align-items-center">
                <div class="col-3">
                    <button class="col-12 recive_notify_btn" value="{{ $notification->id }}">View Notice</button>
                </div>
                <div class="col-4">
                    <p class="col-12 recive_notify_time">{{ $notification->sent_time }}</p>
                </div>
                <div class="col-4">
                    <p class="col-12 recive_notify_time">{{ $notification->date }}</p>
                </div>

            </div>
        </div>
    @endforeach
</div>
