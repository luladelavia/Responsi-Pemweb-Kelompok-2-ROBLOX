document.addEventListener("DOMContentLoaded", function() {
    const formAuth = document.getElementById("formAuth");
    if (formAuth) {
        formAuth.addEventListener("submit", function(e) {
            const inputUser = document.getElementById("username_or_email");
            const inputPass = document.getElementById("password");

            if (inputUser && inputUser.value.trim() === "") {
                alert("Kolom Username/Email tidak boleh dikosongkan!");
                e.preventDefault();
                return;
            }

            if (inputPass && inputPass.value.trim() === "") {
                alert("Kolom Password tidak boleh dikosongkan!");
                e.preventDefault();
                return;
            }

            if (inputPass && inputPass.value.length < 8) {
                alert("Keamanan Akun: Password minimal harus terdiri dari 8 karakter!");
                e.preventDefault();
            }
        });
    }
});