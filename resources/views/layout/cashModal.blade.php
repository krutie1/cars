{{--    Cash Modal--}}
<div class="modal fade" id="cashModal" tabindex="-1" aria-labelledby="cashModal"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cashModal">Внести платёж</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{--                    <form method="POST" action="/createClient">--}}
                <form id="createCash" method="POST" action="{{ route('transactions.insert') }}">
                    <div class="mb-3">
                        <input type="number" name="amount" class="form-control amount"
                               placeholder="Сумма" id="amount">

                        <div id="amount-error" class="invalid-feedback"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Внести платёж</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
