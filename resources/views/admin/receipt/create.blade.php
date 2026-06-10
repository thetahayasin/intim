@extends('admin.main')

@section('title', 'Asif Associates | New Receipt')

@section('content')

@livewire('create-receipt')

@endsection

@section('scripts')
<script>
function initReceiptSelect2() {
    if (!document.getElementById('receipt_client_id')) return;
    if (typeof jQuery === 'undefined' || !jQuery.fn.select2) { setTimeout(initReceiptSelect2, 80); return; }

    if (jQuery('#receipt_client_id').data('select2')) jQuery('#receipt_client_id').select2('destroy');
    jQuery('#receipt_client_id').select2({ width: '100%', placeholder: '-- Select Client --', allowClear: true });

    jQuery('#receipt_client_id').off('change.lwr').on('change.lwr', function() {
        var el = document.querySelector('[wire\\:id]');
        if (el) { var c = Livewire.find(el.getAttribute('wire:id')); if (c) c.set('client_id', jQuery(this).val() || ''); }
    });
}

document.removeEventListener('livewire:navigated', initReceiptSelect2);
document.addEventListener('livewire:navigated', initReceiptSelect2);
initReceiptSelect2();
</script>
@endsection
