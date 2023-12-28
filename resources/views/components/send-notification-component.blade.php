<div class="d-flex col-md-8 col-12 main_send_notification_div">
    <div class="d-flex flex-wrap  col-12  send_notification_div_1 ">
        <div class="d-flex flex-wrap col-12 justify-content-center send_notification_div_2">
            <p>New Notification</p>
            <p>From: {{ Auth::user()->login_email }}</p>
        </div>
        <form class="d-flex  col-12 justify-content-center send_notification_div_3" id="send_notify_form">
            @csrf
            <div class="d-flex flex-wrap col-md-8 col-11 justify-content-center send_notify_div_1">
                <div class="d-flex col-md-6 col-12 flex-wrap send_notify_div_2">
                    <p class="col-11">Select District</p>
                    <select name="district_code" class="col-11" id="send_notify_select_1">
                        <option value="999" selected>Select</option>
                        <option value="999">All</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->district_code }}">{{ $district->district_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex col-md-6 col-12 flex-wrap send_notify_div_2">
                    <p class="col-11">Select Block</p>
                    <select name="block_code" class="col-11" id="send_notify_select_2">
                        <option value="" selected>Select</option>
                    </select>
                </div>
                <div class="d-flex col-md-8 col-12 flex-wrap send_notify_div_2">
                    <p class="col-6">Select Document </p>
                    <input type="file" name="notify_file" style="display: none" id="send_notify_file"
                        accept=".pdf,.PDF">
                    <button type="button" class="col-6" id="send_notify_file_btn"><i class="fa-solid fa-file"></i>
                        Upload Document</button>
                </div>
                <div class="d-flex col-md-11 col-12 flex-wrap flex-column send_notify_div_2">
                    <p>Enter Notification Subject ...</p>
                    <input type="text" name="notify_name" class="col-8 p-1" placeholder="Enter Subject">
                </div>
                <div class="d-flex col-md-11 col-12 flex-wrap send_notify_div_2">
                    <p class="col-11">Type Your Discription</p>
                    <textarea class="col-12" name="notify_text" id="" placeholder="Add Description Here"></textarea>
                </div>
                <button type="submit" class="col-3 send_notify_btn">Send ...</button>
            </div>
        </form>
    </div>
</div>
