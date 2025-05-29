function formatNumber(value) {
    value = value.replace(/\D/g, '');
    if (!value) return '';
    return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function updateDisplayAndRaw(input) {
    let raw = input.val().replace(/\D/g, '');

    // Format hiển thị
    let formatted = formatNumber(raw);
    input.val(formatted);

    // Cập nhật input hidden với số thô
    $('.number-raw-input').val(raw);
}

$(document).ready(function () {
    const displayInput = $('.number-display-input');

    // ✅ Khi trang tải, format nếu có giá trị sẵn
    updateDisplayAndRaw(displayInput);

    // ✅ Khi người dùng nhập
    displayInput.on('input', function () {
      updateDisplayAndRaw($(this));
    });
});