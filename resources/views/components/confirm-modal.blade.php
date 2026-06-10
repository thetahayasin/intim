<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:0;border:1px solid #e0e0e0;">
            <div class="modal-header" style="border-bottom:1px solid #e0e0e0;padding:.9rem 1.25rem;">
                <h6 class="modal-title mb-0 font-weight-bold" id="confirmDeleteModalLabel">Confirm Delete</h6>
            </div>
            <div class="modal-body" style="padding:.9rem 1.25rem;">
                <p class="mb-0" id="confirmDeleteMessage">Are you sure you want to delete this item? This action cannot be undone.</p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #e0e0e0;padding:.6rem 1.25rem;">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-secondary btn-sm">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function initDeleteModal() {
        document.addEventListener('click', function(e) {
            var trigger = e.target.closest('[data-confirm-delete]');
            if (!trigger) return;
            e.preventDefault();

            var msg     = trigger.getAttribute('data-confirm-delete') || 'Are you sure you want to delete this item?';
            var formSel = trigger.getAttribute('data-form');
            var form    = formSel ? document.querySelector(formSel) : trigger.closest('form');

            document.getElementById('confirmDeleteMessage').textContent = msg;
            document.getElementById('confirmDeleteBtn').onclick = function() {
                $('#confirmDeleteModal').modal('hide');
                if (form) form.submit();
            };
            $('#confirmDeleteModal').modal('show');
        });
    }
    initDeleteModal();
})();
</script>
