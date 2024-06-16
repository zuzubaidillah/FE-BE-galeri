// Add JS here
// Cek apakah token ada di localStorage saat halaman dimuat
document.addEventListener("DOMContentLoaded", () => {
	const token = localStorage.getItem("token");
	if (!token) {
		// Redirect ke halaman dashboard jika token ditemukan
		window.location.href = "/index.html";
	}

	getCurrentUsers()
});

async function getCurrentUsers() {
	const token = localStorage.getItem("token");

	try {
		// Lakukan permintaan (request) POST ke endpoint login
		const response = await fetch("http://localhost/smkti/FE-BE-galeri/restapi/api/auth/current", {
			method: "GET",
			headers: {
				Authorization: `Bearer ${token}`,
				"Content-Type": "application/json"
			},
		});

		if (!response.ok) {
			const errorData = await response.json();
			if (response.status === 401) {
				localStorage.clear()
				alert(errorData.message)
				window.location.href = "/index.html";
			}
			return;
		}

		const data = await response.json(); // Mengkonversi respon menjadi format JSON

		if (data.data) {
			localStorage.setItem("currentUsers", JSON.stringify(data.data));
      document.getElementById("usersName").innerText = data.data.nama;
      document.getElementById("usersLevel").innerText = data.data.level;
		}
	} catch (error) {
		console.error("Error:", error);
	}
}
