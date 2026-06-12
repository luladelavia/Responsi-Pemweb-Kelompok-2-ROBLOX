document.addEventListener("DOMContentLoaded", function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    
    function formatRibuan(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    checkboxes.forEach(box => {
        box.addEventListener('change', function() {
            let totalHargaBarang = 0;
            let namaBarangTerpilih = "-";
            let jumlahTerpilih = 0;

            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            jumlahTerpilih = checkedBoxes.length;

            checkedBoxes.forEach((checkedBox, index) => {
                totalHargaBarang += parseInt(checkedBox.getAttribute('data-harga'));
               
                if (index === 0) {
                    namaBarangTerpilih = checkedBox.getAttribute('data-nama');
                }
            });

            let biayaAdmin = jumlahTerpilih > 0 ? 5 : 0;
            let totalBayar = totalHargaBarang + biayaAdmin;

            if (jumlahTerpilih > 1) {
                document.getElementById('summary-name').innerText = `${namaBarangTerpilih} (+${jumlahTerpilih - 1})`;
            } else {
                document.getElementById('summary-name').innerText = namaBarangTerpilih;
            }

            document.getElementById('summary-item-price').innerText = "⬡ " + formatRibuan(totalHargaBarang);
            document.getElementById('summary-admin-price').innerText = "⬡ " + formatRibbus(biayaAdmin);
            document.getElementById('summary-total-price').innerText = "⬡ " + formatRibuan(totalBayar);
        });
    });
});