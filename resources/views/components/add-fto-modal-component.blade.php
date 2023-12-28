<!-- Modal -->
<div class="modal fade" id="add_fto_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <p>Add FTO Number For {{ $addTo }}</p>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="" id="fto_input"
                                aria-label="Example text with button addon" aria-describedby="button-addon1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id='submit_fto_btn' class="btn btn-primary">Submit FTO</button>
            </div>
        </div>
    </div>
</div>
