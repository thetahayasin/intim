@extends('admin.main')

@section('title', 'Asif Associates | New Billing')

@section('content')

@livewire('create-billing')

@endsection

@section('scripts')
<script>
function initCreateBillingSelect2() {
    if (typeof jQuery === 'undefined' || !jQuery.fn.select2 || !document.getElementById('billing_client_id')) {
        setTimeout(initCreateBillingSelect2, 80);
        return;
    }

    if (jQuery('#billing_client_id').data('select2')) jQuery('#billing_client_id').select2('destroy');
    if (jQuery('#billing_firm').data('select2')) jQuery('#billing_firm').select2('destroy');

    jQuery('#billing_client_id').select2({ width: '100%', placeholder: '-- Select Client --', allowClear: true });
    jQuery('#billing_firm').select2({ width: '100%', placeholder: '-- Select Firm --', allowClear: true });

    jQuery('#billing_client_id').off('change.lwcreate').on('change.lwcreate', function() {
        var h = document.getElementById('billing_client_id_hidden');
        if (h) { h.value = jQuery(this).val() || ''; h.dispatchEvent(new Event('input', { bubbles: true })); }
    });

    jQuery('#billing_firm').off('change.lwcreate').on('change.lwcreate', function() {
        var h = document.getElementById('billing_firm_hidden');
        if (h) { h.value = jQuery(this).val() || ''; h.dispatchEvent(new Event('input', { bubbles: true })); }
    });
}

document.addEventListener('DOMContentLoaded', initCreateBillingSelect2);
document.addEventListener('livewire:navigated', initCreateBillingSelect2);
</script>
@endsection
