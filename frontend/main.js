// Add JS here
// Cek apakah token ada di localStorage saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token');
    if (token) {
        // Redirect ke halaman dashboard jika token ditemukan
        window.location.href = '/dashboard.html';
    }
});

document.getElementById('login-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('error-message');
    const countdownElement = document.getElementById('countdown');

    try {
        // Lakukan permintaan (request) POST ke endpoint login
        const response = await fetch('http://localhost/smkti/FE-BE-galeri/restapi/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password }) // Konversi data ke format JSON
        });

        if (!response.ok) {
            const errorData = await response.json();
            if (response.status === 400) {
                errorMessage.innerText = errorData.message;
            } else {
                errorMessage.innerText = 'Terjadi kesalahan';
            }
            return;
        }

        const data = await response.json(); // Mengkonversi respon menjadi format JSON

        if (data.token) {
            localStorage.setItem('token', data.token);
            errorMessage.style.color = 'green';

            // Hitungan mundur
            let countdown = 5;
            errorMessage.innerText = `Login berhasil! Anda akan diarahkan dalam ${countdown} detik.`;
            const countdownInterval = setInterval(() => {
                countdown--;
                errorMessage.innerText = `Login berhasil! Anda akan diarahkan dalam ${countdown} detik.`;
                if (countdown === 0) {
                    clearInterval(countdownInterval);
                    // Redirect ke halaman lain atau lakukan tindakan setelah login berhasil
                    window.location.href = '/dashboard.html';
                }
            }, 1000);
        } else {
            errorMessage.innerText = data.message || 'Login gagal';
        }
    } catch (error) {
        errorMessage.innerText = 'Terjadi kesalahan';
        console.error('Error:', error);
    }
});
