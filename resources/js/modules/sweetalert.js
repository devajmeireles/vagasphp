import 'sweetalert';

window.addEventListener('swal:common', event => {
    swal({
        title: event.detail.message,
        text: event.detail.text,
        icon: event.detail.type,
        closeOnClickOutside: event.detail.dismissable ?? true,
    });
});

window.addEventListener('swal:confirm', event => {
    return swal({
        title: event.detail.message,
        text: event.detail.text,
        icon: event.detail.type,
        buttons: ['Cancelar', 'OK'],
        dangerMode: true,
        closeOnClickOutside: event.detail.dismissable ?? true,
    }).then((confirm) => {
        if (confirm) {
            return Livewire.emit(event.detail.confirm, event.detail.append || null)
        }

        return Livewire.emit(event.detail.cancel, event.detail.append || null)
    });
});
