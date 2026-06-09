@extends('admin.main')

@section('title', 'Asif Associates | New Receipt')

@section('content')

@livewire('create-receipt')

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var initInterval = setInterval(function() {
            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                clearInterval(initInterval);
                
                jQuery('#receipt_client_id').select2({ width: '100%', placeholder: '-- Select Client --', allowClear: true });

                jQuery('#receipt_client_id').on('change', function() {
                    var component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                    if (component) component.set('client_id', jQuery(this).val() || '');
                });
            }
        }, 100);
    });
</script>
@endsection
