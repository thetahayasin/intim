@extends('admin.main')

@section('title', 'Asif Associates | Edit Billing')

@section('content')

@livewire('edit-billing', ['billingId' => $billingId])

@endsection

@section('scripts')
<script>
function initEditBillingSelect2() {
    if (typeof jQuery === 'undefined' || !jQuery.fn.select2 || !document.getElementById('edit_client_id')) {
        setTimeout(initEditBillingSelect2, 80);
        return;
    }

    if (jQuery('#edit_client_id').data('select2')) jQuery('#edit_client_id').select2('destroy');
    if (jQuery('#edit_firm').data('select2')) jQuery('#edit_firm').select2('destroy');

    jQuery('#edit_client_id').select2({ width: '100%', placeholder: '-- Select Client --', allowClear: true });
    jQuery('#edit_firm').select2({ width: '100%', placeholder: '-- Select Firm --', allowClear: true });

    jQuery('#edit_client_id').off('change.lwedit').on('change.lwedit', function() {
        var h = document.getElementById('edit_client_id_hidden');
        if (h) { h.value = jQuery(this).val() || ''; h.dispatchEvent(new Event('input', { bubbles: true })); }
    });

    jQuery('#edit_firm').off('change.lwedit').on('change.lwedit', function() {
        var h = document.getElementById('edit_firm_hidden');
        if (h) { h.value = jQuery(this).val() || ''; h.dispatchEvent(new Event('input', { bubbles: true })); }
    });
}

document.addEventListener('DOMContentLoaded', initEditBillingSelect2);
document.addEventListener('livewire:navigated', initEditBillingSelect2);
</script>
@endsection
